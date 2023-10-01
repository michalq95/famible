<template>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8 relative">
        <h1
            class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-400"
        >
            {{ title }}
            <!-- <img
                :src="image"
                alt=""
                class="rounded-sm text-white text-lg font-semibold"
            /> -->
        </h1>

        <slot name="header"></slot>
    </div>
    <main>
        <div class="mx-auto max-w-7xl py-3 sm:px-6 lg:px-8 relative">
            <slot> </slot>
        </div>
    </main>
</template>

<script setup>
import { useStore } from "vuex";
const store = useStore();

const props = defineProps({
    title: String,
    image: String,
});
</script>

<style lang="scss" scoped></style>
