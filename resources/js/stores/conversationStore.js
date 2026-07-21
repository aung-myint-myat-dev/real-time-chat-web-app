import { defineStore } from "pinia";
import { ref } from "vue";

export const useConversationStore = defineStore("conversations", () => {
    const conversations = ref([]);

    const setConversations = (items) => {
        conversations.value = items;
    };

    const addConversation = (conversation) => {
        const exists = conversations.value.find(
            (c) => c.id === conversation.id,
        );

        if (!exists) {
            conversations.value.unshift(conversation);
        }
    };

    return {
        conversations,
        setConversations,
        addConversation,
    };
});
