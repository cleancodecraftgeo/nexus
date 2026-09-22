<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store';
import axios from "axios"
import { reactive } from 'vue';
import { useRouter } from 'vue-router';


const authStore = useAuthStore()
const router = useRouter()

const form = reactive({
  email:'',
  password:'',
  error:'',
  loading:false
})

async function submitLogin(){
  form.error=''
  form.loading=true


try {
  await authStore.login(form.email, form.password)
  router.push('/')
} catch (err: unknown) {
  if (axios.isAxiosError(err)) {
    form.error = err.response?.data?.message ?? 'Login failed.'
  } else {
    form.error = 'Login failed.'
  }
  console.log(err)
} finally {
  form.loading = false
}}
</script>

<template>
<div class="max-w-md mx-auto mt-16 ">
  <h1 class="text-2xl font-bold mb-6 text-center">Login</h1>

  <form @submit.prevent="submitLogin" class="space-y-4">
    <div>
      <label for="email" class="block mb-1">Email</label>
      <input
      id="email"
      v-model="form.email"
      type="email"
      required
      class="w-full border rounded px-3 py-2"
      >
    </div>

    <div>
      <label for="password" class="block mb-1">Password</label>

      <input
      id="password"
      type="password" v-model="form.password"
      class="w-full border rounded px-3 py-2"
      required
      >
    </div>

    <p v-if="form.error" class="text-red-600">{{form.error}}</p>

    <button type="submit" :disabled="form.loading"
    class="w-full border rounded px-4 py-2 hover:bg-gray-200 ">{{form.loading ? 'Logging in...': 'Login'}}</button>
  </form>
</div>
</template>
