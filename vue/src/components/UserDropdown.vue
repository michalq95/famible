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

        <select
            class="p-2 mx-2 w-36 text-black rounded-md bg-slate-200 border-neutral-700 hover:bg-gray-200"
            name="status"
            v-model="status"
        >
            <option value="1">Accepted</option>
            <option value="3">Blocked</option>
        </select>

        <button class="p-2 mx-2 bg-blue-500 rounded-2xl" @click="confirm">
            Change
        </button>
    </div>
</template>
<script setup>
import { useStore } from "vuex";
import { useRouter } from "vue-router";
import { ref, computed, watch } from "vue";
import { modifyAccess } from "../service";

const store = useStore();
const router = useRouter();

const props = defineProps({
    user: Object,
});

const emit = defineEmits(["close:dropdown"]);

const role = ref(props.user.role);
const status = ref(props.user.status);

function confirm() {
    let data = {
        access_id: props.user.access_id,
        user_id: props.user.id,
        role: role.value,
        status: status.value,
    };
    modifyAccess(data)
        .then((res) => {
            if (res.user_id == store.state.user.data.id) {
                store.dispatch("refreshUser").then(() => {
                    router.push({
                        name: "Dashboard",
                    });
                });
            }
        })
        .catch((err) => {
            store.commit("setError", err.response.data.message);
        });
}
</script>
