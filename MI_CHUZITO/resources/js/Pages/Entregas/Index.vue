 <script setup>
import { router } from '@inertiajs/vue3';
 
const props = defineProps({
    pedidos: Array,
});
 
const enRuta = (id) => {
    if (confirm('Marcar este pedido como En Ruta?')) {
        router.post('/entregas/' + id + '/en-ruta');
    }
};
 
const confirmar = (id) => {
    if (confirm('Confirmar la entrega de este pedido?')) {
        router.post('/entregas/' + id + '/confirmar');
    }
};
 
const estadoLabel = (status) => {
    const labels = { en_proceso: 'En Proceso', en_ruta: 'En Ruta', entregado: 'Entregado', cancelado: 'Cancelado' };
    return labels[status] || status;
};
 
const estadoColor = (status) => {
    const colors = {
        en_proceso: 'bg-yellow-100 text-yellow-700',
        en_ruta: 'bg-blue-100 text-blue-700',
        entregado: 'bg-green-100 text-green-700',
        cancelado: 'bg-red-100 text-red-700',
    };
    return colors[status] || 'bg-gray-100 text-gray-700';
};
</script>
 
<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestion de Entregas</h1>
            <a href="/menu-items" class="text-gray-500 text-sm">Ir al Menu</a>
        </div>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Pedidos</h2>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Fecha</th>
                        <th class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="pedido in pedidos" :key="pedido.id" class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-600">{{ pedido.id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ pedido.customer_name }}</td>
                        <td class="px-6 py-4 text-gray-600">${{ pedido.total }}</td>
                        <td class="px-6 py-4">
                            <span :class="estadoColor(pedido.status)" class="px-2 py-1 rounded-full text-xs">{{ estadoLabel(pedido.status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ pedido.created_at }}</td>
                        <td class="px-6 py-4 space-x-2">
                            <button v-if="pedido.status === 'en_proceso'" @click="enRuta(pedido.id)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">En Ruta</button>
                            <button v-if="pedido.status === 'en_ruta'" @click="confirmar(pedido.id)" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-lg text-xs">Confirmar Entrega</button>
                            <a :href="'/entregas/' + pedido.id" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded-lg text-xs">Ver Detalle</a>
                        </td>
                    </tr>
                    <tr v-if="pedidos.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No hay pedidos registrados</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
 
