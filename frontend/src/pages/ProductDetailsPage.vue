<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { ProductService } from '@/services/product.service';
import { useCartStore } from '@/stores/cart.store';
import type { Product } from '@/types/product';
import { storageUrl } from '@/utils/images';

import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

const route = useRoute();

const cartStore = useCartStore();
const product = ref<Product|null>(null);
const loading = ref(true);
const error = ref<string|null>(null);

onMounted(async ()=>{
  try{
    const response = await ProductService.getProduct(route.params.slug as string);
    product.value = response.data;
  }catch{
    error.value="product could not be loaded.";
  }finally{
    loading.value=false
  }
});

</script>

<template>
  <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <Breadcrumbs :current-label="product?.name" />
    <!-- main grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- gallery -->
      <div class="bg-slate-100 rounded-2xl overflow-hidden aspect-square border border-amber-500">
        <img v-if="product?.thumbnail" :src="storageUrl(product.thumbnail)" :alt="product.name"
          class="w-full h-full object-contain">
        <p v-else>No image available</p>
      </div>
      <!-- product info -->
      <div class="border border-red-400 ">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 ">{{product?.brand}}</h1>
        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 ">{{product?.name}}</h2>
        <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-2">$ {{product?.price}}</p>
        <p v-if="product?.description" v-html="product.description"></p>

        <div v-for="attribute in product?.attributes ?? []" :key="attribute.id"
             class="block p-1">
          <p type="button"
          >{{attribute.name}}</p>

          <div class="flex flex-wrap gap-2">
            <button type="button" v-for="value in attribute.values" :key="value.id"
            class="border border-gray-600 px-2 py-1 bg-gray-300 text-slate-900 font-medium
            hover:bg-amber-600 hover:text-shadow-white hover:text-white rounded-xs shadow-2xl
            transition duration-150">{{value.value}}</button>
          </div>
        </div>
    </div>
    </div>
    <!-- recomended products -->
    <div></div>
  </div>
</template>
