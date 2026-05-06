<script setup>
</script>

<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Reportes - Mi Chuzito</h1>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <a href="/reports/ventas" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-3">💰</div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Ventas</h2>
                <p class="text-gray-500 mt-1">Total vendido, pedidos y productos más vendidos por período.</p>
            </a>
            <a href="/reports/inventario" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-3">📦</div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Inventario</h2>
                <p class="text-gray-500 mt-1">Productos disponibles, agotados y niveles de stock.</p>
            </a>
            <a href="/reports/pedidos" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-3">🧾</div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Pedidos</h2>
                <p class="text-gray-500 mt-1">Total de pedidos procesados por período y estado.</p>
            </a>
            <a href="/reports/tiempos" class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-3">⏱️</div>
                <h2 class="text-xl font-bold text-gray-800">Reporte de Tiempos de Entrega</h2>
                <p class="text-gray-500 mt-1">Tiempo de entrega por pedido en un período determinado.</p>
            </a>
        </div>
    </div>
</div>
</template>
