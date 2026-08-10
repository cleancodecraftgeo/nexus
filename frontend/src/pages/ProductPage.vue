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
  <div
    v-for="product in productStore.products" :key="product.id">
        <ProductCard :product="product"/>

</div>
</template>
