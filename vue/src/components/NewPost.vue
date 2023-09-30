<template>
    <div
        class="fixed top-32 justify-between items-center py-3 px-5 shadow-md dark:bg-sky-700"
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
            label="Title"
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
            no-resize
            rows="3"
            label="description"
            v-model="newPostModel.description"
        ></v-textarea>
        <div>
            <div class="mt1 flex items-center">
                <img v-if="image" :src="image" class="w-6 object-scale-down" />
                <span
                    v-else
                    class="flex items-center justify-center object-cover rounded-full overflow-hidden bg-gray-100"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-gray-500"
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
                <v-file-input
                    label="Image input"
                    accept="image/*"
                    @change="onImageChoose"
                ></v-file-input>
            </div>
        </div>
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
// const image = ref(null);

const newPostModel = ref({
    title: "",
    description: "",
    status: 0,
    user_handling: "",
    image: "",
});

function onImageChoose(ev) {
    newPostModel.value.image = ev.target.files[0];
    // image.value = URL.createObjectURL(ev.target.files[0]);
}

function newPost() {
    const formData = new FormData();
    for (const field in newPostModel.value) {
        formData.append(field, newPostModel.value[field]);
    }
    store.dispatch("savePost", { formData });
    newPostModel.value = {
        title: "",
        description: "",
        status: 0,
        user_handling: "",
        image: "",
    };

    emit("close");
}
</script>
