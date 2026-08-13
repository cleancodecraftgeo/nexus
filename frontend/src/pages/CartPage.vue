<script setup lang="ts">
import {useCartStore} from "@/stores/cart.store";

const cartStore = useCartStore();
</script>

<template>

  <div>
    <br>
    <h1>Cart</h1>

    <div
      v-for="item in cartStore.items" :key="item.product.id"
      >


      <h2>{{item.product.name}}</h2>
      <p>{{item.product.price}}</p>
      <p>quantity: {{item.quantity}}</p>

      <button type="button" @click="cartStore.increaseQuantity(item.product.id)">increaseQuantity</button>
      <span>'    '</span>
      <button type="button" @click="cartStore.decreaseQuantity(item.product.id)">decreaseQuantity</button>
      <span>'    '</span>
      <button type="button" @click="cartStore.removeFromCart(item.product.id)">RemoveFromCart</button>
    </div>

   <hr>
   <p>Total Count: {{ cartStore.cartCount }}</p>
   <p>Total Price:{{cartStore.cartTotal}}</p>
    <!-- <button type="button" @click="">Checkout</button> -->
  </div>
</template>
