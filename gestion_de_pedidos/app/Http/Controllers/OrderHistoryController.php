<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();

        return Inertia::render('Orders', [
            'orders' => $orders
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->status !== 'En proceso') {

            return response()->json([
                'message' => 'No se puede modificar'
            ], 400);

        }

        $order->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Estado actualizado'
        ]);
    }
}