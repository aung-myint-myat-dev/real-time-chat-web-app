import { defineStore } from "pinia";
import { ref } from "vue";

export const useOnlineUsersStore = defineStore("onlineUsersId", () => {
    const onlineUsersId = ref([]);

    const setOnlineUsersId = (id) => {
        onlineUsersId.value = id ?? [];
    }

    const addOnlineUserId = (id) => {
        onlineUsersId.value.push(id);
    } 

    const removeOnlineUserId = (leaveUserId) => {
        onlineUsersId.value = onlineUsersId.value.filter((id) => id !== leaveUserId);
    }

    const isOnline = (id) => {
        return onlineUsersId.value.includes(id);
    }

    return {  
        onlineUsersId,
        setOnlineUsersId,
        addOnlineUserId,
        removeOnlineUserId,
        isOnline,
    };
});
