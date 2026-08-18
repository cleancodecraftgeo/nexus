<script setup lang="ts">
import { useCartStore } from '@/stores/cart.store';
import { useRoute } from 'vue-router';
import {Heart, Menu, ShoppingCart, User} from "lucide-vue-next"
import { ref } from 'vue';
import MobileMenu from './Navbar/MobileMenu.vue';



const cartStore = useCartStore();
const isMenuOpen = ref<boolean>(false);

const navbarLinks = [
  {label: 'Home', path: '/'},
  {label: 'Category', path: '/products'},
  {label: 'Collection', path: '/products'},
  {label: 'Contact Us', path: '/products'},
]

const toggleMenu = ():void=>{
    isMenuOpen.value=!isMenuOpen.value
    console.log("toggle menu ",isMenuOpen.value);
}

const route = useRoute()

console.log("vue-router : ",route.path)      // → "/products"
console.log("vue-router : ",route.name)      // → "products"
console.log("vue-router : ",route.params)    // → { slug: "macbook" }
console.log("vue-router : ",route.query)

</script>

<template>

  <header class="relative ">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4 bg-slate-950 text-amber-50
      rounded-lg">
      <!-- left side -->
      <div class="  text-xl font-bold tracking-wide">
        <RouterLink to="/">Nexus</RouterLink>
      </div>
      <!-- left side ends-->
      <!-- center -->
      <div class="hidden  md:flex gap-x-8  ">

        <RouterLink v-for="link in navbarLinks" :key="link.path +link.label" :to="link.path"
          class="text-sm md:text-base font-medium  text-slate-300 hover:text-white transition" active-class="!text-white"
          >
          {{ link.label }}
        </RouterLink>
      </div>
      <!-- center ends-->
      <!-- right side -->
      <div class="hidden md:flex items-center gap-x-4   text-slate-300">
        <RouterLink to="/cart">
          <Heart :size="20" class="cursor-pointer text-slate-300 transition hover:text-white" />
        </RouterLink>

        <RouterLink to="/cart" class="relative">
          <ShoppingCart :size="20" class="cursor-pointer text-slate-300 transition hover:text-white" />
          <span v-if="cartStore.cartCount>0"
            class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">
            {{ cartStore.cartCount }}
          </span>
        </RouterLink>


        <RouterLink to="/">
          <User :size="20" class="cursor-pointer text-slate-300 transition hover:text-white" />
        </RouterLink>


      </div>
      <!-- right side ends-->
       <!-- right side mobile -->
        <div class="md:hidden relative">

            <button
            type="button"

            aria-label="Open Menu"
            @click="toggleMenu"
            >
              <Menu :size="22"/>
            </button>

        </div>

       <!-- right side mobile ends-->
    </nav>

    <MobileMenu
            :is-open="isMenuOpen"
            :links="navbarLinks"
            @close="isMenuOpen=false"
            />
  </header>
</template>
