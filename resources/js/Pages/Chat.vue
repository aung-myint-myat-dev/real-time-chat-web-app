<script setup>
import { ArrowLeft, ArrowDown, Send, X, MoreVertical, Trash2 } from "@lucide/vue";
import ChatLayout from "../layouts/ChatLayout.vue";
import ChatMessage from "../components/app/ChatMessage.vue";
import Dropdown from "../components/ui/Dropdown.vue";
import { ref, inject, onUnmounted, computed, watch, nextTick } from "vue";
import { usePage } from "@inertiajs/vue3";
import axios from "axios";
import { useOnlineUsersStore } from "../stores/onlineUsersStore.js";
import { useConversationStore } from "../stores/conversationStore.js";
import { useChatScroll } from "../composables/useChatScroll.js";
import { useTypingIndicator } from "../composables/useTypingIndicator.js";
import { useChatMessages } from "../composables/useChatMessages,js";
import { useMarkAsReadOnView } from "../composables/useMarkAsReadOnView.js";

defineOptions({
    layout: ChatLayout,
});

const props = defineProps({
    conversation: Object,
});

const page = usePage();
const { handleBackToLists } = inject("BackToListsHandaler");
const { requestDeleteConversation } = inject("DeleteConversation");

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
const messagesContainer = ref(null);
const observeRead = ref(false);
const openingFirstUnreadId = ref(null);

const {
    messages,
    hasNewer,
    firstUnreadMessageId,
    getMessages,
    loadOlderMessages,
    loadNewerMessages,
    appendIfNew,
    markReadUpTo,
    applySeen,
    updateMessage,
    deleteMessage,
} = useChatMessages({ getContainer: () => messagesContainer.value });

const message = ref("");
const messageInput = ref(null);
const messageType = ref("text");
const replyMessageId = ref(null);
const sendError = ref(null);
const isEditing = ref(false);
const editingMessageId = ref(null);

const conversationId = computed(() => props.conversation?.id);

const firstUnreadId = computed(() => {
    const id = openingFirstUnreadId.value;
    if (!id) return null;

    const message = messages.value.find((msg) => msg.id === id);
    if (message?.is_read) return null;

    return id;
});

const {
    isAtBottom,
    showScrollButton,
    unreadCount,
    pendingScrollTargetId,
    scrollToBottom: scrollContainerToBottom,
    scrollToMessageId,
    handleScroll,
} = useChatScroll({
    containerRef: messagesContainer,
    onLoadOlder: loadOlderMessages,
    onLoadNewer: loadNewerMessages,
    hasNewer,
});

const { markUpTo, onMessageViewed } = useMarkAsReadOnView({
    conversationId,
    onReadUpTo(maxId) {
        const marked = markReadUpTo(maxId, authUser.value.id);
        conversationStore.applyReadUpTo(conversationId.value, maxId, marked);
    },
    onUnreadCount(count) {
        conversationStore.setUnreadCount(conversationId.value, count);
    },
});

async function scrollToBottom() {
    if (hasNewer.value && conversationId.value) {
        openingFirstUnreadId.value = null;
        await getMessages(conversationId.value);
        await nextTick();
    }

    await scrollContainerToBottom();
    const lastMessage = messages.value[messages.value.length - 1];
    if (lastMessage) {
        markUpTo(lastMessage.id);
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
            markUpTo(e.id);
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

function handleMessagesRead(e) {
    if (e.reader_id === authUser.value.id) {
        return;
    }

    applySeen(e.message_ids, e.read_at);
}

function subscribeToConversation(conversationId) {
    unsubscribeFromConversation();

    subscribedConversationId = conversationId;
    Echo.private(`chats.${conversationId}`)
        .listen(".message.sent", handleIncomingMessage)
        .listen(".message.deleted", (e) => deleteMessage(e.id))
        .listen(".message.edited", (e) => updateMessage(e.id, e.body, e.edited_at))
        .listen(".messages.read", handleMessagesRead)
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
    async (id) => {
        if (!id) return;

        observeRead.value = false;
        openingFirstUnreadId.value = null;
        subscribeToConversation(id);

        const currentConversation = conversationStore.getConversation(id);
        const hasUnread =
            (currentConversation?.unread_count ?? 0) > 0
            || (currentConversation?.unread?.count ?? 0) > 0
            || Boolean(currentConversation?.first_unread_message_id)
            || Boolean(currentConversation?.unread?.firstMessageId);

        await getMessages(id, { fromUnread: hasUnread });
        await nextTick();

        const targetId =
            currentConversation?.unread?.firstMessageId
            ?? currentConversation?.first_unread_message_id
            ?? firstUnreadMessageId.value;

        openingFirstUnreadId.value = hasUnread ? targetId : null;
        await nextTick();

        if (hasUnread && targetId) {
            scrollToMessageId(targetId);
        } else {
            await scrollContainerToBottom();
        }

        await nextTick();
        observeRead.value = true;
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
        <div class="border-b border-border-color h-16 flex items-center justify-between px-4 shrink-0">
            <div class="flex items-center gap-4 min-w-0">

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

            <Dropdown menu-class="right-0 top-full">
                <template #trigger>
                    <span class="flex items-center justify-center w-9 h-9 rounded-full text-slate-400 hover:text-slate-600 dark:text-slate-500 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors"
                        aria-label="Chat options">
                        <MoreVertical :size="18" />
                    </span>
                </template>
                <button type="button" @click="requestDeleteConversation(props.conversation)" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors">
                    <Trash2 :size="14" />
                    <span>Delete</span>
                </button>
            </Dropdown>
        </div>


        <!-- Messages: Scroll area -->
        <div class="flex-1 min-h-0 relative overflow-hidden">

            <div ref="messagesContainer" class="h-full overflow-y-auto p-4" @scroll="handleScroll">
                <template v-for="msg in messages" :key="msg.id">
                    <div
                        v-if="msg.id === firstUnreadId"
                        :id="`unread-start-${msg.id}`"
                        class="flex items-center gap-3 my-3 px-2"
                    >
                        <div class="flex-1 h-px bg-blue-500/30"></div>
                        <span class="text-[11px] font-semibold text-blue-500 uppercase tracking-wide">Unread</span>
                        <div class="flex-1 h-px bg-blue-500/30"></div>
                    </div>
                    <ChatMessage
                        :message="msg"
                        :observer-root="messagesContainer"
                        :observe-read="observeRead"
                        @delete="handleDeleteMessage($event)"
                        @edit="handleEditMessage($event)"
                        @viewed="onMessageViewed"
                    />
                </template>
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