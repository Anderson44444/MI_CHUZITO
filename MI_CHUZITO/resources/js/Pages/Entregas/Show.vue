 <script setup>
const props = defineProps({
    pedido: Object,
    entrega: Object,
});
 
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
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Detalle de Entrega</h1>
            <a href="/entregas" class="text-gray-500 text-sm">Volver a entregas</a>
        </div>
 
        <div class="bg-white rounded-xl shadow p-6 mb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informacion del Pedido</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Numero de Pedido</p>
                    <p class="font-bold text-gray-800"># {{ pedido.id }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Cliente</p>
                    <p class="font-bold text-gray-800">{{ pedido.customer_name }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total</p>
                    <p class="font-bold text-gray-800">${{ pedido.total }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Estado</p>
                    <span :class="estadoColor(pedido.status)" class="px-2 py-1 rounded-full text-xs font-semibold">{{ estadoLabel(pedido.status) }}</span>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Fecha del Pedido</p>
                    <p class="font-bold text-gray-800">{{ pedido.created_at }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Ultima Actualizacion</p>
                    <p class="font-bold text-gray-800">{{ pedido.updated_at }}</p>
                </div>
            </div>
        </div>
 
        <div v-if="entrega" class="bg-white rounded-xl shadow p-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4">Informacion de Entrega</h2>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Repartidor</p>
                    <p class="font-bold text-gray-800">{{ entrega.delivery_person }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Inicio de Entrega</p>
                    <p class="font-bold text-gray-800">{{ entrega.started_at }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Hora de Entrega</p>
                    <p class="font-bold text-gray-800">{{ entrega.delivered_at ?? 'Pendiente' }}</p>
                </div>
            </div>
            <div v-if="pedido.status === 'entregado'" class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                <p class="text-green-700 font-semibold text-center">Entrega confirmada exitosamente</p>
                <p class="text-green-600 text-sm text-center mt-1">Hora de entrega: {{ entrega.delivered_at }}</p>
            </div>
        </div>
 
        <div v-else class="bg-white rounded-xl shadow p-6">
            <p class="text-center text-gray-400">No hay informacion de entrega aun para este pedido.</p>
        </div>
    </div>
</div>
</template>
