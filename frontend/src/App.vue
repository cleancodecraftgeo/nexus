<script setup lang="ts">
import { RouterView } from 'vue-router';
import { onMounted, watch } from 'vue'
import './assets/main.css'
import AppNavbar from './components/AppNavbar.vue';
import { useAuthStore } from './stores/auth.store.ts';
import { useCurrencyStore } from './stores/currency.store.ts';
import { useCartStore } from './stores/cart.store.ts';

const authStore = useAuthStore()
const currencyStore = useCurrencyStore()
const cartStore = useCartStore()

onMounted(async ()=>{
 await authStore.fetchUser()
})

watch(
  () => currencyStore.currency,
  async () => {
    await cartStore.refreshPrices()
  }
)
</script>



<template>


  <AppNavbar class=" sticky top-0 z-50" />
  <vue3-snackbar top right :duration="4000" />
  <main>
    <RouterView />
  </main>
</template>
