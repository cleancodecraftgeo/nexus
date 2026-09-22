<script setup lang="ts">
import { RouterView } from 'vue-router';
import { onMounted } from 'vue'
import './assets/main.css'
import AppNavbar from './components/AppNavbar.vue';
import { useAuthStore } from './stores/auth.store.ts';

const authStore = useAuthStore()

onMounted(async ()=>{

 await authStore.fetchUser()
})
</script>



<template>


  <AppNavbar class=" sticky top-0 z-50"/>
  <vue3-snackbar top  right :duration="4000" />
  <main>
  <RouterView />
</main>
</template>

