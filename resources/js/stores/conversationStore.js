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

    const applyReadUpTo = (conversationId, messageId, markedCount) => {
        const conversation = conversations.value.find(
            (c) => c.id === conversationId,
        );

        if (!conversation) return;

        conversation.unread_count = Math.max(
            0,
            (conversation.unread_count ?? 0) - markedCount,
        );

        if (
            conversation.unread?.firstMessageId &&
            conversation.unread.firstMessageId <= messageId
        ) {
            conversation.unread.count = Math.max(
                0,
                (conversation.unread.count ?? 0) - markedCount,
            );
            conversation.unread.firstMessageId = null;
        }
    };

    const setUnreadCount = (conversationId, count) => {
        const conversation = conversations.value.find(
            (c) => c.id === conversationId,
        );

        if (!conversation) return;

        conversation.unread_count = Math.max(0, count ?? 0);

        if (conversation.unread_count === 0) {
            conversation.unread = {
                count: 0,
                firstMessageId: null,
            };
        }
    };

    const removeConversation = (conversationId) => {
        conversations.value = conversations.value.filter(
            (c) => Number(c.id) !== Number(conversationId),
        );
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
        applyReadUpTo,
        setUnreadCount,
        removeConversation,
        getConversation,
    };
});
