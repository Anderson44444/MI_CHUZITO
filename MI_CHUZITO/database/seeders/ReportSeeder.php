<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $menuItems = [
            ['name' => 'Chuzo de Pollo', 'description' => 'Delicioso chuzo de pollo a la plancha', 'price' => 8000, 'category' => 'Chuzos', 'active' => true],
            ['name' => 'Chuzo de Res', 'description' => 'Chuzo de res jugoso a las brasas', 'price' => 10000, 'category' => 'Chuzos', 'active' => true],
            ['name' => 'Hamburguesa', 'description' => 'Hamburguesa con papas fritas', 'price' => 15000, 'category' => 'Hamburguesas', 'active' => true],
        ];

        foreach ($menuItems as $item) {
            DB::table('menu_items')->insertOrIgnore(array_merge($item, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $menuItemIds = DB::table('menu_items')->pluck('id')->toArray();

        $customers = ['Juan Pérez', 'María López', 'Carlos García', 'Ana Martínez', 'Luis Rodríguez'];
        $statuses = ['entregado', 'entregado', 'entregado', 'cancelado', 'en_proceso'];

        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(rand(0, 30));
            $status = $statuses[array_rand($statuses)];

            $orderId = DB::table('orders')->insertGetId([
                'customer_name' => $customers[array_rand($customers)],
                'total' => 0,
                'status' => $status,
                'created_at' => $date,
                'updated_at' => $date,
            ]);

            $total = 0;
            $numItems = rand(1, 3);
            for ($j = 0; $j < $numItems; $j++) {
                $menuItemId = $menuItemIds[array_rand($menuItemIds)];
                $menuItem = DB::table('menu_items')->where('id', $menuItemId)->first();
                $quantity = rand(1, 4);
                $unitPrice = $menuItem->price;
                $total += $quantity * $unitPrice;

                DB::table('order_items')->insert([
                    'order_id' => $orderId,
                    'menu_item_id' => $menuItemId,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }

            DB::table('orders')->where('id', $orderId)->update(['total' => $total]);

            if ($status === 'entregado') {
                $started = $date->copy()->addMinutes(rand(5, 15));
                $delivered = $started->copy()->addMinutes(rand(10, 45));

                DB::table('deliveries')->insert([
                    'order_id' => $orderId,
                    'delivery_person' => 'Repartidor ' . rand(1, 3),
                    'started_at' => $started,
                    'delivered_at' => $delivered,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }
    }
}