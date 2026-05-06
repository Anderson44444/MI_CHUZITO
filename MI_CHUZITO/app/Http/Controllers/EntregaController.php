<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class EntregaController extends Controller
{
    public function index()
    {
        $pedidos = DB::table('orders')
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Entregas/Index', [
            'pedidos' => $pedidos,
        ]);
    }

    public function enRuta($id)
    {
        $pedido = DB::table('orders')->where('id', $id)->first();

        if (!$pedido || $pedido->status !== 'en_proceso') {
            return redirect()->route('entregas.index')
                ->with('error', 'El pedido no puede marcarse como En Ruta.');
        }

        DB::table('orders')->where('id', $id)->update([
            'status' => 'en_ruta',
            'updated_at' => now(),
        ]);

        DB::table('deliveries')->updateOrInsert(
            ['order_id' => $id],
            [
                'delivery_person' => 'Repartidor 1',
                'started_at' => now(),
                'delivered_at' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        return redirect()->route('entregas.index')
            ->with('success', 'Pedido marcado como En Ruta.');
    }

    public function confirmar($id)
    {
        $pedido = DB::table('orders')->where('id', $id)->first();

        if (!$pedido || $pedido->status !== 'en_ruta') {
            return redirect()->route('entregas.index')
                ->with('error', 'Solo se puede confirmar entrega de pedidos En Ruta.');
        }

        DB::table('orders')->where('id', $id)->update([
            'status' => 'entregado',
            'updated_at' => now(),
        ]);

        DB::table('deliveries')->where('order_id', $id)->update([
            'delivered_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('entregas.index')
            ->with('success', 'Entrega confirmada correctamente.');
    }

    public function show($id)
    {
        $pedido = DB::table('orders')->where('id', $id)->first();
        $entrega = DB::table('deliveries')->where('order_id', $id)->first();

        return Inertia::render('Entregas/Show', [
            'pedido' => $pedido,
            'entrega' => $entrega,
        ]);
    }
}