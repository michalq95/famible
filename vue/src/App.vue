<script setup>
import DefaultLayout from "./components/DefaultLayout.vue";
import store from "./store";
</script>

<template>
    <!-- <router-view></router-view> -->
    <DefaultLayout></DefaultLayout>
    <div
        v-if="$store.state.error"
        class="py-3 px-4 bg-red-400 text-white rounded flex items-center justify-between top-1/3 fixed left-0 mx-auto w-72"
    >
        {{ $store.state.error }}
        <span
            class="cursor-pointer text-right rounded-full"
            @click="$store.commit('setError', '')"
            >X</span
        >
    </div>
</template>

<style scoped>
.logo {
    height: 6em;
    padding: 1.5em;
    will-change: filter;
    transition: filter 300ms;
}
.logo:hover {
    filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
    filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
