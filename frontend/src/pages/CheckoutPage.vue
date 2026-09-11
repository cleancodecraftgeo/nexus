<script setup lang="ts">
import { useCartStore } from "@/stores/cart.store";
import { useOrderStore } from "@/stores/order.store";
import { createOrder } from "@/services/order.service";
import { storageUrl } from "@/utils/images";
import { useRouter } from "vue-router";
import { ref } from "vue";
import axios from "axios";
const cartStore = useCartStore();
const orderStore = useOrderStore();

const orderError = ref<string | null>(null);
const loading = ref(false);
const router = useRouter();

const placeOrder = async () => {
    loading.value = true;
    orderError.value = null;

    try {
        const payload = {
            items: cartStore.items.map((item) => ({
                product_id: item.product.id,
                variant_id: item.variant.id,
                quantity: item.quantity,

            })),

        };
console.log("ORDER PAYLOAD:", payload);
            const response = await createOrder(payload);

    console.log("Order Created:", response);

    orderStore.setLastOrder(response.order);

    cartStore.items = [];

    router.push({
        name: "order-success",
        params: {
            id: response.order.id,
        },
    });

    } catch (error) {
        // console.error("Full error ",error);

        // orderError.value = "Order could not be created.";
        if (axios.isAxiosError(error)) {
    orderError.value =
      error.response?.data?.message ?? "Order could not be created.";
  } else {
    orderError.value = "Order could not be created.";
  }
    } finally {
        loading.value = false;

    }
};


</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-6 py-10">



      <!-- Header -->
      <div class="mb-10">
        <RouterLink to="/cart" class="text-sm text-slate-500 transition hover:text-slate-900">
          ← Back to cart
        </RouterLink>

        <h1 class="mt-4 text-3xl font-bold text-slate-900">
          Checkout
        </h1>
      </div>

      <!-- Checkout layout -->
      <div class="grid gap-10 lg:grid-cols-[1fr_420px]">

        <!-- LEFT SIDE -->
        <div class="space-y-8">

          <!-- Contact -->
          <section class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-900">
              Contact information
            </h2>

            <div class="mt-5">
              <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                Email
              </label>

              <input id="email" type="email" placeholder="you@example.com"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
            </div>
          </section>

          <!-- Shipping -->
          <section class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-900">
              Shipping information
            </h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">

              <div class="sm:col-span-2">
                <label aria-label="Full name" class="mb-2 block text-sm font-medium text-slate-700">
                  Full name
                </label>

                <input type="text" placeholder="John Doe"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                  City
                </label>

                <input type="text" placeholder="Tbilisi"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>

              <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">
                  Country
                </label>

                <input type="text" placeholder="Georgia"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>

              <div class="sm:col-span-2">
                <label class="mb-2 block text-sm font-medium text-slate-700">
                  Address
                </label>

                <input type="text" placeholder="Street and house number"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>

            </div>
          </section>

          <!-- Payment -->
          <section class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-900">
              Payment
            </h2>

            <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm font-medium text-slate-900">
                Payment method
              </p>

              <p class="mt-1 text-sm text-slate-500">
                Payment integration will be added soon.
              </p>
            </div>
          </section>

        </div>

        <!-- RIGHT SIDE -->
        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 lg:sticky lg:top-6">
          <h2 class="text-xl font-semibold text-slate-900">
            Your Order
          </h2>

          <!-- Products -->
          <div class="mt-6 space-y-5">

            <div v-for="item in cartStore.items" :key="item.product.id" class="flex gap-4">
              <div class="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                <img v-if="item.product.thumbnail" :src="storageUrl(item.product.thumbnail)" :alt="item.product.name"
                  class="h-full w-full object-contain" />

                <span
                  class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-slate-900 px-1 text-xs font-bold text-white">
                  {{ item.quantity }}
                </span>
              </div>

              <div class="min-w-0 flex-1">
                <h3 class="truncate text-sm font-medium text-slate-900">
                  {{ item.product.name }}
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                  ${{ item.product.price }}
                </p>
              </div>

              <p class="text-sm font-medium text-slate-900">
                ${{ Number(item.product.price) * item.quantity }}
              </p>
            </div>

          </div>

          <!-- Summary -->
          <div class="mt-6 border-t border-slate-200 pt-6">

            <div class="flex justify-between text-sm text-slate-600">
              <span>Items</span>
              <span>{{ cartStore.cartCount }}</span>
            </div>

            <div class="mt-3 flex justify-between text-sm text-slate-600">
              <span>Subtotal</span>
              <span>
                ${{ cartStore.cartTotal }}
              </span>
            </div>

            <div class="mt-4 flex justify-between border-t border-slate-200 pt-4 text-lg font-bold text-slate-900">
              <span>Total</span>

              <span>
                ${{ cartStore.cartTotal }}
              </span>
            </div>

          </div>

          <!-- Place order -->
          <button type="button" @click="placeOrder" :disabled="loading || cartStore.items.length === 0"
            class="mt-6 w-full rounded-xl bg-slate-950 px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50">
            {{ loading ? "Creating Order..." : "Place Order" }}
          </button>

          <p v-if="orderError" class="mt-3 text-sm text-red-600">
            {{ orderError }}
          </p>

        </aside>

      </div>
    </div>
  </div>
</template>
