<script setup lang="ts">



import Breadcrumbs from '@/components/Breadcrumbs.vue';
import ProductCard from '@/components/ProductCard.vue';
import { useProductStore } from '@/stores/product.store';
import { onMounted } from 'vue';


const productStore = useProductStore();

onMounted(async ()=>{
    await productStore.fetchProducts();
});
</script>



<template>
  <h2>Products Page</h2>
  <Breadcrumbs />


  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mt-2">
    <ProductCard
      v-for="product in productStore.products"
      :key="product.id"
      :product="product"
    />
  </div>
</template>
