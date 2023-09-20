<template>
    <PageComponent v-slot:header :title="model.name"> </PageComponent>
    <button
        class="p-2 flex items-center justify-center bg-blue-500 rounded-2xl"
        @click="showNewPost = !showNewPost"
    >
        Add new post!
    </button>
    <NewPostComponent
        v-if="showNewPost"
        @close="showNewPost = false"
    ></NewPostComponent>
    <div v-if="roomLoading" class="flex justify-center">Loading...</div>
    {{ model.users }}
    <div class="flex flex-wrap">
        <div
            v-for="user in model.users"
            :key="user.id"
            class="w-12 h-12 flex items-center justify-center bg-blue-500 rounded-2xl m-2"
        >
            <img
                v-if="user.image"
                :src="user.image"
                :alt="user.name.slice(0, 2)"
                class="rounded-full text-white text-lg font-semibold"
            />
            <span v-else class="text-white text-lg font-semibold"
                >{{ user.name.slice(0, 2) }}
            </span>
        </div>
    </div>
    <div v-for="post in model.posts" :key="post.id">
        <PostComponent :post="post"></PostComponent>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useRoute } from "vue-router";
import axiosClient from "../axios";

import store from "../store";
import PageComponent from "../components/PageComponent.vue";
import PostComponent from "../components/PostComponent.vue";
import NewPostComponent from "../components/NewPostComponent.vue";

const route = useRoute();
const model = ref({ posts: [], users: [], name: "", description: "" });
const showNewPost = ref(false);
const roomLoading = computed(() => store.state.currentRoom.loading);

watch(
    () => store.state.currentRoom.data,
    (newVal, oldVal) => {
        // axiosClient.get(`room/${newVal.id}`).then((res) => {
        //     model.value = { ...res.data.data };
        // });
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
