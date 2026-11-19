<script setup lang="ts">
import { useCartStore } from "@/stores/cart.store";
import { useOrderStore } from "@/stores/order.store";
import { createOrder } from "@/services/order.service";
import { storageUrl } from "@/utils/images";
import { useRouter } from "vue-router";
import { reactive, ref } from "vue";
import axios from "axios";
import { useNotify } from "@/composables/useNotify";
import { useI18n } from 'vue-i18n'

// import { M } from "vue-router/dist/index-BN0B0y8a.js";
const cartStore = useCartStore();
const orderStore = useOrderStore();
const notify = useNotify();
const orderError = ref<string | null>(null);
const loading = ref(false);
const router = useRouter();
const {t} = useI18n();

const placeOrder = async () => {
    loading.value = true;
    orderError.value = null;

    try {
        const payload = {
            ...form,

            items: cartStore.items.map((item) => ({
                product_id: item.product.id,
                variant_id: item.variant.id,
                quantity: item.quantity,

            })),

        };

            const response = await createOrder(payload);



    orderStore.setLastOrder(response.order);
    cartStore.items = [];



    router.push({
        name: "order-success",
        params: {
            id: response.order.id,
        },
    });

    } catch (error) {
    if (axios.isAxiosError(error)) {
        const data = error.response?.data;
        console.log("BACKEND ERROR DATA:", data);
        console.log(data);

        if (data?.code === "INSUFFICIENT_STOCK") {
           const message = data?.message ?? "Insufficient stock."

            orderError.value = message
            notify.warning(message)

            return
          }

          if (data?.code === "OUT_OF_STOCK") {
              const message = data?.message ?? "This product is currently out of stock."

              orderError.value = message
              notify.error(message)

              return
      }


        const message = data?.message ?? "Order could not be created.";
        orderError.value = message;
        notify.error(message);
        return;
    }

    const message = "Order could not be created.";
    orderError.value = message;
    notify.error(message);


} finally {
    loading.value = false;
}
}

const form = reactive({
  first_name: '',
  last_name: '',
  phone: '',
  email: '',
  city: '',
  address: '',
  postal_code: '',
  notes: '',
})

</script>

