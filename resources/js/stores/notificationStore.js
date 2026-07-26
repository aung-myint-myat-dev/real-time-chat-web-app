import { defineStore } from "pinia";
import { computed, ref, watch } from "vue";

export const useNotificationStore = defineStore("notifications", () => {
    const notifications = ref([]);

    const count = computed(() => notifications.value.length);

    const add = (noti) => {
        notifications.value.push(noti);
        if (notifications.value.length > 2) {
            notifications.value.shift();
        }
    };

    const remove = (id) => {
        notifications.value = notifications.value.filter((n) => n.id !== id);
    };

    const clear = () => {
        notifications.value = [];
    };

    return {
        notifications,
        count,
        add,
        remove,
        clear,
    };
});
