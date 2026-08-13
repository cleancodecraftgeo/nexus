<script setup lang="ts">
import { RouterLink } from 'vue-router';
import type {Product} from '../types/product'
import { storageUrl } from '@/utils/images';
const props = defineProps<{product:Product}>();

console.log(props.product.thumbnail);
</script>

<template>

    <article
    class="group overflow-hidden rounded-2xl border border-slate-200 bg-amber-50 transition duration-300 hover:-translate-y-1 hover:shadow-xl"
    >
      <div class="aspect-square overflow-hidden bg-slate-100">
        <img :src="storageUrl(props.product.thumbnail)"
            :alt="props.product.name"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"/>
      </div>

      <div class="p-2">

        <RouterLink :to="`/products/${props.product.slug}`"
        class="block "
        >
        <h2 class="truncate text-lg font-semibold text-slate-900 transition hover:text-slate-600"
        >{{props.product.name}}</h2>
      </RouterLink>

      <div class="mt-3 flex items-center justify-between">
        <p class="text-lg font-bold text-slate-900">${{props.product.price}}</p>
        <button type="button"
        class="rounded-lg bg-slate-950 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-800">
            Add to cart
      </button>
      </div>

      </div>
<p v-if="props.product.thumbnail">
    {{ props.product.thumbnail }}
</p>
    </article>

</template>
