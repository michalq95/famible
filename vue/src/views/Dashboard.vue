<template>
    <PageComponent title="Dashboard">
        <!-- <template v-slot:header> Dashboard </template> -->
        <button
            class="p-2 items-center text-zinc-700 bg-zinc-300 dark:text-zinc-300 dark:bg-zinc-700"
            @click="refreshUser"
        >
            Refresh
        </button>
        <div
            class="grid grid-cols-1 gap-4 pt-5"
            v-if="pendingAccessess.data.length > 0"
        >
            <h3>Rooms to accept</h3>
            <div
                v-for="acc in pendingAccessess.data"
                :key="acc.id"
                class="flex justify-between items-center py-3 px-5 shadow-md bg-emerald-300 hover:bg-emerald-200 dark:bg-emerald-950 dark:hover:bg-emerald-800 h-[80px]"
            >
                <div class="flex flex-col justify-between items-center">
                    <h4>
                        {{ acc.room.name }}
                    </h4>
                    <h4>
                        {{ acc.room.description }}
                    </h4>
                </div>

                <div class="flex flex-col justify-between items-center">
                    <button
                        type="button"
                        @click="updateRoom(acc, 1)"
                        class="p-2 items-center text-zinc-700 bg-zinc-300 dark:text-zinc-300 dark:bg-zinc-700"
                    >
                        Accept Room
                    </button>
                    <button
                        type="button"
                        @click="updateRoom(acc, 2)"
                        class="p-2 items-center text-zinc-700 bg-zinc-300 dark:text-zinc-300 dark:bg-zinc-700"
                    >
                        Decline Room
                    </button>
                    <button
                        type="button"
                        @click="updateRoom(acc, 3)"
                        class="p-2 items-center text-zinc-700 bg-zinc-300 dark:text-zinc-300 dark:bg-zinc-700"
                    >
                        Block Room
                    </button>
                </div>
            </div>
        </div>
        <div class="relative">
            <div v-for="noti in user.notifications" :key="noti.id">
                <NotificationComponent
                    :notification="noti"
                ></NotificationComponent>
            </div>
            <button
                class="p-2 left-0 items-center absolute bg-green-300 dark:bg-green-700"
                @click="markAllAsRead"
            >
                Mark all as read
            </button>
        </div>
    </PageComponent>
</template>

<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";
import { useStore } from "vuex";
import PageComponent from "../components/PageComponent.vue";
import NotificationComponent from "../components/NotificationComponent.vue";
import { getPendingAccessess, readOne, readAll } from "../service";
import axiosClient from "../axios";

const store = useStore();

const user = computed(() => store.state.user.data);
const pendingAccessess = ref({ data: [] });

onMounted(() => {
    if (user.value) {
        getPendingAccessess().then((data) => {
            pendingAccessess.value = data;
        });
    }
});

function updateRoom(acc, status) {
    console.log(user.value);
    axiosClient
        .put(`/access/${acc.id}`, { user_id: user.value.id, status })
        .then((res) => {
            console.log(res);
            const updatedAccess = res.data;
            const index = pendingAccessess.value.data.findIndex(
                (access) => access.id === updatedAccess.id
            );
            if (index !== -1) {
                pendingAccessess.value.data.splice(index, 1);
            }
        });
    store.dispatch("refreshUser");
}

function refreshUser() {
    store.dispatch("refreshUser");
}

function markAllAsRead() {
    readAll().then((data) => {
        store.dispatch("refreshUser");
    });
}
</script>

<style scoped lang="scss"></style>
