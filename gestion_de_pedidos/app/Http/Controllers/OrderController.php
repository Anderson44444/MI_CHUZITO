<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
   public function store(Request $request)
{
    $order = Order::create([
        'total' => $request->total,
        'customer_name' => $request->customer_name,
        'address' => $request->address,
        'phone' => $request->phone,
        'status' => 'En proceso'
    ]);

    foreach ($request->items as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'quantity' => $item['cantidad'],
            'price' => $item['price'],
            'salsas' => implode(', ', $item['salsas'] ?? []),
            'acompanamientos' => implode(', ', $item['acompanamientos'] ?? [])
        ]);
    }  // ← foreach cierra aquí

    return response()->json(['success' => true]);  // ← fuera del foreach
} 
} // ← función cierra aquí