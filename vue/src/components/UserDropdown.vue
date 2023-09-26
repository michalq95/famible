<template>
    <div
        @click.stop
        class="absolute justify-between items-center py-3 px-5 shadow-md dark:bg-sky-700"
    >
        <button
            class="p-2 block mx-2 bg-blue-500 rounded-2xl"
            @click="$emit('close:dropdown')"
        >
            X
        </button>
        {{ user.name }}
        <div class="block">
            <select
                class="p-2 mx-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
                name="role"
                v-model="role"
            >
                <option value="0">Admin</option>
                <option value="1">Mod</option>
                <option value="2">Member</option>
                <option value="3">Guest</option>
            </select>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close:dropdown')"
            >
                Change Role
            </button>
        </div>
        <div>
            <select
                class="p-2 mx-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
                name="status"
                v-model="status"
            >
                <option value="1">Accepted</option>
                <option value="3">Blocked</option>
            </select>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close:dropdown')"
            >
                Change Status
            </button>
        </div>
    </div>
</template>
<script setup>
import { ref, computed, watch } from "vue";

const props = defineProps({
    user: Object,
});

const emit = defineEmits(["close:dropdown"]);

const role = ref(props.user.role);
const status = ref(props.user.status);
</script>
