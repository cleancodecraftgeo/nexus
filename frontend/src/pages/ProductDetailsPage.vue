<script setup lang="ts">
import { onMounted, ref } from "vue";
import { useRoute } from "vue-router";
import { ProductService } from "../services/product.service";
import type { Product } from "@/types/product";
import Breadcrumbs from "@/components/Breadcrumbs.vue";
import { useCartStore } from "@/stores/cart.store";


const route = useRoute();

const cartStore = useCartStore();
const product = ref<Product | null>(null);
const loading = ref(true);
const error = ref<string | null>(null);

onMounted(async () => {
    try {
        const response = await ProductService.getProduct(
            route.params.slug as string
        );

        product.value = response.data;
    } catch{
        error.value = "Product could not be loaded.";
    } finally {
        loading.value = false;
    }
});

</script>




<template>
  <h2> Product details Page</h2>
  <Breadcrumbs :current-label="product?.name" />
  <div v-if="loading">
    Loading...
  </div>

  <div v-else-if="error">
    {{ error }}
  </div>

  <div v-else-if="product">

    <h1>{{ product.name }}</h1>

    <p>${{ product.price }}</p>

    <button type="button"
      v-if="product" @click="cartStore.addToCart(product)"
    >add to cart</button>
    <br>
     <!-- ✅ Reaktiv göstər -->
  <p>Səbətdəki say: {{ cartStore.cartCount }}</p>
  <br>
  <p>Items: {{ cartStore.items }}</p>
  </div>
</template>
