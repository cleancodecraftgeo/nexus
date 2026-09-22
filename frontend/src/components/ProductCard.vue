<script setup lang="ts">
import { RouterLink } from 'vue-router';
import { storageUrl } from '@/utils/images';
import type { Product } from '@/types/Product';

const props = defineProps<{product:Product}>();



</script>

<template>

  <div
    class=" group overflow-hidden rounded-2xl border border-slate-200 bg-amber-50 transition duration-300 hover:-translate-y-1 hover:shadow-xl">
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

     <div class="mt-3 flex items-center justify-between ">
    <p class="text-lg font-bold text-slate-900">${{ props.product.price }}</p>

  </div>

    </div>

  </div>

</template>
