<script setup>
import { useForm } from '@inertiajs/vue3';
const props = defineProps({ item: Object });
const form = useForm({
    name: props.item.name,
    description: props.item.description,
    price: props.item.price,
    category: props.item.category,
    active: Boolean(props.item.active),
});
const submit = () => form.put('/menu-items/' + props.item.id);
</script>

<template>
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Editar item</h1>
        <a href="/menu-items" class="text-gray-500 text-sm">Volver al listado</a>
        <div class="bg-white rounded-xl shadow p-6 mt-4">
            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input v-model="form.name" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                    <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripcion</label>
                    <textarea v-model="form.description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                    <input v-model="form.price" type="number" step="0.01" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                    <span v-if="form.errors.price" class="text-red-500 text-xs">{{ form.errors.price }}</span>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                    <input v-model="form.category" type="text" class="w-full border border-gray-300 rounded-lg px-3 py-2" />
                    <span v-if="form.errors.category" class="text-red-500 text-xs">{{ form.errors.category }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" v-model="form.active" id="active" class="w-4 h-4" />
                    <label for="active" class="text-sm text-gray-700">Activo</label>
                </div>
                <button type="submit" :disabled="form.processing" class="w-full bg-blue-500 text-white font-semibold py-2 rounded-lg">
                    Actualizar item
                </button>
            </form>
        </div>
    </div>
</div>
</template>