<script setup lang="ts">
import { orderService } from '@/services/order.service';
import type { Order } from '@/types/Order';
import { onMounted,ref, computed } from 'vue';
import { useRoute } from 'vue-router';
import { statusClass } from '@/composables/statusClass';
import { backendUrl } from '@/services/api';
import { isAxiosError } from 'axios';

const route = useRoute();
const order = ref<Order|null>(null)
const loading = ref(true)
const error = ref('')
const notFound = ref(false);

async function fetchOrder(){
  try {
    loading.value = true;
    error.value = '';
    notFound.value = false;

    const response = await orderService.getOrder(route.params.id as string)

    order.value = response.data.order

  } catch(err: unknown) {
    if (isAxiosError(err)) {
      if (err.response?.status === 404) {
        notFound.value = true;
        return;
      }

      if (err.response?.status === 403) {
        error.value = "You are not authorized to view this order ";
        return;
      }
    }

    error.value = 'Something wen wrong. Please try again ';
  } finally {
      loading.value = false;
  }
}

function formatDate(date: string): string {
  return new Date(date).toLocaleString('en-GB', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  });
}

function formatMoney(amount: string | number): string {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: order.value?.currency_code ?? 'USD',
  }).format(Number(amount));
}

const itemSubtotal = computed(() => {
  if (!order.value) return 0;

  const subTotalInCents = order.value.items.reduce(
    (sum, item) => sum + Math.round(Number(item.price) * 100) * item.quantity, 0
  );

  return subTotalInCents / 100;
});

onMounted(fetchOrder)
</script>

