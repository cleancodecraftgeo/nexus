<script setup lang="ts">
import { ref,onMounted} from 'vue';
import { orderService } from '@/services/order.service'
import type { Order } from '@/types/Order';
import { statusClass } from '@/composables/statusClass';
import {backendUrl } from '@/services/api';

const orders = ref<Order[]>([]);
const loading = ref(true)
const error = ref('')

async function fetchOrders() {
  try {
    loading.value = true
    error.value= ''

    const response = await orderService.myOrders()

    orders.value = response.data.orders
    // for debugging
    console.log(orders.value);
  } catch {
    error.value = 'Orders could not be fetched'
  } finally {
    loading.value = false
  }
}

function formatDate(date: string) {
  return new Date(date).toLocaleDateString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  });
}



onMounted(()=>{
  fetchOrders()
})
</script>

<template>
  <section class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-semibold mb-6">
      My Orders
    </h1>

    <p v-if="loading">
      Loading orders...
    </p>

    <p
      v-else-if="error"
      class="text-red-500"
    >
      {{ error }}
    </p>

    <p
      v-else-if="orders.length === 0"
      class="text-gray-500"
    >
      You don't have any orders yet.
    </p>

    <div
      v-else
      class="space-y-6 "
    >
      <div
        v-for="order in orders"
        :key="order.id"
        class="border rounded-xl p-5"
      >
        <div class="flex justify-between items-start mb-4">
          <div>
            <p class="text-sm text-gray-500">
              Order
            </p>

            <p class="font-medium">
              {{ order.id }}
            </p>

            <p class="text-sm text-gray-500 mt-1">
              {{ formatDate(order.created_at) }}
             </p>
          </div>

          <span
          :class="[
            'text-sm font-medium px-3 py-1 rounded-full capitalize', statusClass(order.status)
          ]"
          >
            {{ order.status }}
          </span>
        </div>

        <div
  v-for="item in order.items"
  :key="item.id"
  class="border-t py-4 flex gap-4"
>
  <img
    v-if="item.product.thumbnail"
    :src="`${backendUrl}/storage/${item.product.thumbnail}`"
    :alt="item.product.name"
    class="w-20 h-20 object-cover rounded-lg"
  />

  <div>
    <h2 class="font-medium">
      {{ item.product.name }}
    </h2>

    <p>
      Quantity: {{ item.quantity }}
    </p>

    <p>
      Price: {{ item.price }}
    </p>

    <div class="mt-2">
      <span
        v-for="attributeValue in item.variant.attribute_values"
        :key="attributeValue.id"
        class="mr-4 text-sm text-gray-600"
      >
        {{ attributeValue.attribute.name }}:
        {{ attributeValue.value }}
      </span>
    </div>
  </div>
</div>

        <div class="border-t pt-4 flex justify-between">
          <RouterLink :to="`/orders/${order.id}`"
        class="text-sm font-medium underline"
        >
            View details
        </RouterLink>
        
          <div>
            <span class="text-gray-500 mr-2">
            Total:
          </span>

          <span class="font-semibold">
            {{ order.total }}
          </span>
          </div>
        </div>
        <div>

</div>
      </div>

    </div>

  </section>
</template>
