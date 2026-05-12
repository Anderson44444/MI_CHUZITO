<script setup>
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

const props = defineProps({
    totalVentas: Number,
    totalPedidos: Number,
    productosMasVendidos: Array,
    desde: String,
    hasta: String,
});

const filtrar = () => {
    const desde = document.getElementById('desde').value;
    const hasta = document.getElementById('hasta').value;
    window.location.href = '/reports/ventas?desde=' + desde + '&hasta=' + hasta;
};

const formatPrice = (value) => {
    return new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 }).format(value);
};

const descargarPDF = () => {
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text('Reporte de Ventas - Mi Chuzito', 14, 20);
    doc.setFontSize(11);
    doc.text('Periodo: ' + props.desde + ' al ' + props.hasta, 14, 30);
    doc.text('Total Vendido: ' + formatPrice(props.totalVentas), 14, 38);
    doc.text('Total Pedidos: ' + props.totalPedidos, 14, 46);

    autoTable(doc, {
        startY: 55,
        head: [['Producto', 'Cantidad Vendida', 'Total Ingresos']],
        body: props.productosMasVendidos.map(p => [p.name, p.total_vendido, formatPrice(p.total_ingresos)]),
    });

    doc.save('reporte-ventas-' + props.desde + '-' + props.hasta + '.pdf');
};

const descargarExcel = () => {
    const data = props.productosMasVendidos.map(p => ({
        Producto: p.name,
        'Cantidad Vendida': p.total_vendido,
        'Total Ingresos': p.total_ingresos,
    }));

    const resumen = [
        { Concepto: 'Total Vendido', Valor: props.totalVentas },
        { Concepto: 'Total Pedidos', Valor: props.totalPedidos },
        { Concepto: 'Periodo', Valor: props.desde + ' al ' + props.hasta },
    ];

    const wb = XLSX.utils.book_new();
    const ws1 = XLSX.utils.json_to_sheet(resumen);
    const ws2 = XLSX.utils.json_to_sheet(data);
    XLSX.utils.book_append_sheet(wb, ws1, 'Resumen');
    XLSX.utils.book_append_sheet(wb, ws2, 'Productos');
    XLSX.writeFile(wb, 'reporte-ventas-' + props.desde + '-' + props.hasta + '.xlsx');
};
</script>

<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Ventas</h1>
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
            <button @click="descargarPDF" class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
            <button @click="descargarExcel" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold">Descargar Excel</button>
        </div>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 mb-6">
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Total Vendido</p>
                <p class="text-3xl font-bold text-green-600 mt-1">{{ formatPrice(totalVentas) }}</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6">
                <p class="text-gray-500 text-sm">Total Pedidos</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ totalPedidos }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Productos mas vendidos</h2>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Cantidad Vendida</th>
                        <th class="px-6 py-3">Total Ingresos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="producto in productosMasVendidos" :key="producto.name" class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ producto.name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ producto.total_vendido }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ formatPrice(producto.total_ingresos) }}</td>
                    </tr>
                    <tr v-if="productosMasVendidos.length === 0">
                        <td colspan="3" class="px-6 py-8 text-center text-gray-400">No hay datos en este periodo</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</template>
