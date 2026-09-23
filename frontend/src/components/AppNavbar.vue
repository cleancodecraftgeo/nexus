<script setup lang="ts">
import { useCartStore } from '@/stores/cart.store';
import { RouterLink,   useRouter } from 'vue-router';
import {Heart, Menu,  ShoppingCart, User} from "lucide-vue-next"
import { ref,computed } from 'vue';
import MobileMenu from './Navbar/MobileMenu.vue';
import { useAuthStore } from '@/stores/auth.store';
import { useI18n } from 'vue-i18n';

const {t,locale} = useI18n()
const cartStore = useCartStore();
const isMenuOpen = ref<boolean>(false);
const authStore = useAuthStore()
const router = useRouter()

const navbarLinks = computed(()=> [
  {label: t('nav.home'), path: '/'},
  {label: t('nav.category'), path: '/products'},
  {label: t('nav.collection'), path: '/products'},
  {label: t('nav.contact'), path: '/products'},
])

const toggleMenu = ():void=>{
    isMenuOpen.value=!isMenuOpen.value

}

async function logout(){
  await authStore.logout()

  router.push('/login')
}


// const savedLocale = localStorage.getItem('locale')

// if (savedLocale === 'en' || savedLocale === 'az' || savedLocale === 'ge') {
//   locale.value = savedLocale
// }
const supportedLocales = ['en', 'az', 'ru', 'ka'] as const
type SupportedLocale = typeof supportedLocales[number]

const savedLocale = localStorage.getItem('locale') as SupportedLocale | null
if (savedLocale && supportedLocales.includes(savedLocale)) {
  locale.value = savedLocale
}

function changeLocale(event: Event) {
  const value = (event.target as HTMLSelectElement).value as SupportedLocale
  locale.value = value
  localStorage.setItem('locale', value)
}
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
          class="text-sm md:text-base font-medium  text-slate-300 hover:text-white transition"
          active-class="!text-white">
          {{ link.label }}
        </RouterLink>
      </div>
      <!-- center ends-->
      <!-- right side -->

      <div class="hidden md:flex items-center gap-x-4   text-slate-300">
        <label for="language-select" class="sr-only">
          Language
        </label>

        <select id="language-select" v-model="locale" @change="changeLocale"
          class="bg-slate-900 text-white border border-slate-700 rounded px-2 py-1 text-sm">
          <option value="en">EN</option>
          <option value="az">AZ</option>
          <option value="ru">RU</option>
          <option value="ka">KA</option>
        </select>
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

        <div class="flex items-center gap-4">
          <template v-if="!authStore.isAuthenticated">
            <RouterLink to="/login" class="hover:opacity-70">
              {{ t('nav.login') }}
            </RouterLink>

            <RouterLink to="/register" class="hover:opacity-70">
              {{ t('nav.register') }}
            </RouterLink>

          </template>

          <template v-else>
            <span>{{authStore.user?.name}}</span>
            <button type="button" @click="logout" class="hover:opacity-70">{{t('nav.logout')}}</button>
          </template>

        </div>

      </div>
      <!-- right side ends-->
      <!-- right side mobile -->
      <div class="md:hidden relative">

        <button type="button" aria-label="Open Menu" @click="toggleMenu">
          <Menu :size="22" />
        </button>

      </div>

      <!-- right side mobile ends-->
    </nav>

    <MobileMenu :is-open="isMenuOpen" :links="navbarLinks" @close="isMenuOpen=false" />
  </header>
</template>
