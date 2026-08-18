// stores/order.store.ts
import { defineStore } from 'pinia';

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

export const useOrderStore = defineStore('order', {
    state: () => ({
        lastOrder: null as Order | null,
    }),
    actions: {
        setLastOrder(order: Order) {
            this.lastOrder = order;
        },
        clearLastOrder() {
            this.lastOrder = null;
        },
    },
});
