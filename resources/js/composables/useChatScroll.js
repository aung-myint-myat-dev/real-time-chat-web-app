import { ref, nextTick } from "vue";

const NEAR_TOP_THRESHOLD = 0.5; // fraction of viewport height
const NEAR_BOTTOM_THRESHOLD = 2; // multiples of viewport height

export function useChatScroll({ onLoadOlder }) {
    const containerRef = ref(null);
    const isAtBottom = ref(true);
    const showScrollButton = ref(false);
    const unreadCount = ref(0);
    const pendingScrollTargetId = ref(null);

    function scrollToMessageId(id, { markAsRead } = {}) {
        const el = document.getElementById(`message-${id}`);
        el?.scrollIntoView({ behavior: "smooth", block: "center" });
        if (markAsRead) markAsRead(id);
    }

    async function scrollToBottom() {
        await nextTick();
        const el = containerRef.value;
        if (!el) return;

        if (pendingScrollTargetId.value) {
            scrollToMessageId(pendingScrollTargetId.value);
            pendingScrollTargetId.value = null;
        } else {
            el.scrollTop = el.scrollHeight;
        }
        showScrollButton.value = false;
        unreadCount.value = 0;
        isAtBottom.value = true;
    }

    function handleScroll() {
        const el = containerRef.value;
        if (!el) return;

        if (el.scrollTop < el.clientHeight * NEAR_TOP_THRESHOLD) {
            onLoadOlder?.();
        }

        const distanceFromBottom =
            el.scrollHeight - el.clientHeight - el.scrollTop;
        isAtBottom.value =
            distanceFromBottom <= el.clientHeight * NEAR_BOTTOM_THRESHOLD;
        showScrollButton.value = !isAtBottom.value;
    }

    return {
        containerRef,
        isAtBottom,
        showScrollButton,
        unreadCount,
        pendingScrollTargetId,
        scrollToBottom,
        scrollToMessageId,
        handleScroll,
    };
}
