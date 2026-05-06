<script setup>
const props = defineProps({
    entregas: Array,
    promedioMinutos: Number,
    desde: String,
    hasta: String,
});
 
const filtrar = () => {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    window.location.href = '/reports/tiempos?desde=' + desde + '&hasta=' + hasta;
};
</script>
 
<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Tiempos de Entrega</h1>
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
            <p class="text-gray-500 text-sm">Promedio de Tiempo de Entrega</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ promedioMinutos }} minutos</p>
        </div>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Detalle de Entregas</h2>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3">Pedido</th>
                        <th class="px-6 py-3">Cliente</th>
                        <th class="px-6 py-3">Repartidor</th>
                        <th class="px-6 py-3">Inicio</th>
                        <th class="px-6 py-3">Entrega</th>
                        <th class="px-6 py-3">Tiempo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="entrega in entregas" :key="entrega.id" class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-600">{{ entrega.id }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ entrega.customer_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ entrega.delivery_person }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ entrega.started_at }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ entrega.delivered_at }}</td>
                        <td class="px-6 py-4">
                            <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">{{ entrega.minutos }} min</span>
                        </td>
                    </tr>
                    <tr v-if="entregas.length === 0">
                        <td colspan="6" class="px-6 py-8 text-center text-gray-400">No hay entregas en este periodo</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
