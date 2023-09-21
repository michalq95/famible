<template>
    <div
        class="fixed justify-between items-center py-3 px-5 shadow-md dark:bg-sky-700"
    >
        <div>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close')"
            >
                X
            </button>
            <button class="p-2 mx-2 bg-blue-500 rounded-2xl" @click="newPost()">
                Add new post!
            </button>
        </div>
        <v-text-field
            label="Label"
            v-model="newPostModel.title"
            class="text-black"
        ></v-text-field>
        <!-- <input
            id="title"
            name="name"
            type="text"
            placeholder="title"
            v-model="newPostModel.title"
            class="block w-full rounded-md bg-zinc-200 border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
        /> -->
        <v-textarea
            label="description"
            v-model="newPostModel.description"
        ></v-textarea>
        <select
            class="p-2 mx-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
            name="user_handling"
            v-model="newPostModel.user_handling"
        >
            <option :value="null">...</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
            </option>
        </select>
        <select
            class="p-2 mx-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
            name="status"
            v-model="newPostModel.status"
        >
            <option value="0">Pending</option>
            <option value="1">On It</option>
            <option value="2">Complications</option>
            <option value="3">Done</option>
        </select>
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import store from "../store";

const emit = defineEmits(["close"]);

const users = computed(() => store.state.currentRoom.data.users);

const newPostModel = ref({
    title: "",
    description: "",
    status: 0,
    user_handling: undefined,
});

function newPost() {
    store.dispatch("savePost", newPostModel.value);
    newPostModel.value = {
        title: "",
        description: "",
        status: 0,
        user_handling: undefined,
    };

    emit("close");
}
</script>
