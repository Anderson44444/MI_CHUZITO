<script setup>
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

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

const descargarPDF = () => {
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text('Reporte de Tiempos de Entrega - Mi Chuzito', 14, 20);
    doc.setFontSize(11);
    doc.text('Periodo: ' + props.desde + ' al ' + props.hasta, 14, 30);
    doc.text('Promedio de entrega: ' + props.promedioMinutos + ' minutos', 14, 38);

    autoTable(doc, {
        startY: 47,
        head: [['Pedido', 'Cliente', 'Repartidor', 'Inicio', 'Entrega', 'Minutos']],
        body: props.entregas.map(e => [e.id, e.customer_name, e.delivery_person, e.started_at, e.delivered_at, e.minutos + ' min']),
    });

    doc.save('reporte-tiempos-' + props.desde + '-' + props.hasta + '.pdf');
};

const descargarExcel = () => {
    const data = props.entregas.map(e => ({
        'Pedido': e.id,
        'Cliente': e.customer_name,
        'Repartidor': e.delivery_person,
        'Inicio': e.started_at,
        'Entrega': e.delivered_at,
        'Minutos': e.minutos,
    }));

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(data);
    XLSX.utils.book_append_sheet(wb, ws, 'Tiempos');
    XLSX.writeFile(wb, 'reporte-tiempos-' + props.desde + '-' + props.hasta + '.xlsx');
};
</script>

<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Tiempos de Entrega</h1>
            <a href="/reports" class="text-gray-500 text-sm">Volver a reportes</a>
        </div>
        <div class="bg-white rounded-xl shadow p-4 mb-6 flex gap-4 items-end flex-wrap">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Desde</label>
                <input type="date" id="desde" :value="desde" class="border border-gray-300 rounded-lg px-3 py-2" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Hasta</label>
                <input type="date" id="hasta" :value="hasta" class="border border-gray-300 rounded-lg px-3 py-2" />
            </div>
            <button @click="filtrar" class="bg-green-500 text-white px-4 py-2 rounded-lg font-semibold">Filtrar</button>
            <button @click="descargarPDF" class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
            <button @click="descargarExcel" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold">Descargar Excel</button>
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
