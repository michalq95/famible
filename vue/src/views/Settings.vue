<template>
    <PageComponent title="Settings"> </PageComponent>
    <img v-if="user.image" :src="user.image" class="w-64 object-cover" />

    <img v-else-if="imageTemp" :src="imageTemp" class="w-64 object-cover" />
    <span
        v-else
        class="flex items-center justify-center w-64 object-cover rounded-full overflow-hidden bg-gray-100"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-[80%] w-[80%] text-gray-500"
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
    <button class="p-2 mx-2 bg-blue-500 rounded-2xl" @click="uploadImage()">
        Upload image
    </button>
</template>
<script setup>
import { ref, computed, watch } from "vue";
import { useStore } from "vuex";
import { sendImage } from "../service";

import PageComponent from "../components/PageComponent.vue";

const store = useStore();
const image = ref(null);
const imageTemp = ref(null);
const user = computed(() => store.state.user.data);

function onImageChoose(ev) {
    image.value = ev.target.files[0];
    imageTemp.value = URL.createObjectURL(ev.target.files[0]);
}

function uploadImage() {
    const formData = new FormData();
    formData.append("image", image.value);
    const res = sendImage(formData).then(() => {
        store.dispatch("refreshUser");
    });
}
</script>
