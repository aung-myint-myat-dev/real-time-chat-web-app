import { defineStore } from "pinia";
import { ref } from "vue";

export const useConversationStore = defineStore("conversations", () => {
    const conversations = ref([]);

    const setConversations = (items) => {
        conversations.value = (items ?? []).map((conversation) => ({
            unread: {
                count: 0,
                firstMessageId: null,
            },
            ...conversation,
        }));
        // console.log(conversations.value);
    };

    const addConversation = (conversation) => {
        const exists = conversations.value.find(
            (c) => c.id === conversation.id,
        );

        if (!exists) {
            conversations.value.unshift({
                unread: {
                    count: 0,
                    firstMessageId: null,
                },
                ...conversation,
            });
        }
    };

    const updateConversation = (updated) => {
        const index = conversations.value.findIndex((c) => c.id === updated.id);

        if (index === -1) {
            conversations.value.unshift({
                unread: {
                    count: 0,
                    firstMessageId: null,
                },
                ...updated,
            });

            return;
        }

        const merged = {
            ...conversations.value[index],
            ...updated,
        };

        conversations.value.splice(index, 1);
        conversations.value.unshift(merged);
    };

    const addUnreadMessage = (conversationId, messageId) => {
        const conversation = conversations.value.find(
            (c) => c.id === conversationId,
        );

        if (!conversation) return;

        conversation.unread ??= {
            count: 0,
            firstMessageId: null,
        };

        conversation.unread.count++;

        // Keep only the first unread message id
        conversation.unread.firstMessageId ??= messageId;
    };

    const clearUnreadMessages = (conversationId) => {
        const conversation = conversations.value.find(
            (c) => c.id === conversationId,
        );

        if (!conversation) return;

        conversation.unread = {
            count: 0,
            firstMessageId: null,
        };
    };

    const getConversation = (conversationId) => {
        return conversations.value.find((c) => c.id === conversationId);
    };

    return {
        conversations,

        setConversations,
        addConversation,
        updateConversation,

        addUnreadMessage,
        clearUnreadMessages,
        getConversation,
    };
});
