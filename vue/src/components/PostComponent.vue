<template>
    <div
        class="flex justify-between items-center py-3 px-5 shadow-md dark:bg-sky-900 dark:hover:bg-sky-700 h-[80px]"
    >
        <label class="flex h-12 w-12 shrink-0" for="file-input">
            <img
                v-if="post.image"
                :src="post.image"
                class="rounded-sm h-12 !w-12 min-w-12 object-cover"
            />
            <span
                v-else
                class="items-center justify-center h-12 w-12 min-w-12 rounded-full bg-gray-100"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-12 w-12 text-gray-500"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                >
                    <path
                        fill-rule="evenodd"
                        d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                        clip-rule="evenodd"
                    />
                </svg>
            </span>
        </label>
        <v-file-input
            id="file-input"
            accept="image/*"
            @change="onImageChoose"
            style="display: none"
        />
        <h3>
            {{ post.title }}
        </h3>

        <h5>{{ post.description }}</h5>

        {{ image }}

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
import { computed, ref } from "vue";
const users = computed(() => store.state.currentRoom.data.users);
const roomId = computed(() => store.state.currentRoom.id);

const props = defineProps({
    post: Object,
});

const image = ref(null);

function onImageChoose(ev) {
    image.value = ev.target.files[0];
    props.post.image = URL.createObjectURL(ev.target.files[0]);
}

function apply() {
    const formData = new FormData();
    for (const field in props.post) {
        formData.append(field, props.post[field]);
    }
    formData.delete("image");
    console.log(image.value);
    if (image.value) formData.append("image", image.value);
    formData.append("_method", "PUT");
    store.dispatch("savePost", { formData, id: props.post.id });
}
</script>

<style lang="scss" scoped>
img {
    min-width: 100%;
}
</style>
