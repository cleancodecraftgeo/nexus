
<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store';
import axios from 'axios';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';



const authStore = useAuthStore()
const router = useRouter()

const form = reactive({
  name:'',
  email:'',
  password:'',
  passwordConfirmation:'',
  error:'',
  loading:false,
})

async function submitRegister(){
  form.error= ''
  form.loading=true

  try{
      await authStore.register(
        form.name,
        form.email,
        form.password,
        form.passwordConfirmation
      )
      router.push('/')
  }catch(err:unknown){
    if(axios.isAxiosError(err)){
      form.error=err.response?.data?.message?? 'Registration failed'
    }else{
      form.error='Registration failed.'
    }
  }finally{
    form.loading=false
  }
}
</script>

<template>
  <div class="max-w-md mx-auto mt-16 ">
    <h1 class="text-2xl text-center font-bold mb-6 ">Register</h1>

    <form @submit.prevent="submitRegister" class="space-y-4">
      <label for="name" class="block mb-1">Name</label>
      <input id="name" type="text"
      v-model="form.name"
      required
      class="w-full border rounded px-3 py-2">

      <label for="email" class="block mb-1">Email</label>
      <input id="email" type="email"
      v-model="form.email"
      required
      class="w-full border rounded px-3 py-2">

      <label for="password" class="block mb-1">password</label>
      <input id="password" type="password"
      v-model="form.password"
      required
      class="w-full border rounded px-3 py-2">

       <label  for="passwordConfirmation" class="block mb-1">Password Confirmation</label>
      <input id="passwordConfirmation" type="password"
      v-model="form.passwordConfirmation"
      required
      class="w-full border rounded px-3 py-2">

      <p v-if="form.error" class="text-red-600 text-sm">
          {{ form.error }}
      </p>

      <button type="submit"
      :disabled="form.loading"
      class="w-full border rounded px-4 py-2 hover:shadow-gray-700 hover:shadow-xl
      transition-all duration-300"
      >{{form.loading ? "Registering... ":"Register"}}</button>
    </form>
  </div>
</template>
