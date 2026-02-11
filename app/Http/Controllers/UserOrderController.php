<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()
            ->orders()
            ->with('offer')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
