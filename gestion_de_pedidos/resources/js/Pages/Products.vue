<template>
    <div class="layout">
        <!-- 🛍️ PRODUCTOS -->
        <div class="productos">
            <div class="header">
                <h1 class="titulo">Menú</h1>

                <!-- BOTÓN CARRITO -->
                <button
                    class="btn-carrito"
                    @click="mostrarCarrito = !mostrarCarrito"
                >
                    🛒
                </button>

                <!-- BOTÓN HISTORIAL -->
                <button
                    v-if="!mostrarCarrito"
                    class="btn-historial"
                    @click="mostrarHistorial = !mostrarHistorial"
                >
                    📋
                </button>
            </div>

            <div class="grid">
                <div class="card" v-for="product in products" :key="product.id">
                    <h2>{{ product.name }}</h2>

                    <p class="price">${{ formatear(product.price) }}</p>
                    <div class="extras">
                        <p class="extra-title">🥫 Salsas</p>

                        <label>
                            <input
                                type="checkbox"
                                value="BBQ"
                                v-model="product.salsas"
                            />

                            BBQ
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                value="Ajo"
                                v-model="product.salsas"
                            />

                            Ajo
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                value="Piña"
                                v-model="product.salsas"
                            />

                            Piña
                        </label>

                        <p class="extra-title">🍟 Acompañamientos</p>

                        <label>
                            <input
                                type="checkbox"
                                value="Papas"
                                v-model="product.acompanamientos"
                            />

                            Papas
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                value="Arepa"
                                v-model="product.acompanamientos"
                            />

                            Arepa
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                value="Ensalada"
                                v-model="product.acompanamientos"
                            />

                            Ensalada
                        </label>
                    </div>

                    <button class="agregar" @click="agregar(product)">
                        Agregar
                    </button>
                </div>
            </div>
        </div>

        <!-- 🛒 CARRITO -->

        <div class="carrito" v-if="mostrarCarrito">
            <div class="carrito-header">
                <h2>Tu Pedido</h2>

                <button @click="mostrarCarrito = false">✖</button>
            </div>

            <div v-if="carrito.length === 0">
                <p>No hay pedidos</p>
            </div>

            <div v-else>
                <div class="item" v-for="item in carrito" :key="item.id">
                    <strong>
                        {{ item.name }}
                    </strong>
                    <p>
                        🥫

                        {{ item.salsas.join(", ") || "Sin salsas" }}
                    </p>
                    <p>
                        🍟
                        {{
                            item.acompanamientos.join(", ") ||
                            "Sin acompañamientos"
                        }}
                    </p>

                    <div class="acciones">
                        <button @click="disminuir(item)">-</button>

                        <span>
                            {{ item.cantidad }}
                        </span>

                        <button @click="aumentar(item)">+</button>
                    </div>

                    <p>${{ formatear(item.price * item.cantidad) }}</p>

                    <button class="eliminar" @click="eliminar(item.id)">
                        ✖
                    </button>
                </div>

                <hr />

                <p>Subtotal: ${{ formatear(subtotal) }}</p>

                <p>Domicilio: ${{ formatear(domicilio) }}</p>

                <h3>Total: ${{ formatear(total) }}</h3>

                <input type="text" v-model="nombre" placeholder="Nombre" />

                <input
                    type="text"
                    v-model="direccion"
                    placeholder="Dirección"
                />

                <input type="text" v-model="telefono" placeholder="Teléfono" />

                <button class="pedido" @click="guardarPedido">
                    Continuar con el pedido
                </button>
            </div>
        </div>

        <!-- 📋 PANEL HISTORIAL -->

        <div class="panel-historial" v-if="mostrarHistorial">
            <iframe
                src="/orders-history"
                width="100%"
                height="100%"
                style="border: none"
            ></iframe>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";
import axios from "axios";
const props = defineProps({
    products: Array,
});

props.products.forEach((product) => {
    if (!product.salsas) {
        product.salsas = [];
    }

    if (!product.acompanamientos) {
        product.acompanamientos = [];
    }
});

const carrito = ref([]);

const mostrarCarrito = ref(false);

const mostrarHistorial = ref(false);

const nombre = ref("");

const direccion = ref("");

const telefono = ref("");

const agregar = (product) => {
    const existe = carrito.value.find((p) => p.id === product.id);

    if (existe) {
        existe.cantidad++;
        existe.salsas = [...product.salsas]; // ← línea nueva
        existe.acompanamientos = [...product.acompanamientos]; // ← línea nueva
    } else {
        carrito.value.push({
            ...product,
            cantidad: 1,
            salsas: [...(product.salsas || [])],
            acompanamientos: [...(product.acompanamientos || [])],
        });
    }
};

const aumentar = (item) => item.cantidad++;

const disminuir = (item) => {
    if (item.cantidad > 1) {
        item.cantidad--;
    }
};

const eliminar = (id) => {
    carrito.value = carrito.value.filter((p) => p.id !== id);
};

const subtotal = computed(() =>
    carrito.value.reduce((sum, item) => sum + item.price * item.cantidad, 0),
);

const domicilio = 3000;

const total = computed(() => subtotal.value + domicilio);

const guardarPedido = async () => {
    try {
        await axios.post("/orders", {
            items: carrito.value,

            total: total.value,

            customer_name: nombre.value,

            address: direccion.value,

            phone: telefono.value,
        });

        alert("Pedido guardado 🎉");

        carrito.value = [];
    } catch (error) {
        console.log(error);

        alert("Error al guardar");
    }
};

const formatear = (precio) => Number(precio).toLocaleString();
</script>

<style scoped>
.extras {
    margin-top: 10px;
    text-align: left;
}

.extra-title {
    margin-top: 10px;
    font-weight: bold;
    color: #ff6b35;
}

.extras label {
    display: block;
    margin-top: 5px;
    font-size: 14px;
}
</style>
