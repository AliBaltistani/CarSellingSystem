<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::with(['car.category', 'car.images'])
            ->where('user_id', auth()->id())
            ->latest('created_at')
            ->paginate(12);

        return view('favorites.index', compact('favorites'));
    }

    public function toggle(Car $car)
    {
        $favorite = Favorite::where('user_id', auth()->id())
            ->where('car_id', $car->id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $car->decrement('favorites_count');
            $message = 'Removed from favorites.';
            $isFavorited = false;
        } else {
            Favorite::create([
                'user_id' => auth()->id(),
                'car_id' => $car->id,
                'created_at' => now(),
            ]);
            $car->increment('favorites_count');
            $message = 'Added to favorites!';
            $isFavorited = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'is_favorited' => $isFavorited,
                'favorites_count' => $car->fresh()->favorites_count,
            ]);
        }

        return back()->with('success', $message);
    }
}
