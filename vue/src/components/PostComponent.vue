<template>
    <div
        class="flex justify-between items-center py-3 px-5 shadow-md dark:bg-sky-900 dark:hover:bg-sky-700 h-[80px]"
    >
        <h3>
            {{ post.title }}
        </h3>
        <h5>{{ post.description }}</h5>
        <!-- {{ post.status }}
        {{ post.added_by.name }}
        {{ post.user_handling?.name }} -->
        {{ post }}
        <!-- {{ users }} -->
        <!-- <button
            class="p-2 text-black bg-slate-200 border-neutral-700 hover:bg-gray-200"
        >
            I got this!
        </button> -->
        <select
            class="p-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
            name="user_handling"
            v-model="post.user_handling"
        >
            <option :value="null">...</option>
            <option v-for="user in users" :key="user.id" :value="user.id">
                {{ user.name }}
            </option>
        </select>
        <select
            class="p-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
            name="status"
            v-model="post.status"
        >
            <option value="0">Pending</option>
            <option value="1">On It</option>
            <option value="2">Complications</option>
            <option value="3">Done</option>
        </select>
        <button
            class="p-2 w-36 bg-slate-300 text-black rounded-md border-neutral-700 hover:bg-gray-200"
            @click="apply"
        >
            Apply
        </button>
    </div>
</template>

<script setup>
import store from "../store";
import { computed } from "vue";
const users = computed(() => store.state.currentRoom.data.users);
const roomId = computed(() => store.state.currentRoom.id);

// const userHandling = computed();

const props = defineProps({
    post: Object,
});

// const userCompanyId = computed(() => store.state.user?.data?.company?.id);

function apply() {
    store.dispatch("savePost", props.post);
}
</script>

<style lang="scss" scoped></style>
