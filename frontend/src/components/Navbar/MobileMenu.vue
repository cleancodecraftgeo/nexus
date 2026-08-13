<script setup lang="ts">
import { RouterLink } from 'vue-router';


const props = defineProps<{
    isOpen: boolean
    links: {label:string; path:string}[]

}>()

const emit = defineEmits<{
  close: []
}>()
</script>


<template>

<div
    v-if="props.isOpen"
    class="absolute left-0 right-0 top-full bg-slate-950 md:hidden"
>
    <div class="flex flex-col gap-4 px-6 py-5">
        <RouterLink
            v-for="link in props.links"
            :key="link.path + link.label"
            :to="link.path"
            @click="emit('close')"
            class="text-sm font-medium text-slate-300 transition hover:text-white"
        >
            {{ link.label }}
        </RouterLink>
    </div>
</div>
</template>
