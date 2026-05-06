<script setup>
const props = defineProps({
    totalPedidos: Number,
    porEstado: Array,
    pedidos: Array,
    desde: String,
    hasta: String,
});
 
const filtrar = () => {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    window.location.href = '/reports/pedidos?desde=' + desde + '&hasta=' + hasta;
};
 
const estadoLabel = (status) => {
    const labels = { en_proceso: 'En Proceso', entregado: 'Entregado', cancelado: 'Cancelado' };
    return labels[status] || status;
};
 
const estadoColor = (status) => {
    const colors = {
        en_proceso: 'bg-yellow-100 text-yellow-700',
        entregado: 'bg-green-100 text-green-700',
        cancelado: 'bg-red-100 text-red-700',
    };
    return colors[status] || 'bg-gray-100 text-gray-700';
};
</script>
 
<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Pedidos</h1>
            <a href="/reports" class="text-gray-500 text-sm">Volver a reportes</a>
        </div>
        <div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" id="desde" :value="desde" class="border border-gray-300 rounded-lg px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" id="hasta" :value="hasta" class="border border-gray-300 rounded-lg px-3 py-2" />
            </div>
            <button @click="filtrar" class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold">Filtrar</button>
        </div>
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <p class="text-gray-500 text-sm">Total Pedidos en el periodo</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ totalPedidos }}</p>
        </div>
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Pedidos por Estado</h2>
            <div class="flex gap-4">
                <div v-for="estado in porEstado" :key="estado.status" class="flex-1 text-center p-4 rounded-lg bg-gray-50">
                    <p class="text-2xl font-bold text-gray-800">{{ estado.total }}</p>
                    <span :class="estadoColor(estado.status)" class="px-2 py-1 rounded-full text-xs mt-1 inline-block">{{ estadoLabel(estado.status) }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Detalle de Pedidos</h2>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3">Fecha</th>
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
                    </tr>
                    <tr v-if="pedidos.length === 0">
                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay pedidos en este periodo</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
