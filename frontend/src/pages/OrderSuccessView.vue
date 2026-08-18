<script setup lang="ts">
import { useOrderStore } from "@/stores/order.store";
import { computed } from "vue";
import { RouterLink } from "vue-router";
const orderStore = useOrderStore();

console.log('order success sehifesi');
const order = computed(() => orderStore.lastOrder);
</script>

<template>
    <div class="flex min-h-screen items-center justify-center bg-slate-50 px-6">

        <div
            v-if="order"
            class="w-full max-w-lg rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm"
        >
            <div class="text-5xl">
                ✅
            </div>

            <h1 class="mt-5 text-3xl font-bold text-slate-900">
                Order placed successfully!
            </h1>

            <p class="mt-3 text-slate-600">
                Thank you for your order.
            </p>

            <div class="mt-6 rounded-xl bg-slate-50 p-4 text-left">
                <div class="flex justify-between">
                    <span class="text-slate-500">Order ID</span>
                    <span class="font-medium text-slate-900">
                        {{ order.id }}
                    </span>
                </div>

                <div class="mt-3 flex justify-between">
                    <span class="text-slate-500">Total</span>
                    <span class="font-bold text-slate-900">
                        ${{ order.total }}
                    </span>
                </div>
            </div>

            <RouterLink
                to="/products"
                class="mt-8 inline-block rounded-xl bg-slate-950 px-6 py-3 text-sm font-semibold text-white transition hover:bg-slate-800"
            >
                Continue Shopping
            </RouterLink>
        </div>

        <div
            v-else
            class="text-center"
        >
            <h1 class="text-2xl font-bold text-slate-900">
                No recent order found
            </h1>

            <p class="mt-3 text-slate-600">
                We couldn't find a recent order.
            </p>

            <RouterLink
                to="/products"
                class="mt-6 inline-block text-slate-900 underline"
            >
                Back to shop
            </RouterLink>
        </div>

    </div>
</template>
