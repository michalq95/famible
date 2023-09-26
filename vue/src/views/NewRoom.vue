<template>
    <PageComponent v-slot:header title="Create Room"> </PageComponent>
    <form
        @submit.prevent="saveRoom"
        class="justify-between items-center py-3 px-5 shadow-md dark:bg-sky-900"
    >
        <div>
            <label class="block text-sm font-medium text-gray-500">Image</label>
            <div class="mt1 flex items-center">
                <img v-if="image" :src="image" class="w-64 object-cover" />
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
            </div>
        </div>
        <v-text-field
            label="Name"
            v-model="model.name"
            :rules="[
                (v) =>
                    (v || '').length <= 255 ||
                    'Name must be 255 characters or less',
            ]"
            class="text-black"
        ></v-text-field>

        <v-textarea
            label="description"
            :rules="[
                (v) =>
                    (v || '').length <= 2000 ||
                    'Name must be 2000 characters or less',
            ]"
            v-model="model.description"
        ></v-textarea>
        <v-text-field
            label="Shorthand"
            v-model="model.shorthand"
            :rules="[
                (v) =>
                    (v || '').length <= 2 ||
                    'Name must be 2 characters or less',
            ]"
            class="text-black"
        ></v-text-field>
        <!-- <select
            class="p-2 mx-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
            name="status"
            v-model="model.status"
        >
            <option value="0">Pending</option>
            <option value="1">On It</option>
            <option value="2">Complications</option>
            <option value="3">Done</option>
        </select> -->

        <button
            type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Save
        </button>
    </form>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { useRouter } from "vue-router";
import store from "../store";
import PageComponent from "../components/PageComponent.vue";

const router = useRouter();

defineEmits(["close"]);

const model = ref({
    name: "",
    description: "",
    shorthand: "",
    image: null,
});

const image = ref(null);

function onImageChoose(ev) {
    model.value.image = ev.target.files[0];
    image.value = URL.createObjectURL(ev.target.files[0]);
}

function saveRoom() {
    const formData = new FormData();
    for (const field in model.value) {
        formData.append(field, model.value[field]);
    }
    store.dispatch("saveRoom", formData).then((res) => {
        // props.offer.application = res.data;
        router.push({
            name: "Dashboard",
        });
    });
}
</script>
