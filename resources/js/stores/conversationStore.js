import { defineStore } from "pinia";
import { ref } from "vue";

export const useConversationStore = defineStore("conversations", () => {
    const conversations = ref([]);

    const setConversations = (items) => {
        conversations.value = items ?? [];
    };

    const addConversation = (conversation) => {
        const exists = conversations.value.find(
            (c) => c.id === conversation.id,
        );

        if (!exists) {
            conversations.value.unshift(conversation);
        }
    };

    const updateConversation = (updated) => {
        const index = conversations.value.findIndex(
            (c) => c.id === updated.id,
        );

        if (index === -1) {
            conversations.value.unshift(updated);
            return;
        }

        const merged = {
            ...conversations.value[index],
            ...updated,
        };

        conversations.value.splice(index, 1);
        conversations.value.unshift(merged);
    };

    return {
        conversations,
        setConversations,
        addConversation,
        updateConversation,
    };
});
