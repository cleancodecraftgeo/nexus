<script setup lang="ts">
import { onMounted } from 'vue';
import { useProductStore } from '../stores/product.store';
import ProductCard from '@/components/ProductCard.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';



const productStore = useProductStore();

  onMounted(async()=>{

    await productStore.fetchProducts();
    

  });
</script>


<template>
  <div>
    <h2>Home Page</h2>
    <Breadcrumbs />
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 xl:grid-cols-4 gap-6 mt-2">

  <ProductCard v-for="product in productStore.products" :key="product.id" :product="product" />

</div>

  </div>
</template>
