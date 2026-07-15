import { defineStore } from "pinia";
import { ref } from "vue";

export const useConversationStore = defineStore('conversations', () => {
    const conversations = ref([]);

    function setConversation(conversations) {
        conversations.value = conversations
    }
    
    return { conversations, setConversation }
})