<template>
  <section class="max-w-5xl mx-auto px-4 py-8">

    <div class="mt-8 mb-4">
      <router-link to="/orders"
        class="inline-flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900 transition-colors">
        <span aria-hidden="true">←</span>
        Back to My Orders
      </router-link>

    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-16">
      <div class="text-center">
        <div class="w-8 h-8 border-4 border-gray-200 border-t-gray-700 rounded-full animate-spin mx-auto"></div>

        <p class="text-sm text-gray-500 mt-4">
          Loading order details...
        </p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="text-center py-16">
      <p class="text-red-600 font-medium">
        {{ error }}
      </p>

      <button @click="fetchOrder"
        class="mt-4 px-5 py-2 rounded-lg bg-gray-900 text-white text-sm hover:bg-gray-700 transition-colors">
        Try Again
      </button>
    </div>

    <!-- Not Found State -->
    <div v-else-if="notFound" class="text-center py-16">
      <h2 class="text-xl font-semibold text-gray-900">
        Order Not Found
      </h2>

      <p class="text-sm text-gray-500 mt-2">
        The order you are looking for does not exist.
      </p>

    </div>

    <div v-else-if="order">
      <div class="flex flex-col sm:flex-row sm:items-center
            justify-between gap-5 mb-8">

        <div>
          <h1 class="text-2xl font-bold">
            Order Details
          </h1>

          <p class="font-mono text-xs sm:text-sm text-gray-500 mt-2 break-all">
            Order #{{ order.id }}
          </p>

          <p class="text-sm text-gray-500 mt-1">
            {{ formatDate(order.created_at) }}
          </p>

        </div>

        <div class="flex flex-col sm:items-end gap-2">
          <span :class="[
        'px-3 py-1 rounded-full text-sm font-medium capitalize',
        statusClass(order.status)
      ]">
            {{ order.status }}
          </span>

          <p class="text-xl font-bold">
            {{ formatMoney(order.total) }}
          </p>
        </div>

      </div>


      <div v-for="item in order.items" :key="item.id"
        class="bg-white border border-gray-200 rounded-xl p-5 mb-4 shadow-sm">
        <div class="flex flex-col sm:flex-row gap-5">

          <!-- Product image -->
          <div class="shrink-0">
            <img v-if="item.product.thumbnail" :src="`${backendUrl}/storage/${item.product.thumbnail}`"
              :alt="item.product.name" class="w-28 h-28 object-cover rounded-lg bg-gray-100" />

            <div v-else class="w-28 h-28 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
              No image
            </div>
          </div>

          <!-- Product information -->
          <div class="flex-1">

            <h2 class="text-lg font-semibold text-gray-900">
              {{ item.product.name }}
            </h2>

            <p class="text-sm text-gray-500 mt-1">
              SKU: {{ item.variant?.sku ?? 'Unavailable' }}
            </p>

            <!-- Variant attributes -->
            <div v-if="item.variant" class="flex flex-wrap gap-2 mt-3">
              <span v-for="attributeValue in item.variant.attribute_values" :key="attributeValue.id"
                class="px-3 py-1 bg-gray-100 rounded-md text-sm text-gray-700">
                {{ attributeValue.attribute.name }} : {{ attributeValue.value }}
              </span>
            </div>

            <p v-else class="text-sm text-gray-400 mt-3">
              Variant information unavailable
            </p>

            <!-- Price information -->
            <div class="border-t mt-4 pt-4 flex flex-wrap justify-between gap-3">

              <div class="text-sm text-gray-500">
                Quantity:
                <span class="font-medium text-gray-900">
                  {{ item.quantity }}
                </span>
              </div>

              <div class="text-sm text-gray-500">
                Unit price:
                <span class="font-semibold text-gray-900">
                  {{ formatMoney(item.price) }}
                </span>
              </div>

              <div class="text-sm text-gray-500">
                Subtotal:
                <span class="font-semibold text-gray-900">
                  {{ formatMoney((Number(item.price) * item.quantity).toFixed(2)) }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Summary -->
      <div class="bg-white border border-gray-200 rounded-xl p-5 mt-8 shadow-sm">

        <h2 class="text-lg font-semibold text-gray-900 mb-5">
          Order Summary
        </h2>

        <div class="space-y-3">

          <div class="flex justify-between text-sm">
            <span class="text-gray-500">Items Subtotal</span>

            <span class="font-medium text-gray-900">
              {{ formatMoney(itemSubtotal) }}
            </span>
          </div>

          <div class="border-t border-gray-200 pt-4 flex justify-between items-center">
            <span class="font-semibold text-gray-900">
              Order Total
            </span>

            <span class="text-xl font-bold text-gray-900">
              {{ formatMoney(order.total) }}
            </span>
          </div>

        </div>
      </div>

      <!-- Customer & Delivery Information -->
      <div class="bg-white border border-gray-200 rounded-xl p-5 mt-8 shadow-sm">

        <h2 class="text-lg font-semibold text-gray-900 mb-5">
          Customer & Delivery Information
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

          <div>
            <p class="text-sm text-gray-500">Full Name</p>
            <p class="font-medium text-gray-900 mt-1">
              {{ order.first_name }} {{ order.last_name }}
            </p>
          </div>

          <div>
            <p class="text-sm text-gray-500">Email</p>
            <p class="font-medium text-gray-900 mt-1 break-all">
              {{ order.email }}
            </p>
          </div>

          <div>
            <p class="text-sm text-gray-500">Phone</p>
            <p class="font-medium text-gray-900 mt-1">
              {{ order.phone }}
            </p>
          </div>

          <div>
            <p class="text-sm text-gray-500">City</p>
            <p class="font-medium text-gray-900 mt-1">
              {{ order.city }}
            </p>
          </div>

          <div class="sm:col-span-2">
            <p class="text-sm text-gray-500">Delivery Address</p>
            <p class="font-medium text-gray-900 mt-1">
              {{ order.address }}
            </p>
          </div>

          <div v-if="order.postal_code">
            <p class="text-sm text-gray-500">Postal Code</p>
            <p class="font-medium text-gray-900 mt-1">
              {{ order.postal_code }}
            </p>
          </div>

          <div v-if="order.notes" class="sm:col-span-2">
            <p class="text-sm text-gray-500">Order Notes</p>
            <p class="font-medium text-gray-900 mt-1 whitespace-pre-line">
              {{ order.notes }}
            </p>
          </div>

        </div>
      </div>
    </div>

  </section>
</template>
