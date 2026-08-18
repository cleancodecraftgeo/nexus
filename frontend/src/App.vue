<script setup lang="ts">
import { RouterView } from 'vue-router';

import './assets/main.css'
import AppNavbar from './components/AppNavbar.vue';
</script>


<template>
  <AppNavbar class=" sticky top-0 z-50"/>
<main>
  <RouterView />
</main>
</template>
