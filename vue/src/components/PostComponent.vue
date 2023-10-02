<template>
    <div class="dark:bg-sky-900 bg-sky-100 py-3 px-5 shadow-md">
        <div
            @click="showAll = !showAll"
            class="flex justify-between items-center bg-sky-200 hover:bg-sky-300 dark:bg-sky-800 dark:hover:bg-sky-700 h-[80px] p-2"
        >
            <label
                @click.stop
                class="cursor-pointer flex h-12 w-12 shrink-0"
                for="file-input"
            >
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

            <div class="flex flex-col flex-grow p-2">
                <h3 class="font-bold text-lg">
                    {{ post.title }}
                </h3>
                <h5 v-if="!showAll" class="truncate">{{ post.description }}</h5>
            </div>
            <div class="flex flex-col justify-between p-2">
                <div v-if="userHandling">
                    {{ userHandling.name }}
                </div>
                <div>{{ postStatus.name }}</div>
            </div>
        </div>
        <Transition
            enter-active-class="duration-300 ease-out transform transition-transform"
            enter-from-class="-translate-y-16 opacity-0"
            enter-to-class="translate-y-0 opacity-100"
            leave-from-class="translate-y-0 opacity-100"
            leave-to-class="-translate-y-16 opacity-0"
        >
            <div v-if="showAll">
                <div class="w-full">
                    <img
                        v-if="post.image"
                        :src="post.image"
                        class="relative max-h-[250px] object-contain rounded-sm left-0"
                    />
                </div>
                <v-textarea v-model="post.description"></v-textarea>

                <div>
                    <div class="inline-block text-sm my-2">
                        <div>Created: {{ post.created_at }}</div>
                        <div>Last Updated: {{ post.updated_at }}</div>
                    </div>
                    <div class="inline-block p-2">
                        <select
                            class="w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
                            name="user_handling"
                            v-model="post.user_handling"
                        >
                            <option :value="null">...</option>
                            <option
                                v-for="user in users"
                                :key="user.id"
                                :value="user.id"
                            >
                                {{ user.name }}
                            </option>
                        </select>
                        <select
                            class="w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
                            name="status"
                            v-model="post.status"
                        >
                            <option
                                v-for="status in commonTaskStatuses"
                                :key="status.value"
                                :value="status.value"
                            >
                                {{ status.name }}
                            </option>
                            <!-- <option value="0">Pending</option>
                    <option value="1">On It</option>
                    <option value="2">Complications</option>
                    <option value="3">Done</option> -->
                        </select>
                        <button
                            class="p-2 w-36 bg-slate-300 text-black rounded-md border-neutral-700 hover:bg-gray-200"
                            @click="apply"
                        >
                            Apply
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<script setup>
import store from "../store";
import { computed, ref } from "vue";

const users = computed(() => store.state.currentRoom.data.users);
const roomId = computed(() => store.state.currentRoom.id);
const commonTaskStatuses = computed(() => store.getters.commonTaskStatuses);
const userHandling = computed(() =>
    users.value.find((user) => user.id === props.post.user_handling)
);
const postStatus = computed(() =>
    commonTaskStatuses.value.find(
        (status) => status.value === props.post.status
    )
);

const props = defineProps({
    post: Object,
});

const showAll = ref(false);
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
