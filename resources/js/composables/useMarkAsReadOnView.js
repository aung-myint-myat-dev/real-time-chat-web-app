import { onUnmounted, unref, watch } from "vue";
import axios from "axios";

export function useMarkAsReadOnView({ conversationId, onReadUpTo, onUnreadCount }) {
    let queuedMaxId = 0;
    let highestSentId = 0;
    let timer = null;
    let inFlight = false;

    function markUpTo(messageId) {
        if (typeof document !== "undefined" && document.hidden) {
            return;
        }

        const id = Number(messageId);
        if (!id || id <= highestSentId) {
            return;
        }

        queuedMaxId = Math.max(queuedMaxId, id);
        scheduleFlush();
    }

    function scheduleFlush() {
        clearTimeout(timer);
        timer = setTimeout(() => {
            flush();
        }, 200);
    }

    async function flush() {
        const id = unref(conversationId);
        const maxId = queuedMaxId;

        if (!id || !maxId || maxId <= highestSentId || inFlight) {
            return;
        }

        queuedMaxId = 0;
        highestSentId = maxId;
        inFlight = true;
        onReadUpTo?.(maxId);

        try {
            const { data } = await axios.post(`/chats/${id}/read`, { message_id: maxId });
            if (typeof data?.unread_count === "number") {
                onUnreadCount?.(data.unread_count);
            }
        } catch (error) {
            highestSentId = 0;
            console.error("Failed to mark messages as read:", error);
        } finally {
            inFlight = false;
            if (queuedMaxId > highestSentId) {
                scheduleFlush();
            }
        }
    }

    watch(
        () => unref(conversationId),
        (nextId, previousId) => {
            const pendingId = queuedMaxId;
            clearTimeout(timer);
            queuedMaxId = 0;
            highestSentId = 0;
            inFlight = false;

            if (previousId && pendingId) {
                axios.post(`/chats/${previousId}/read`, { message_id: pendingId }).catch((error) => {
                    console.error("Failed to mark messages as read:", error);
                });
            }
        },
    );

    onUnmounted(() => {
        clearTimeout(timer);
        flush();
    });

    return {
        markUpTo,
        onMessageViewed(message) {
            markUpTo(message?.id);
        },
    };
}
