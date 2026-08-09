import { throttle } from "lodash-es";
import { ref, onUnmounted } from "vue";

export function useTypingIndicator(channelName, currentUserId, otherUserId) {
    const isOtherUserTyping = ref(false);
    let timer = null;

    const notifyTyping = throttle(() => {
        Echo.private(channelName.value).whisper("typing", { user_id: currentUserId.value });
    }, 2000);

    function handleWhisper({ user_id }) {
        isOtherUserTyping.value = user_id === otherUserId.value;
        clearTimeout(timer);
        timer = setTimeout(() => {
            isOtherUserTyping.value = false;
        }, 1000);
    }

    onUnmounted(() => clearTimeout(timer));

    return { isOtherUserTyping, notifyTyping, handleWhisper };
}
