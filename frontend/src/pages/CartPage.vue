<script setup lang="ts">
import { useCartStore } from "@/stores/cart.store";

import { storageUrl } from "@/utils/images";
import { RouterLink } from "vue-router";

const cartStore = useCartStore();
</script>

<template>
  <div class="mx-auto max-w-5xl px-6 py-10">
    <h1 class="mb-8 text-3xl font-bold text-slate-900">
      Shopping cart
    </h1>

    <!-- Empty cart -->
    <div v-if="cartStore.items.length === 0" class="rounded-xl bg-slate-50 p-10 text-center">
      <h2 class="text-xl font-semibold text-slate-900">
        Your cart is empty
      </h2>

      <RouterLink to="/products"
        class="mt-5 inline-block rounded-lg bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
        Continue Shopping
      </RouterLink>
    </div>

    <!-- Cart with items -->
    <div v-else>
      <!-- Grid: Cart items və Summary yan-yana -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <!-- Cart items (sol tərəf - 2/3 hissə) -->
        <div class="md:col-span-2">
          <div v-for="item in cartStore.items" :key="item.product.id"
            class="md:flex items-center md:justify-center p-3 md:gap-5    py-6">

            <!-- Image -->
            <div class="flex md:h-24 md:w-24 w-4xs shrink-0 overflow-hidden rounded-xl bg-slate-100">
              <img v-if="item.product.thumbnail" :src="storageUrl(item.product.thumbnail)" :alt="item.product.name"
                class="h-full w-full object-contain" />
            </div>

            <!-- Product info -->
            <div class="min-w-0 flex-1  ">
              <RouterLink :to="`/products/${item.product.slug}`"
                class="text-lg sm:text-start text-center font-semibold text-slate-900 transition hover:text-slate-600">
                {{ item.product.name }}
              </RouterLink>

              <p class="mt-1 text-center sm:text-start text-slate-500">
                ${{ item.variant.price }}
              </p>
            </div>

            <div class="min-w-0 flex-1  0 py-2">

              <p class="text-sm text-center sm:text-start font-semibold text-slate-900 transition hover:text-slate-600">
                Total Price {{ Number(item.variant.price) * item.quantity }}
              </p>
<div class="text-sm text-slate-500">
  <span
    v-for="value in item.variant.attributeValues"
    :key="value.id"
    class="flex"
  >
    {{ value.attribute }}: {{ value.value }}
  </span>
</div>
            </div>


            <!-- Quantity -->
            <div class="flex items-center rounded-lg  border-slate-200">
              <button type="button" @click="cartStore.decreaseQuantity(item.variant.id)" :disabled="item.quantity <= 1"
                :class="[
                  'px-3 py-2 transition',
                  item.quantity <= 1
                    ? 'text-slate-300 cursor-not-allowed'
                    : 'text-slate-600 hover:bg-slate-100'
                ]">
                -
              </button>

              <span class="min-w-10 text-center text-sm font-medium">
                {{ item.quantity }}
              </span>

              <button type="button" @click="cartStore.increaseQuantity(item.variant.id)"
                class="px-3 py-2 text-slate-600 transition hover:bg-slate-100">
                +
              </button>
            </div>

            <!-- Remove -->
            <button type="button" @click="cartStore.removeFromCart(item.variant.id)"
              class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
              Remove
            </button>
          </div>
        </div>

        <!-- Order Summary (sağ tərəf - 1/3 hissə) -->
        <div class="md:col-span-1">
          <div class="h-fit rounded-2xl  border-slate-200 shadow-xl bg-gray-200 p-6 md:sticky md:top-4">
            <h2 class="text-xl font-semibold text-slate-900">
              Order Summary
            </h2>

            <div class="mt-6 flex justify-between text-sm text-slate-600">
              <span>Items</span>
              <span>{{ cartStore.cartCount }}</span>
            </div>

            <div class="my-4 border-t border-slate-200"></div>

            <div class="flex justify-between text-lg font-bold text-slate-900">
              <span>Total</span>
              <span>${{ cartStore.cartTotal }}</span>
            </div>

            <RouterLink
              :to="cartStore.items.length > 0 ? '/checkout' : '#'"
              @click.prevent="cartStore.items.length === 0 && $event.preventDefault()"
              :class="[
                'mt-6 block w-full rounded-xl px-5 py-3 text-center text-sm font-semibold transition',
                cartStore.items.length === 0
                  ? 'bg-slate-300 text-slate-500 cursor-not-allowed pointer-events-none'
                  : 'bg-slate-950 text-white hover:bg-slate-800'
              ]">
              Checkout
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
