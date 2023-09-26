<template>
    <PageComponent v-slot:header :title="model.name"> </PageComponent>
    <button
        class="p-2 flex items-center justify-center bg-blue-500 rounded-2xl"
        @click="showNewPost = !showNewPost"
    >
        Add new post!
    </button>
    <button
        v-if="isMod"
        class="p-2 flex items-center justify-center bg-blue-500 rounded-2xl"
        @click="showUserSearch = !showUserSearch"
    >
        Add new members!
    </button>
    <NewPost v-if="showNewPost" @close="showNewPost = false"></NewPost>
    <UserSearch
        v-if="showUserSearch"
        @close:search="showUserSearch = false"
        :roomId="route.params.id"
    ></UserSearch>
    {{ showUserDropdown }}
    <div v-if="roomLoading" class="flex justify-center">Loading...</div>
    <img
        :src="model.image"
        alt=""
        class="rounded-sm text-white text-lg font-semibold"
    />
    <div class="flex flex-wrap">
        <div
            v-for="user in model.users"
            :key="user.id"
            class="w-12 h-12 flex items-center justify-center bg-blue-500 rounded-2xl m-2"
        >
            <div @click="setShowUserDropdown(user.id)">
                <img
                    v-if="user.image"
                    :src="user.image"
                    :alt="user.name.slice(0, 2)"
                    class="rounded-full text-white text-lg font-semibold"
                />
                <span v-else class="text-white text-lg font-semibold"
                    >{{ user.name.slice(0, 2) }}
                </span>
                <UserDropdown
                    :user="user"
                    @close:dropdown="showUserDropdown = -1"
                    v-if="isMod && showUserDropdown == user.id"
                ></UserDropdown>
            </div>
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
import NewPost from "../components/NewPost.vue";
import UserSearch from "../components/UserSearch.vue";
import UserDropdown from "../components/UserDropdown.vue";

const route = useRoute();
const model = ref({
    posts: [],
    users: [],
    name: "",
    description: "",
    image: null,
});
const showNewPost = ref(false);
const showUserSearch = ref(false);
const showUserDropdown = ref(-1);
const roomLoading = computed(() => store.state.currentRoom.loading);

const isMod = computed(() => {
    const users = store.state.currentRoom?.data?.users;
    if (!users) return false;

    return users
        .filter((el) => el.role <= 1)
        .filter((el) => el.status == 1)
        .find((el) => el.id == store.state.user.data.id);
});

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

function setShowUserDropdown(id) {
    showUserDropdown.value == id
        ? (showUserDropdown.value = -1)
        : (showUserDropdown.value = id);
}

if (route.params.id) {
    store.dispatch("getRoom", route.params.id);
}
</script>
