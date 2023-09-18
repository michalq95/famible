<template>
    <PageComponent v-slot:header :title="model.name"> </PageComponent>

    <div v-if="roomLoading" class="flex justify-center">Loading...</div>
    <div
        v-for="post in model.posts"
        :key="post.id"
        class="flex justify-between items-center py-3 px-5 shadow-md dark:bg-sky-900 dark:hover:bg-sky-700 h-[80px]"
    >
        <h3>
            {{ post.title }}
        </h3>
        <h5>{{ post.description }}</h5>
        {{ post.status }}
        {{ post.added_by.name }}
        {{ post.user_handling?.name }}
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";

import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const route = useRoute();
const model = ref({ posts: [], users: [], name: "", description: "" });
const roomLoading = computed(() => store.state.currentRoom.loading);

watch(
    () => store.state.currentRoom.data,
    (newVal, oldVal) => {
        axiosClient.get(`room/${newVal.id}`).then((res) => {
            model.value = { ...res.data.data };
        });
        model.value = {
            // ...JSON.parse(JSON.stringify(newVal)),
            ...newVal,
        };
    }
);

if (route.params.id) {
    store.dispatch("getRoom", route.params.id);
}
</script>
