<script setup>
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx';

const props = defineProps({
    productos: Array,
    disponibles: Number,
    agotados: Number,
});

const descargarPDF = () => {
    const doc = new jsPDF();
    doc.setFontSize(18);
    doc.text('Reporte de Inventario - Mi Chuzito', 14, 20);
    doc.setFontSize(11);
    doc.text('Fecha: ' + new Date().toLocaleDateString('es-CO'), 14, 30);
    doc.text('Disponibles: ' + props.disponibles, 14, 38);
    doc.text('Agotados: ' + props.agotados, 14, 46);

    autoTable(doc, {
        startY: 55,
        head: [['Producto', 'Categoria', 'Precio', 'Estado']],
        body: props.productos.map(p => [p.name, p.category, '$' + p.price, p.active ? 'Disponible' : 'Agotado']),
    });

    doc.save('reporte-inventario.pdf');
};

const descargarExcel = () => {
    const data = props.productos.map(p => ({
        Producto: p.name,
        Categoria: p.category,
        Precio: p.price,
        Estado: p.active ? 'Disponible' : 'Agotado',
    }));

    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.json_to_sheet(data);
    XLSX.utils.book_append_sheet(wb, ws, 'Inventario');
    XLSX.writeFile(wb, 'reporte-inventario.xlsx');
};
</script>

<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Reporte de Inventario</h1>
            <a href="/reports" class="text-gray-500 text-sm">Volver a reportes</a>
        </div>

        <div class="flex gap-3 mb-6">
            <button @click="descargarPDF" class="bg-red-500 text-white px-4 py-2 rounded-lg font-semibold">Descargar PDF</button>
            <button @click="descargarExcel" class="bg-emerald-600 text-white px-4 py-2 rounded-lg font-semibold">Descargar Excel</button>
        </div>

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

        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="text-lg font-bold text-gray-800">Estado del Inventario</h2>
            </div>
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="px-6 py-3">Producto</th>
                        <th class="px-6 py-3">Categoria</th>
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
