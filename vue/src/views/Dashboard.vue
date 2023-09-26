<template>
    <PageComponent title="Dashboard">
        <!-- <template v-slot:header> Dashboard </template> -->

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
    </PageComponent>
</template>

<script setup>
import { ref, computed, watchEffect, onMounted, watch } from "vue";
import { useStore } from "vuex";
import PageComponent from "../components/PageComponent.vue";
import { getPendingAccessess } from "../service";
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
    axiosClient
        .put(`/access/${acc.id}`, { user_id: user.id, status })
        .then((res) => {
            const updatedAccess = res.data.data;
            const index = pendingAccessess.value.data.findIndex(
                (access) => access.id === updatedAccess.id
            );
            if (index !== -1) {
                pendingAccessess.value.data.splice(index, 1);
            }
        });

    //load new rooms
}
</script>

<style scoped lang="scss"></style>
