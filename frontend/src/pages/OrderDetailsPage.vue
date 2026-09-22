<script setup lang="ts">
import { orderService } from '@/services/order.service';
import type { Order } from '@/types/Order';
import { onMounted,ref } from 'vue';
import { useRoute } from 'vue-router';


const route = useRoute();
const order = ref<Order|null>(null)
const loading = ref(true)
const error = ref('')

async function fetchOrder(){
  try {
    loading.value = true
    error.value = ''
    const response = await orderService.getOrder(route.params.id as string)

    order.value = response.data.orders
  } catch  {
      error.value = 'Order could not be fetched'
  } finally {
      loading.value = false
  }
}
onMounted(()=>{
  fetchOrder()
  console.log('datalar: ', order.value);
})
</script>

<template>
  <section class="max-w-5xl mx-auto px-4 py-8">

    <p v-if="loading">
      Loading order...
    </p>

    <p
      v-else-if="error"
      class="text-red-500"
    >
      {{ error }}
    </p>

    <div v-else-if="order">
      <h1 class="text-2xl font-semibold mb-4">
        Order Details
      </h1>

      <p>
        Order ID: {{ order.id }}
      </p>

      <p>
        Status: {{ order.status }}
      </p>

      <p>
        Total: {{ order.total }}
      </p>

      <div
        v-for="item in order.items"
        :key="item.id"
        class="border-t py-4 mt-4"
      >
        <h2 class="font-medium">
          {{ item.product.name }}
        </h2>

        <p>
          Quantity: {{ item.quantity }}
        </p>

        <p>
          Price: {{ item.price }}
        </p>

        <div>
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

  </section>
</template>
