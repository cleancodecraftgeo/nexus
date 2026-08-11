<script setup lang="ts">
import { ref } from "vue";
import { useCartStore } from "@/stores/cart.store";
import { createOrder } from "@/services/order.service";

const cartStore = useCartStore();

const order = ref<Order|null>(null);
const orderError = ref<string | null>(null);
const loading = ref(false);

const placeOrder = async () => {
    loading.value = true;
    orderError.value = null;

    try {
        const payload = {
            items: cartStore.items.map((item) => ({
                product_id: item.product.id,
                quantity: item.quantity,
            })),
        };

        const response = await createOrder(payload);

        console.log("Created order:", response);

        order.value = response.order;

        // Order uğurla yaradıldıqdan sonra cart təmizlənir
        cartStore.items = [];
    } catch (error) {
        console.error(error);

        orderError.value = "Order could not be created.";
    } finally {
        loading.value = false;
    }
};

interface OrderItem {
    id: string;
    order_id: string;
    product_id: string;
    quantity: number;
    price: string;
}

interface Order {
    id: string;
    total: number;
    status: string;
    items: OrderItem[];
}
</script>

<template>
    <div>
        <!-- SUCCESS -->
        <div v-if="order">
            <h1>Order Created Successfully! 🎉</h1>

            <p>
                Order ID: {{ order.id }}
            </p>

            <p>
                Status: {{ order.status }}
            </p>

            <p>
                Total:
                ${{ Number(order.total).toFixed(2) }}
            </p>
        </div>

        <!-- CHECKOUT -->
        <div v-else>
            <h1>Checkout</h1>

            <div
                v-for="item in cartStore.items"
                :key="item.product.id"
            >
                <p>
                    {{ item.product.name }} × {{ item.quantity }}
                </p>

                <p>
                    ${{ (
                        Number(item.product.price) * item.quantity
                    ).toFixed(2) }}
                </p>

                <hr />
            </div>

            <p>
                Total items:
                {{ cartStore.cartCount }}
            </p>

            <p>
                Total:
                ${{ cartStore.cartTotal.toFixed(2) }}
            </p>

            <br />

            <button type="button"
                @click="placeOrder"
                :disabled="loading || cartStore.items.length === 0"
            >
                {{ loading ? "Creating Order..." : "Place Order" }}
            </button>

            <p v-if="orderError">
                {{ orderError }}
            </p>
        </div>
    </div>
</template>
