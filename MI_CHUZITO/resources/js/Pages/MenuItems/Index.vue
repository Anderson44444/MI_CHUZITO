<script setup>
import { router } from '@inertiajs/vue3';

const props = defineProps({ items: Array });

const destroy = (id) => {
    if (confirm('Eliminar este item?')) {
        router.delete('/menu-items/' + id);
    }
};

const editUrl = (id) => '/menu-items/' + id + '/edit';
</script>

<template>
    <div class="min-h-screen bg-gray-100 py-8 px-4">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Menu - Mi Chuzito</h1>
                <a href="/menu-items/create"
                   class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg">
                    + Agregar item
                </a>
            </div>
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-800 text-white">
                        <tr>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Categoria</th>
                            <th class="px-6 py-3">Precio</th>
                            <th class="px-6 py-3">Activo</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in items" :key="item.id" class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-800">{{ item.name }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ item.category }}</td>
                            <td class="px-6 py-4 text-gray-600">${{ item.price }}</td>
                            <td class="px-6 py-4">
                                <span v-if="item.active" class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Activo</span>
                                <span v-else class="bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs">Inactivo</span>
                            </td>
                            <td class="px-6 py-4 space-x-2">
                                <a :href="editUrl(item.id)" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-lg text-xs">Editar</a>
                                <button @click="destroy(item.id)" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs">Eliminar</button>
                            </td>
                        </tr>
                        <tr v-if="items.length === 0">
                            <td colspan="5" class="px-6 py-8 text-center text-gray-400">No hay items registrados</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>