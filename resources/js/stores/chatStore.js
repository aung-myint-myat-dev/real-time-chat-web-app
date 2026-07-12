import { defineStore } from "pinia";
import { ref } from "vue";

export const useChatStore = defineStore('chatlists', () => {
    const chatLists = ref([]);

    function setChats(chats) {
        chatLists.value = chats
    }
    
    return { chatLists, setChats }
})