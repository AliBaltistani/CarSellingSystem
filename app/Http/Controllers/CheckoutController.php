<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Order;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Show offer detail page
     */
    public function show(Offer $offer)
    {
        if (!$offer->is_active || $offer->is_expired) {
            abort(404);
        }

        $relatedOffers = Offer::active()
            ->where('id', '!=', $offer->id)
            ->ordered()
            ->limit(3)
            ->get();

        return view('offers.show', compact('offer', 'relatedOffers'));
    }

    /**
     * Create Stripe Checkout Session and redirect
     */
    public function checkout(Request $request, Offer $offer)
    {
        if (!$offer->is_active || $offer->is_expired) {
            return back()->with('error', 'This offer is no longer available.');
        }

        if (!$offer->price_from || $offer->price_from <= 0) {
            return back()->with('error', 'This offer does not have a valid price.');
        }

        $user = auth()->user();

        // Create order record
        $order = Order::create([
            'user_id' => $user->id,
            'offer_id' => $offer->id,
            'order_number' => Order::generateOrderNumber(),
            'amount' => $offer->price_from,
            'currency' => 'aed',
            'status' => 'pending',
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => $user->phone,
            'metadata' => [
                'offer_title' => $offer->title,
                'offer_badge' => $offer->badge,
                'offer_features' => $offer->features,
                'offer_description' => $offer->description,
                'offer_price_label' => $offer->price_label,
            ],
        ]);

        // Build Stripe Checkout Session
        $sessionParams = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'aed',
                    'product_data' => [
                        'name' => $offer->title,
                        'description' => $offer->description ? substr($offer->description, 0, 500) : 'Exclusive deal package',
                    ],
                    'unit_amount' => (int) ($offer->price_from * 100), // Stripe uses cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel') . '?order=' . $order->order_number,
            'customer_email' => $user->email,
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'offer_id' => $offer->id,
            ],
        ];

        // Use pre-created Stripe price if available
        if ($offer->stripe_price_id) {
            $sessionParams['line_items'] = [[
                'price' => $offer->stripe_price_id,
                'quantity' => 1,
            ]];
        }

        try {
            $session = StripeSession::create($sessionParams);

            // Update order with session ID
            $order->update([
                'stripe_checkout_session_id' => $session->id,
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            \Log::error('Stripe Checkout Error: ' . $e->getMessage(), [
                'offer_id' => $offer->id,
                'order_id' => $order->id,
                'trace' => $e->getTraceAsString(),
            ]);

            $order->update(['status' => 'failed']);

            return back()->with('error', 'Unable to process payment. Please try again. ' . (config('app.debug') ? $e->getMessage() : ''));
        }
    }

    /**
     * Handle successful checkout return
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if (!$sessionId) {
            return redirect()->route('home')->with('error', 'Invalid checkout session.');
        }

        try {
            $session = StripeSession::retrieve($sessionId);
            $order = Order::where('stripe_checkout_session_id', $sessionId)->first();

            if ($order && $session->payment_status === 'paid') {
                $order->update([
                    'status' => 'paid',
                    'stripe_payment_intent_id' => $session->payment_intent,
                    'paid_at' => now(),
                ]);
            }

            return view('checkout.success', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Unable to verify payment.');
        }
    }

    /**
     * Handle cancelled checkout
     */
    public function cancel(Request $request)
    {
        $orderNumber = $request->get('order');
        $order = null;

        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)
                ->where('user_id', auth()->id())
                ->first();
        }

        return view('checkout.cancel', compact('order'));
    }
}
