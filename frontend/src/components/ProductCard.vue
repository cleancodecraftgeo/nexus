<script setup lang="ts">
import type {Product} from '../types/product'
const props = defineProps<{product:Product}>()
</script>

<template>
  <div>
    <h2>{{props.product.name}}</h2>
    <p>
      ${{ props.product.price }}
    </p>
  </div>
</template>
