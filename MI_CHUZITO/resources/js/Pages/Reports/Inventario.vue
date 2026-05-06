 <script setup>
const props = defineProps({
    productos: Array,
    disponibles: Number,
    agotados: Number,
});
</script>

<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">📦 Reporte de Inventario</h1>
            <a href="/reports" class="text-gray-500 text-sm">Volver a reportes</a>
        </div>

        <!-- Tarjetas resumen -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Productos Disponibles</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ disponibles }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Productos Agotados</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ agotados }}</p>
            </div>
        </div>

        <!-- Tabla de productos -->
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Estado del Inventario</h2>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Categoría</th>
                        <th class="px-6 py-3">Precio</th>
                        <th class="px-6 py-3">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="producto in productos" :key="producto.name" class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ producto.name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ producto.category }}</td>
                        <td class="px-6 py-4 text-gray-600">${{ producto.price }}</td>
                        <td class="px-6 py-4">
                            <span v-if="producto.active" class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Disponible</span>
                            <span v-else class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Agotado</span>
                        </td>
                    </tr>
                    <tr v-if="productos.length === 0">
                        <td colspan="4" class="px-6 py-8 text-center text-gray-400">No hay productos registrados</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
