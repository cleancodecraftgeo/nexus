<script setup lang="ts">
import { RouterLink } from 'vue-router';
import type {Product} from '../types/product'
import { storageUrl } from '@/utils/images';
import { useCartStore } from '@/stores/cart.store';
import { computed } from 'vue';
const props = defineProps<{product:Product}>();
const cartStore = useCartStore();

const isInCart = computed(() =>
  cartStore.items.some(item => item.product.id === props.product.id)
);
</script>

<template>

  <div
    class="group overflow-hidden rounded-2xl border border-slate-200 bg-amber-50 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
    <div class="aspect-square overflow-hidden bg-slate-100">

      <RouterLink :to="`/products/${props.product.slug}`">
        <img :src="storageUrl(props.product.thumbnail)" :alt="props.product.name"
        class="h-full w-full object-contain transition duration-500 group-hover:scale-105" />
      </RouterLink>
    </div>

    <div class="p-2">

      <div  class="block ">


        <h2 class="truncate text-lg font-semibold text-slate-900 transition hover:text-slate-600">
  {{ props.product.name }}
</h2>
      </div>

     <div class="mt-3 flex items-center justify-between">
    <p class="text-lg font-bold text-slate-900">${{ props.product.price }}</p>
    <button type="button" :disabled="isInCart" @click.stop="cartStore.addToCart(props.product)"
      class="rounded-lg cursor-pointer px-4 py-2 text-sm font-medium text-white transition"
      :class="isInCart ? 'bg-gray-600 hover:bg-gray-900' : 'bg-red-600 hover:bg-red-800'"
      >
      {{ isInCart ? "Added" : "Add to cart" }}
    </button>
  </div>

    </div>

  </div>

</template>
