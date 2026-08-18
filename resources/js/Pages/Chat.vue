<script setup>
import { ArrowLeft, ArrowDown, Send, X } from "@lucide/vue";
import ChatLayout from "../layouts/ChatLayout.vue";
import ChatMessage from "../components/app/ChatMessage.vue";
import { ref, inject, onUnmounted, computed, watch, onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import { useOnlineUsersStore } from "../stores/onlineUsersStore.js";
import { useConversationStore } from "../stores/conversationStore.js";
import { useChatScroll } from "../composables/useChatScroll.js";
import { useTypingIndicator } from "../composables/useTypingIndicator.js";
import { useChatMessages } from "../composables/useChatMessages,js";

defineOptions({
    layout: ChatLayout,
});

const props = defineProps({
    conversation: Object,
});

const page = usePage();
const { handleBackToLists } = inject("BackToListsHandaler");

const onlineUsersStore = useOnlineUsersStore();
const conversationStore = useConversationStore();

const authUser = computed(() => page.props.auth.user);

const otherUser = computed(() => {
    if (!props.conversation) return null;
    return props.conversation.users.find((user) => user.id !== authUser.value.id);
});

// ---------------------------------------------------------------------------
// Messages (fetch, pagination, dedupe)
// ---------------------------------------------------------------------------
const { messages, nextPageUrl, getMessages, loadOlderMessages, appendIfNew, updateMessage, deleteMessage } =
    useChatMessages();

const message = ref("");
const messageInput = ref(null);
const messageType = ref("text");
const replyMessageId = ref(null);
const sendError = ref(null);
const isEditing = ref(false);
const editingMessageId = ref(null);

async function markMessageAsRead(messageId) {
    try {
        await axios.post(`/messages/${messageId}/mark-as-read`);
    } catch (error) {
        console.error("Failed to mark message as read:", error);
    }
}

async function handleSendMessage() {
    if (!message.value.trim()) return;

    const body = message.value;
    sendError.value = null;

    try {
        if(isEditing.value && message.value && editingMessageId.value) {
            const response = await axios.put(`/messages/${editingMessageId.value}`, {
                body: message.value,
            });
            updateMessage(response.data.message.id, response.data.message.body, response.data.message.edited_at);
        } else {
            await axios.post("/messages", {
                conversation_id: props.conversation.id,
                user_id: authUser.value.id,
                body,
                type: messageType.value,
                reply_message_id: replyMessageId.value,
            });
        }
        message.value = "";
        replyMessageId.value = null;
        isEditing.value = false;
        editingMessageId.value = null;
        scrollToBottom();
    } catch (error) {
        console.error("Failed to send message:", error);
        sendError.value = "Message failed to send. Please try again.";
    }
}

// ---------------------------------------------------------------------------
// Scroll (bottom-tracking, unread count, scroll-to-message)
// ---------------------------------------------------------------------------
const {
    containerRef: messagesContainer,
    isAtBottom,
    showScrollButton,
    unreadCount,
    pendingScrollTargetId,
    scrollToBottom,
    scrollToMessageId,
    handleScroll,
} = useChatScroll({ onLoadOlder: loadOlderMessages });

// ---------------------------------------------------------------------------
// Typing indicator
// ---------------------------------------------------------------------------
const { isOtherUserTyping, notifyTyping, handleWhisper } = useTypingIndicator(
    computed(() => `chats.${props.conversation?.id}`),
    computed(() => authUser.value.id),
    computed(() => otherUser.value?.id)
);

// ---------------------------------------------------------------------------
// Realtime channel subscription
// Re-subscribes correctly whenever the conversation changes, instead of
// binding once in onMounted to whatever conversation was active at load.
// ---------------------------------------------------------------------------
let subscribedConversationId = null;

function handleIncomingMessage(e) {
    appendIfNew(e);

    if (isAtBottom.value) {
        if (e.user_id !== authUser.value.id) {
            markMessageAsRead(e.id);
        }
        scrollToBottom();
    } else {
        unreadCount.value++;
        if (e.user_id !== authUser.value.id && !pendingScrollTargetId.value) {
            pendingScrollTargetId.value = e.id;
        }
        showScrollButton.value = true;
    }
}

function subscribeToConversation(conversationId) {
    unsubscribeFromConversation();

    subscribedConversationId = conversationId;
    Echo.private(`chats.${conversationId}`)
        .listen(".message.sent", handleIncomingMessage)
        .listen(".message.deleted", (e) => deleteMessage(e.id))
        .listen(".message.edited", (e) => updateMessage(e.id, e.body, e.edited_at))
        .listenForWhisper("typing", handleWhisper);
}

function unsubscribeFromConversation() {
    if (subscribedConversationId) {
        Echo.leave(`chats.${subscribedConversationId}`);
        subscribedConversationId = null;
    }
}

// ---------------------------------------------------------------------------
// Delete Message
// ---------------------------------------------------------------------------
const handleDeleteMessage = async (e) => {
    try {
        const response = await axios.delete(`/messages/${e}`);
        deleteMessage(e);
    } catch (error) {
        console.log(error);
    }
}

// ---------------------------------------------------------------------------
// Edit Message
// ---------------------------------------------------------------------------
const handleEditMessage = async (e) => {
    messageInput.value.focus();
    isEditing.value = true;
    message.value = e.body;
    editingMessageId.value = e.id;
}

const cancelEditing = () => {
    messageInput.value = null;
    isEditing.value = false;
    message.value = '';
    editingMessageId.value = null;
}

// ---------------------------------------------------------------------------
// React to conversation changes: subscribe, fetch, scroll
// ---------------------------------------------------------------------------
watch(
    () => props.conversation?.id,
    async (conversationId) => {
        if (!conversationId) return;

        subscribeToConversation(conversationId);
        await getMessages(conversationId);

        const currentConversation = conversationStore.getConversation(conversationId);
        const hasUnread = currentConversation?.unread?.count > 0;

        if (hasUnread) {
            console.log(hasUnread);
            scrollToMessageId(currentConversation.unread.firstMessageId);
            conversationStore.clearUnreadMessages(conversationId);
        } else {
            await scrollToBottom();
        }
    },
    { immediate: true }
);

onUnmounted(() => {
    unsubscribeFromConversation();
});
</script>

<template>
    <div v-if="props.conversation" class="h-screen overflow-hidden flex flex-col">

        <!-- Header: Fixed height -->
        <div class="border-b border-border-color h-16 flex items-center px-4 shrink-0">
            <div class="flex items-center gap-4">

                <button class="md:hidden" @click="handleBackToLists">
                    <ArrowLeft />
                </button>

                <div class="relative size-10 shrink-0">
                    <img v-if="otherUser?.avatar" :src="otherUser.avatar"
                        class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700" />

                    <div v-else
                        class="size-full bg-zinc-500 flex items-center justify-center rounded-full font-bold text-xl text-white">
                        {{ otherUser?.name?.charAt(0).toUpperCase() }}
                    </div>

                    <span v-if="otherUser && onlineUsersStore.isOnline(otherUser.id)"
                        class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>

                <div>
                    <h2 class="font-semibold">
                        {{ otherUser?.name }}
                    </h2>

                    <p v-if="isOtherUserTyping" class="text-[12px] text-white animate-pulse">
                        {{ otherUser?.name }} is typing....
                    </p>

                    <p v-else-if="otherUser && !onlineUsersStore.isOnline(otherUser.id)" class="text-xs text-gray-500">
                        {{ otherUser.last_seen_at }}
                    </p>
                </div>

            </div>
        </div>


        <!-- Messages: Scroll area -->
        <div class="flex-1 min-h-0 relative overflow-hidden">

            <div ref="messagesContainer" class="h-full overflow-y-auto p-4" @scroll="handleScroll">

                <ChatMessage v-for="msg in messages" :key="msg.id" :message="msg" @delete="handleDeleteMessage($event)" @edit="handleEditMessage($event)"/>

            </div>

            <!-- Scroll down button -->
            <button v-if="showScrollButton" @click="scrollToBottom" class="absolute bottom-6 left-1/2 -translate-x-1/2
                size-10 rounded-full flex items-center justify-center
                bg-gray-500 z-20">

                <span v-if="unreadCount" class="absolute -top-2.5 left-1/2 -translate-x-1/2
                    size-5 rounded-full bg-brand-500 text-xs text-white">
                    {{ unreadCount }}
                </span>

                <ArrowDown />

            </button>

        </div>


        <!-- Input: Fixed bottom -->
        <div class="border-t border-slate-200 dark:border-slate-700
            bg-white dark:bg-slate-800 p-4 shrink-0">

            <p v-if="sendError" class="text-xs text-red-500 mb-2">
                {{ sendError }}
            </p>

            <form @submit.prevent="handleSendMessage" class="flex items-end gap-3">

                <div class="flex-1">

                    <textarea v-model="message" ref="messageInput" rows="1" placeholder="Type a message..." class="w-full resize-none rounded-2xl border
                        border-slate-300 dark:border-slate-600
                        bg-slate-100 dark:bg-slate-700
                        px-4 py-3 text-sm text-slate-900
                        dark:text-white placeholder:text-slate-400
                        focus:outline-none focus:ring-2
                        focus:ring-blue-500 focus:border-transparent" @keydown.enter.exact.prevent="handleSendMessage"
                        @keydown.shift.enter.stop @keydown="notifyTyping" />

                </div>
                <button @click="cancelEditing" type="button" v-if="isEditing && editingMessageId" class="flex h-12 w-12 items-center justify-center
                    rounded-full bg-red-600 text-white transition
                    hover:bg-red-700 disabled:cursor-not-allowed
                    disabled:opacity-50"> 
                    <X size="20"/>
                </button>
                <button type="submit" :disabled="!message.trim()" class="flex h-12 w-12 items-center justify-center
                    rounded-full bg-blue-600 text-white transition
                    hover:bg-blue-700 disabled:cursor-not-allowed
                    disabled:opacity-50">
                    <Send size="20"/>
                </button>
            </form>

        </div>

    </div>
</template>