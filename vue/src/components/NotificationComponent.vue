<template>
    <div
        class="flex justify-between items-center py-1 px-5 shadow-md bg-green-300 hover:bg-green-200 dark:bg-green-950 dark:hover:bg-green-800 h-[80px]"
    >
        <span
            >{{ message }}
            <router-link
                :to="{
                    name: 'RoomView',
                    params: { id: notification.data.room_id },
                }"
            >
                {{
                    acceptedAccesses.find(
                        (acc) => acc.room.id === notification.data.room_id
                    ).room.name
                }}:</router-link
            ></span
        >
        <span class="font-bold">{{ notification.data.title }}</span>
        <button
            class="p-2 items-center bg-green-300 dark:bg-green-700"
            @click="markAsRead(notification.id)"
        >
            Mark as read
        </button>
    </div>
</template>
<script setup>
import store from "../store";
import { computed, ref } from "vue";

const acceptedAccesses = computed(() => store.state.user.data.accesses);

const props = defineProps({
    notification: Object,
});

const message = computed(() => {
    const notificationType = props.notification?.data?.type;

    if (notificationType === "new_post") {
        return "A new post has been created in room ";
    } else if (notificationType === "changed_post") {
        return "A post has been changed in room ";
    } else {
        return "Something happened ";
    }
});

function markAsRead(id) {
    readOne(id).then((data) => {
        store.dispatch("refreshUser");
    });
}
</script>
