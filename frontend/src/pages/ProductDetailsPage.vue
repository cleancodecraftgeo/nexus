<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { ProductService } from '@/services/product.service';
import type { Product, ProductVariant } from "../types/Product";
import { storageUrl } from '@/utils/images';
import { useCartStore } from '@/stores/cart.store';
import {   computed, onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';
import { api } from '@/services/api'

async function testOrder() {
  try {
    const response = await api.get(
      '/orders/01m2p2h60vxjwn7n8gepwyr33x'
    )

    console.log('ORDER:', response.data)
  } catch (error) {
    console.log('ORDER ERROR:', error)
  }
}
interface SelectedValues {
    [key: string]: string;
}


const selectedValues = ref<SelectedValues>({});

const route = useRoute();
const cartStore = useCartStore();

const product = ref<Product|null>(null);
const loading = ref(true);
const error = ref<string|null>(null);

function attributeSet(attributeName:string,value:string):void{
  selectedValues.value[attributeName]=value;

}
const checkAttribute = (attributeName:string,value:string):boolean=>{
    return selectedValues.value[attributeName] === value
}

function isValueAvailable(
  attributeName: string,
  value: string
): boolean {

  const variants = product.value?.variants ?? [];



  return variants.some((variant) => {

    const candidateSelection: SelectedValues = {
      ...selectedValues.value,
      [attributeName]: value,
    };


    return Object.entries(candidateSelection).every(
      ([attrName, attrValue]) => {

        return variant.attributeValues.some(
          (av) =>
            av.attribute.trim().toLowerCase() === attrName.trim().toLowerCase() &&
            av.value.trim().toLowerCase() === attrValue.trim().toLowerCase()
        );

      }
    );
  });
}

const selectedVariant = computed<ProductVariant | null>(()=>{
  const variants = product.value?.variants ??[];

  if(Object.keys(selectedValues.value).length===0){
    return null;
  }
  const requiredAttributes = product.value?.attributes?.length ?? 0;
  const selectedAttributes = Object.keys(selectedValues.value).length;

  if(requiredAttributes !== selectedAttributes){
    return null;
  }
   return  variants.find((variant)=>{
    return Object.entries(selectedValues.value).every(
      ([attrName, attrValue])=>{
        return variant.attributeValues.some(
          (av)=>
          av.attribute.trim().toLowerCase()===attrName.trim().toLowerCase() &&
          av.value.trim().toLowerCase()=== attrValue.trim().toLowerCase()
        );
      }
    );
   })??null
})

const quantity = ref(1);

function addToCart():void{

  if(!product.value){
    return;
  }
  if(!selectedVariant.value){
    return;
  }

  cartStore.addToCart(
    product.value,
    selectedVariant.value,
    quantity.value
  )
}
onMounted(async () => {
  try {
    const response = await ProductService.getProduct(route.params.slug as string);
    product.value = response.data;

    // console.log('PRODUCT:', product.value);
    // console.log('ATTRIBUTES:', product.value?.attributes);
    // console.log('VARIANTS:', product.value?.variants);

  } catch {
    error.value = "product could not be loaded.";
  } finally {
    loading.value = false;
//     console.log("test onMounted: ",
//   JSON.stringify(product.value?.variants, null, 2)
// );

console.log(product);
  }
});


// watch(selectedVariant, (value) => {
//     console.log('SELECTED VARIANT:', value);
// });
</script>

<template>
  <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
    <Breadcrumbs :current-label="product?.name" />
    <!-- main grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- gallery -->
      <div class="bg-slate-100 rounded-2xl overflow-hidden aspect-square border border-amber-500">
        <img v-if="product?.thumbnail" :src="storageUrl(product.thumbnail)" :alt="product.name"
          class="w-full h-full object-contain">
        <p v-else>No image available</p>
      </div>
      <!-- product info -->
      <div class="border border-red-400 ">
        <h1 class="text-2xl md:text-3xl font-bold text-slate-900 ">{{product?.brand}}</h1>
        <h2 class="text-2xl md:text-3xl font-bold text-slate-900 ">{{product?.name}}</h2>
        <p class="text-2xl md:text-3xl font-bold text-slate-900 mt-2">
          $ {{ selectedVariant?.price ?? product?.price }}
        </p>
        <p v-if="selectedVariant">
          Stock: {{ selectedVariant.stock }}
        </p>
        <p v-if="product?.description" v-html="product.description"></p>

        <div v-for="attribute in product?.attributes ?? []" :key="attribute.id" class="md:mt-3 p-1">
  <p>{{ attribute.name }}</p>

  <div class="flex flex-wrap gap-2">
    <button
      type="button"
      v-for="value in attribute.values"
      :key="value.id"
      :style="attribute.name === 'Color' ? { '--btn-bg': value.value } : {}"
      :disabled="!isValueAvailable(attribute.name, value.value)"
      @click="attributeSet(attribute.name, value.value)"
      :class="[
        attribute.name === 'Color'
          ? 'color-swatch'
          : (checkAttribute(attribute.name, value.value) ? 'bg-gray-950' : 'bg-gray-500'),
        checkAttribute(attribute.name, value.value) ? 'ring-2 ring-offset-1 ring-amber-600' : '',
        !isValueAvailable(attribute.name, value.value)
          ? 'cursor-not-allowed opacity-50 text-gray-300'
          : 'cursor-pointer'
      ]"
      class="px-2 py-1 text-slate-200 font-medium
        hover:bg-amber-600 hover:text-shadow-white hover:text-white rounded-xs shadow-lg shadow-black/50
        transition duration-150"
    >
      {{ attribute.name === 'Color' ? '' : value.value }}
    </button>
  </div>
</div>

      </div>
    </div>

    <div>
      <!-- test ucun olan div elementi -->
      <button type="button" @click="addToCart" class="border border-red-400 hover:bg-red-700 rounded-2xl shadow-gray-800 bg-amber-700 text-white font-semibold px-4 py-3">
        Add to Cart
      </button>
    </div>

    <!-- recomended products -->
    <div><button
  type="button"
  @click="testOrder"
  class="border px-4 py-2"
>
  Test Order Policy
</button></div>
  </div>
</template>
<style scoped>
.color-swatch {
  background-color: var(--btn-bg) !important;
  min-width: 2rem;
  min-height: 2rem;
}
</style>
