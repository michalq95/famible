<template>
    <div class="h-full w-full">
        <header
            class="md:mb-8 py-4 px-4 bg-slate-400 flex justify-between items-center select-none"
        >
            <div
                class="text-2xl font-semibold flex-none justify-between items-center"
            >
                <router-link :to="{ name: 'Home' }"> Home </router-link>
            </div>
            <div class="flex grow"></div>
            <div v-if="token" class="relative inline-block text-left items-end">
                <div
                    class="inline cursor-pointer bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    @click="roomMenuIsOpen = !roomMenuIsOpen"
                >
                    Rooms
                </div>

                <div
                    v-show="roomMenuIsOpen"
                    class="absolute right-2 z-10 mt-2 w-48 bg-white border rounded-lg shadow-lg"
                >
                    <div
                        v-for="r in acceptedAccesses"
                        class="text-gray-800 pt-1 m-1 truncate"
                    >
                        <button
                            class="p-2 w-10 bg-slate-200 border-neutral-700 hover:bg-gray-200"
                            @click="
                                $router.push({
                                    name: 'RoomView',
                                    params: { id: r.room.id },
                                })
                            "
                        >
                            {{
                                r.room.shorthand
                                    ? r.room.shorthand
                                    : r.room.name.slice(0, 2)
                            }}
                        </button>
                        {{ r.room.name }}

                        <img
                            v-if="r.room_image"
                            :src="r.room_image"
                            class="inline flex-nowrap rounded-sm text-white text-lg font-semibold w-8 h-8"
                        />
                    </div>
                </div>
            </div>

            <div class="flex-none pl-2">
                <div class="text-gray-800 sm:block md:hidden">
                    <div @click="menuIsOpen = !menuIsOpen" v-show="!menuIsOpen">
                        <svg
                            class="fill-current w-8 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"
                            />
                        </svg>
                    </div>

                    <div @click="menuIsOpen = !menuIsOpen" v-show="menuIsOpen">
                        <svg
                            class="fill-current w-8 cursor-pointer"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M10 8.586L2.929 1.515 1.515 2.929 8.586 10l-7.071 7.071 1.414 1.414L10 11.414l7.071 7.071 1.414-1.414L11.414 10l7.071-7.071-1.414-1.414L10 8.586z"
                            />
                        </svg>
                    </div>
                </div>

                <!-- Desktop Links -->
                <div class="hidden md:block text-sm">
                    <div v-if="!token">
                        <router-link
                            :to="{ name: 'Login' }"
                            class="py-2 px-3 ml-2 hover:bg-indigo-100 rounded"
                        >
                            Log In
                        </router-link>

                        <router-link
                            :to="{ name: 'Register' }"
                            class="py-2 px-3 ml-2 hover:bg-indigo-400 bg-indigo-500 rounded shadow-lg border text-white"
                        >
                            Register
                        </router-link>
                    </div>

                    <div v-else>
                        <span class="py-2 px-3 ml-2 rounded">
                            <img
                                :src="user.image"
                                alt=""
                                class="inline w-8 object-cover"
                            />
                            Welcome {{ user.name }}!
                        </span>
                        <router-link
                            class="inline bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            :to="{ name: 'Dashboard' }"
                        >
                            Dashboard
                        </router-link>
                        <router-link
                            class="inline bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            :to="{ name: 'Settings' }"
                        >
                            Settings
                        </router-link>
                        <router-link
                            class="inline bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                            :to="{ name: 'CreateRoom' }"
                        >
                            Create Room
                        </router-link>
                        <span
                            @click="logout"
                            class="py-2 px-3 ml-2 hover:bg-indigo-400 bg-indigo-500 rounded shadow-lg border text-white"
                        >
                            Logout
                        </span>
                    </div>
                </div>
            </div>
        </header>
        <!-- Mobile Links -->

        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="transform opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="transform opacity-0"
        >
            <div
                class="bg-white px-4 py-4 select-none border-b md:hidden duration-200"
                v-if="menuIsOpen"
            >
                <div v-if="!token">
                    <router-link
                        :to="{ name: 'Login' }"
                        class="block mb-2 font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                    >
                        Log In
                    </router-link>

                    <router-link
                        :to="{ name: 'Register' }"
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                    >
                        Register
                    </router-link>
                </div>
                <div v-else>
                    <div
                        class="block font-semibold text-gray-800 py-2 px-3 rounded"
                    >
                        <img
                            :src="user.image"
                            alt=""
                            class="inline w-8 object-cover"
                        />

                        Welcome {{ user.name }}!
                    </div>
                    <router-link
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                        :to="{ name: 'Dashboard' }"
                    >
                        Dashboard
                    </router-link>
                    <router-link
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                        :to="{ name: 'Settings' }"
                    >
                        Settings
                    </router-link>
                    <router-link
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                        :to="{ name: 'CreateRoom' }"
                    >
                        Create Room
                    </router-link>
                    <div
                        @click="logout"
                        class="block font-semibold text-gray-800 py-2 px-3 hover:bg-gray-200 rounded cursor-pointer"
                    >
                        Logout
                    </div>
                </div>
            </div>
        </Transition>

        <div v-if="$store.state.error404">404 not found</div>
        <router-view :key="$route.fullPath" v-else></router-view>
    </div>
</template>

<script setup>
import { useStore } from "vuex";

import { computed, reactive, ref } from "vue";
import { useRouter } from "vue-router";

const menuIsOpen = ref(false);
const roomMenuIsOpen = ref(false);

const store = useStore();
const router = useRouter();
let isMod = computed(() => store.getters.isMod);
const user = computed(() => store.state.user.data);
const acceptedAccesses = computed(() => store.state.user.data.accesses);
const token = computed(() => store.state.user.token);

function logout() {
    store.dispatch("logout").then(() => {
        router.push({
            name: "Home",
        });
    });
}
</script>

<style scoped lang="scss"></style>
