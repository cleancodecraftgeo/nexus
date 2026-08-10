<!-- eslint-disable vue/multi-word-component-names -->
<script setup lang="ts">

import { computed } from 'vue';
import { RouterLink, useRoute, useRouter } from 'vue-router';
const props = defineProps<{currentLabel?: string;}>();
const router = useRouter();
const route = useRoute();
// console.log("router matgces",route.matched[0]?.path);

const breadcrumbs = computed(() => {
    const items = [
        {
            label: "Home",
            to: "/",
        },
    ];

    const routes = route.matched
        .filter((record) => record.meta.breadcrumb)
        .map((record) => ({
            label:
                record.name === "product-details"
                    ? props.currentLabel ?? "Product"
                    : record.meta.breadcrumb,

            to: router.resolve({
                name: record.name,
                params: route.params,
            }).href,
        }));

    return [...items, ...routes];
});

console.log(
    route.matched.map((record) => ({
        name: record.name,
        path: record.path,
        breadcrumb: record.meta.breadcrumb,
    }))
);
</script>

<template>
    <nav>
      <h2>breadcrumb</h2>
       <RouterLink
        v-for="(item,index) in breadcrumbs" :key="index" :to="item.to"
       >
          {{ item.label }}
          <span v-if="index < breadcrumbs.length - 1">
        →
    </span>
       </RouterLink>
    </nav>
</template>
