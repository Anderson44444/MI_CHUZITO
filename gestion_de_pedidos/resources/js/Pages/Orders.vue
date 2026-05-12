<script setup>
import axios from "axios";
import { ref } from "vue";

const props = defineProps({
    orders: Array,
});

const orders = props.orders;

const notificacion = ref("");

const cambiarEstado = async (id, estado) => {
    try {
        await axios.put(`/orders/${id}/status`, {
            status: estado,
        });

        const pedido = orders.find((order) => order.id === id);

        if (pedido) {
            pedido.status = estado;
        }

        if (estado === "Entregado") {
            notificacion.value = "✅ Pedido entregado";
        }

        if (estado === "Cancelado") {
            notificacion.value = "❌ Pedido cancelado";
        }

        setTimeout(() => {
            notificacion.value = "";
        }, 3000);
    } catch (error) {
        alert("No se puede modificar");
    }
};

const formatear = (precio) => {
    return Number(precio).toLocaleString();
};
</script>

<template>
    <div class="contenedor">
        <div v-if="notificacion" class="notificacion">
            {{ notificacion }}
        </div>
        <h1 class="titulo">📋 Historial de Pedidos</h1>

        <div class="pedido-card" v-for="order in orders" :key="order.id">
            <div class="top">
                <h2>Pedido #{{ order.id }}</h2>

                <span
                    class="estado"
                    :class="{
                        proceso: order.status === 'En proceso',
                        entregado: order.status === 'Entregado',
                        cancelado: order.status === 'Cancelado',
                    }"
                >
                    {{ order.status }}
                </span>
                <div v-if="order.status === 'En proceso'" class="botones">
                    <button
                        class="entregar"
                        @click="cambiarEstado(order.id, 'Entregado')"
                    >
                        Entregar
                    </button>

                    <button
                        class="cancelar"
                        @click="cambiarEstado(order.id, 'Cancelado')"
                    >
                        Cancelar
                    </button>
                </div>
            </div>

            <div class="info">
                <p>
                    👤 <strong>Cliente:</strong>
                    {{ order.customer_name }}
                </p>

                <p>
                    📍 <strong>Dirección:</strong>
                    {{ order.address }}
                </p>

                <p>
                    📞 <strong>Teléfono:</strong>
                    {{ order.phone }}
                </p>
            </div>

            <div class="productos">
                <h3>🍔 Productos</h3>

                <div
                    class="producto"
                    v-for="item in order.items"
                    :key="item.id"
                >
                    <span>
                        {{ item.product.name }}
                    </span>

                    <strong> x{{ item.quantity }} </strong>
                    <p class="detalle-extra">
                        <strong>Salsas:</strong>

                        {{ item.salsas || "Ninguna" }}
                    </p>

                    <p class="detalle-extra">
                        <strong>Acompañamientos:</strong>

                        {{ item.acompanamientos || "Ninguno" }}
                    </p>
                </div>
            </div>

            <div class="total">Total: ${{ formatear(order.total) }}</div>
        </div>
    </div>
</template>

<style scoped>
import axios from "axios" body {
    background: #f5f7fb;
}

.contenedor {
    max-width: 1000px;
    margin: auto;
    padding: 40px 20px;
}

.titulo {
    text-align: center;
    font-size: 40px;
    margin-bottom: 40px;
    color: #ff6b35;
}

.pedido-card {
    background: white;
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    transition: 0.3s;
}

.pedido-card:hover {
    transform: translateY(-5px);
}

.top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.top h2 {
    color: #333;
}

.estado {
    padding: 8px 16px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: bold;
}

.proceso {
    background: #fff3cd;
    color: #856404;
}

.entregado {
    background: #d4edda;
    color: #155724;
}

.cancelado {
    background: #f8d7da;
    color: #721c24;
}

.info {
    margin-bottom: 20px;
    line-height: 1.8;
}

.productos {
    background: #fafafa;
    border-radius: 15px;
    padding: 15px;
}

.productos h3 {
    margin-bottom: 15px;
    color: #ff6b35;
}

.producto {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.total {
    text-align: right;
    margin-top: 20px;
    font-size: 22px;
    font-weight: bold;
    color: #ff6b35;
}

.botones {
    margin-top: 15px;
    display: flex;
    gap: 10px;
}

.entregar {
    background: #22c55e;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 10px;
    cursor: pointer;
}

.cancelar {
    background: #ef4444;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 10px;
    cursor: pointer;
}
.detalle-extra {
    font-size: 14px;
    color: #666;
    margin-top: 5px;
}
.notificacion {
    background: #fff3cd;
    color: #856404;
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: bold;
    text-align: center;
}
.notificacion {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #28a745;
    color: white;
    padding: 15px 25px;
    border-radius: 12px;
    font-weight: bold;
    z-index: 9999;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    animation: aparecer 0.3s ease;
}

@keyframes aparecer {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
