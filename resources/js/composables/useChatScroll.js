import { ref, nextTick, unref } from "vue";

const NEAR_TOP_THRESHOLD = 0.5;
const NEAR_BOTTOM_THRESHOLD = 1;

export function useChatScroll({ containerRef: externalContainerRef, onLoadOlder, onLoadNewer, hasNewer } = {}) {
    const containerRef = externalContainerRef ?? ref(null);
    const isAtBottom = ref(true);
    const showScrollButton = ref(false);
    const unreadCount = ref(0);
    const pendingScrollTargetId = ref(null);

    function scrollToMessageId(id) {
        const container = containerRef.value;
        const el = container?.querySelector(`#message-${id}`)
            ?? document.getElementById(`message-${id}`);

        if (!container || !el) {
            return false;
        }

        const unreadMarker = container.querySelector(`#unread-start-${id}`);
        const target = unreadMarker ?? el;
        const containerRect = container.getBoundingClientRect();
        const targetRect = target.getBoundingClientRect();

        container.scrollTop += targetRect.top - containerRect.top - 8;
        isAtBottom.value = false;
        showScrollButton.value = true;

        return true;
    }

    async function scrollToBottom() {
        await nextTick();
        const el = containerRef.value;
        if (!el) return;

        if (pendingScrollTargetId.value) {
            scrollToMessageId(pendingScrollTargetId.value);
            pendingScrollTargetId.value = null;
            return;
        }

        el.scrollTop = el.scrollHeight;
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

        if (unref(hasNewer) && distanceFromBottom < el.clientHeight * NEAR_BOTTOM_THRESHOLD) {
            onLoadNewer?.();
        }

        isAtBottom.value =
            !unref(hasNewer) && distanceFromBottom <= el.clientHeight * 0.35;
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
