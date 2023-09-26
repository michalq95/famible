<template>
    <div
        class="fixed top-32 right-0 justify-between items-center py-3 px-5 shadow-md dark:bg-sky-700"
    >
        <div>
            <v-text-field
                clearable
                label="Find Users"
                v-model="query"
                class="text-black"
            ></v-text-field
            ><v-btn @click="searchUsers"> Search </v-btn>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close:search')"
            >
                X
            </button>
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
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
            <div
                v-for="result in queryResult"
                :key="result.id"
                class="flex justify-between items-center py-3 px-5 shadow-md dark:bg-sky-900 dark:hover:bg-sky-700 h-[40px]"
            >
                <img
                    :src="result.image"
                    alt=""
                    class="rounded-full text-white text-lg font-semibold"
                />
                <div>
                    {{ result.name }}
                </div>

                <v-btn @click="sendInvite(result.id)"> Invite </v-btn>
            </div>
        </div>
        <!-- <div>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close:search')"
            >
                X
            </button>
            <button
                class="p-2 mx-2 bg-blue-500 rounded-2xl"
                @click="$emit('close:search')"
            >
                X
            </button>
        </div> -->
    </div>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { findUsers, inviteUser } from "../service";
const emit = defineEmits(["close:search"]);
const props = defineProps({
    roomId: String,
});
const query = ref("");
const queryResult = ref([]);
const role = ref(2);
const nextPage = ref("");
const prevPage = ref("");

function searchUsers() {
    if (query.value) {
        findUsers({ keyword: query.value }).then((res) => {
            queryResult.value = res.data;
            if (res.links.next) nextPage.value = res.links.next;
            if (res.links.prev) prevPage.value = res.links.prev;
            query.value = "";
        });
    }
}

function sendInvite(id) {
    inviteUser({ user_id: id, room_id: props.roomId, role: role.value }).then(
        () => {
            queryResult.value = queryResult.value.filter((el) => el.id != id);
        }
    );
}
</script>