<template>
  <div class="min-h-screen bg-slate-50">
    <div class="mx-auto max-w-7xl px-6 py-10">



      <!-- Header -->
      <div class="mb-10">
        <RouterLink to="/cart" class="text-sm text-slate-500 transition hover:text-slate-900">
          ← {{ t('checkout.backToCart') }}
        </RouterLink>

        <h1 class="mt-4 text-3xl font-bold text-slate-900">
          {{ t('checkout.checkout') }}
        </h1>
      </div>

      <!-- Checkout layout -->
      <div class="grid gap-10 lg:grid-cols-[1fr_420px]">

        <!-- LEFT SIDE -->
        <div class="space-y-8">

          <!-- Contact -->
          <section class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-900">
              {{t('checkout.contactInformation')}}
            </h2>

            <div class="mt-5">
              <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                {{t('checkout.email')}}
              </label>

              <input id="email" type="email" v-model="form.email" placeholder="you@example.com"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
            </div>
          </section>

          <!-- Shipping -->
          <section class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-900">
              {{ t('checkout.shippingInformation') }}
            </h2>

            <div class="mt-5 grid gap-5 sm:grid-cols-2">

              <div class="sm:col-span-2">
                <label for="Name" aria-label="Full name" class="mb-2 block text-sm font-medium text-slate-700">
                  {{ t('profile.name') }}
                </label>

                <input id="Name" type="text" v-model="form.first_name" :placeholder="t('profile.name')"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />

                <label for="LastName" aria-label="Full name" class="mb-2 block text-sm font-medium text-slate-700">
                  {{ t('profile.surname') }}
                </label>

                <input id="LastName" type="text" v-model="form.last_name" :placeholder="t('profile.surname')"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>

              <div class="">
                <label for="cityInput" class="mb-2 block text-sm font-medium text-slate-700">
                  {{ t('checkout.city') }}
                </label>

                <input id="cityInput" type="text" v-model="form.city" placeholder="Tbilisi"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />


              </div>

              <div>
                <label for="phoneInput" class="mb-2 block text-sm font-medium text-slate-700">
                  {{ t('checkout.phone') }}
                </label>

                <input id="phoneInput" v-model="form.phone" inputmode="numeric" placeholder="598 12 13 14"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>

              <div class="sm:col-span-2">
                <label for="postalCodeInput" class="mb-2 block text-sm font-medium text-slate-700">
                  {{ t('checkout.postalCode') }}
                </label>

                <input id="postalCodeInput" type="text" v-model="form.postal_code" placeholder="11111"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
              </div>




              <div class="sm:col-span-2">
                <label for="addressInput" class="mb-2 block text-sm font-medium text-slate-700">
                  {{ t('checkout.address') }}
                </label>

                <input id="addressInput" v-model="form.address" type="text"
                  :placeholder="t('checkout.addressPlaceholder')"
                  class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />

              </div>

            </div>
            <div>
              <label for="notesInput" class="mb-2 block text-sm font-medium text-slate-700">
                {{ t('checkout.notes') }}
              </label>

              <textarea id="notesInput" rows="4" v-model="form.notes" :placeholder="t('checkout.notesPlaceholder')"
                class="w-full rounded-lg border border-slate-300 px-4 py-3 outline-none transition focus:border-slate-900 focus:ring-1 focus:ring-slate-900" />
            </div>
          </section>

          <!-- Payment -->
          <section class="rounded-2xl border border-slate-200 bg-white p-6">
            <h2 class="text-xl font-semibold text-slate-900">
              {{ t('checkout.payment') }}
            </h2>

            <div class="mt-5 rounded-lg border border-slate-200 bg-slate-50 p-4">
              <p class="text-sm font-medium text-slate-900">
                Payment method
              </p>

              <p class="mt-1 text-sm text-slate-500">
                Payment integration will be added soon.
              </p>


            </div>

          </section>

        </div>

        <!-- RIGHT SIDE -->
        <aside class="h-fit rounded-2xl border border-slate-200 bg-white p-6 lg:sticky lg:top-6">
          <h2 class="text-xl font-semibold text-slate-900">
            {{ t('checkout.yourOrder') }}
          </h2>

          <!-- Products -->
          <div class="mt-6 space-y-5">

            <div v-for="item in cartStore.items" :key="item.variant.id" class="flex gap-4">
              <div class="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                <img v-if="item.product.thumbnail" :src="storageUrl(item.product.thumbnail)" :alt="item.product.name"
                  class="h-full w-full object-contain" />

                <span
                  class="absolute -right-1 -top-1 flex h-5 min-w-5 items-center justify-center rounded-full bg-slate-900 px-1 text-xs font-bold text-white">
                  {{ item.quantity }}
                </span>
              </div>

              <div class="min-w-0 flex-1">
                <h3 class="truncate text-sm font-medium text-slate-900">
                  {{ item.product.name }}
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                  ${{ item.variant.price }}
                </p>
              </div>

              <p class="text-sm font-medium text-slate-900">
                ${{ Number(item.variant.price) * item.quantity }}
              </p>
            </div>

          </div>

          <!-- Summary -->
          <div class="mt-6 border-t border-slate-200 pt-6">

            <div class="flex justify-between text-sm text-slate-600">
              <span>{{ t('checkout.items') }}</span>
              <span>{{ cartStore.cartCount }}</span>
            </div>

            <div class="mt-3 flex justify-between text-sm text-slate-600">
              <span>{{ t('checkout.subtotal') }}</span>
              <span>
                ${{ cartStore.cartTotal }}
              </span>
            </div>

            <div class="mt-4 flex justify-between border-t border-slate-200 pt-4 text-lg font-bold text-slate-900">
              <span>{{ t('checkout.total') }}</span>

              <span>
                ${{ cartStore.cartTotal }}
              </span>
            </div>

          </div>

          <!-- Place order -->
          <button type="button" @click="placeOrder" :disabled="loading || cartStore.items.length === 0"
            class="mt-6 w-full rounded-xl bg-slate-950 px-5 py-3.5 text-sm font-semibold text-white transition hover:bg-slate-800 disabled:cursor-not-allowed disabled:opacity-50">
            {{ loading ? "Creating Order..." : "Place Order" }}
          </button>

          <p v-if="orderError" class="mt-3 text-sm text-red-600">
            {{ orderError }}
          </p>

        </aside>

      </div>
    </div>
  </div>
</template>
