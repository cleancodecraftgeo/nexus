<script setup lang="ts">
import { onMounted } from 'vue';
import { useProductStore } from '../stores/product.store';
import ProductCard from '@/components/ProductCard.vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';



const productStore = useProductStore();

  onMounted(async()=>{

    await productStore.fetchProducts();
    // console.log(productStore.products,'bu datalar productStoredan gelir');
  });
</script>


<template>
  <div>
    <h2>Home Page</h2>
    <Breadcrumbs />

    <ProductCard v-for="product in productStore.products" :key="product.id" :product="product" />

  </div>
</template>
