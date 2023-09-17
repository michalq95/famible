<template>
    <PageComponent title="Dashboard">
        <!-- <template v-slot:header> Dashboard </template> -->

        Rest of the page
    </PageComponent>
</template>

<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";
import { useStore } from "vuex";
import PageComponent from "../components/PageComponent.vue";
const store = useStore();

const user = computed(() => store.state.user.data);
</script>

<style scoped lang="scss"></style>
