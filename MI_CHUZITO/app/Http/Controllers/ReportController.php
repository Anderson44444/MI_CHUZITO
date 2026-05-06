<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index()
    {
        return Inertia::render('Reports/Index');
    }

    public function ventas(Request $request)
    {
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->toDateString());

        $totalVentas = DB::table('orders')
            ->whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->where('status', 'entregado')
            ->sum('total');

        $totalPedidos = DB::table('orders')
            ->whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->count();

        $productosMasVendidos = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$desde, $hasta . ' 23:59:59'])
            ->where('orders.status', 'entregado')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_vendido'), DB::raw('SUM(order_items.quantity * order_items.unit_price) as total_ingresos'))
            ->groupBy('menu_items.name')
            ->orderByDesc('total_vendido')
            ->get();

        return Inertia::render('Reports/Ventas', [
            'totalVentas' => $totalVentas,
            'totalPedidos' => $totalPedidos,
            'productosMasVendidos' => $productosMasVendidos,
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }

    public function inventario()
    {
        $productos = DB::table('menu_items')
            ->select('name', 'category', 'price', 'active')
            ->get()
            ->map(function ($item) {
                $item->estado = $item->active ? 'Disponible' : 'Agotado';
                return $item;
            });

        $disponibles = $productos->where('active', 1)->count();
        $agotados = $productos->where('active', 0)->count();

        return Inertia::render('Reports/Inventario', [
            'productos' => $productos,
            'disponibles' => $disponibles,
            'agotados' => $agotados,
        ]);
    }

    public function pedidos(Request $request)
    {
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->toDateString());

        $totalPedidos = DB::table('orders')
            ->whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->count();

        $porEstado = DB::table('orders')
            ->whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $pedidos = DB::table('orders')
            ->whereBetween('created_at', [$desde, $hasta . ' 23:59:59'])
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Reports/Pedidos', [
            'totalPedidos' => $totalPedidos,
            'porEstado' => $porEstado,
            'pedidos' => $pedidos,
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }

    public function tiempos(Request $request)
    {
        $desde = $request->get('desde', now()->startOfMonth()->toDateString());
        $hasta = $request->get('hasta', now()->toDateString());

        $entregas = DB::table('deliveries')
            ->join('orders', 'deliveries.order_id', '=', 'orders.id')
            ->whereBetween('deliveries.created_at', [$desde, $hasta . ' 23:59:59'])
            ->whereNotNull('deliveries.delivered_at')
            ->select(
                'orders.id',
                'orders.customer_name',
                'deliveries.delivery_person',
                'deliveries.started_at',
                'deliveries.delivered_at',
                DB::raw('TIMESTAMPDIFF(MINUTE, deliveries.started_at, deliveries.delivered_at) as minutos')
            )
            ->orderByDesc('deliveries.created_at')
            ->get();

        $promedioMinutos = $entregas->avg('minutos');

        return Inertia::render('Reports/Tiempos', [
            'entregas' => $entregas,
            'promedioMinutos' => round($promedioMinutos, 1),
            'desde' => $desde,
            'hasta' => $hasta,
        ]);
    }
}
