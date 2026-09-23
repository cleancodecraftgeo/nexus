<script setup lang="ts">
import { useAuthStore } from '@/stores/auth.store';
import { ref,reactive } from 'vue';
import axios from 'axios'
const authStore = useAuthStore()

const form = reactive({
  name:authStore.user?.name ?? '',
  email:authStore.user?.email ?? '',
})

const loading = ref(false)
const error = ref('')
const message = ref('')
const isEditing = ref(false)

async function updateProfile(){
  try {

        loading.value = true
        error.value = ''
        message.value = ''


        const response = await authStore.updateProfile(
          form.name,
          form.email)

        message.value = response.data?.message
        isEditing.value = false
  } catch (err) {
    if (axios.isAxiosError(err)) {
      console.log('PROFILE UPDATE ERROR:', err.response?.data)

      error.value =
        err.response?.data?.message ??
        'Profile could not be updated'
    }
  } finally {
      loading.value= false
  }
}
</script>

<template>
  <section class="max-w-3xl mx-auto px-4 py-3 border rounded mt-2 relative">
    <div class="flex justify-between mb-6 ">
      <h1 class="md:text-2xl font-semibold ">My account</h1>

      <button type="button" @click="isEditing = true" class=" border rounded px-4 py-2">
        Edit Profile
      </button>
    </div>

    <div v-if="authStore.user" class="border rounded-xl p-6 space-y-3">
      <p>
        <span class="font-medium">Name: </span>{{ authStore.user.name }}
      </p>
      <p>
        <span class="font-medium">Email: </span>{{ authStore.user.email }}
      </p>
      <p>
        <span class="font-medium">Role: </span>{{ authStore.user.role }}
      </p>
    </div>


    <form v-if="isEditing" @submit.prevent="updateProfile"
      class="absolute top-12 right-0 z-10 w-80 p-6 rounded-lg bg-gray-800 shadow-xl border border-gray-700 space-y-4">

      <div>
        <label for="name" class="block text-gray-100 mb-1 ">Name</label>
        <input id="name" v-model="form.name" type="text" class="border text-gray-100 rounded px-3 py-2 w-full" />
      </div>

      <div>
        <label for="email" class="block text-gray-100 mb-1">Email</label>
        <input id="email" v-model="form.email" type="email" class="border text-gray-100 rounded px-3 py-2 w-full" />
      </div>

      <p v-if="error" class="text-red-600">
        {{ error }}
      </p>

      <div class="flex justify-between gap-x-2">

        <button type="button" @click="isEditing = false" class="border bg-red-600  text-gray-100 rounded px-4 py-2">
          Cancel
        </button>

        <button type="submit" :disabled="loading" class="border flex-1 bg-green-600  text-gray-100 rounded px-4 py-2">
          {{ loading ? 'Saving...' : 'Save' }}
        </button>
      </div>

    </form>

  </section>

</template>
