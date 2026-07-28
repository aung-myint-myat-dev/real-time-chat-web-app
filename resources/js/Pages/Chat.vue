<script setup>
import { ArrowLeft, ArrowDown } from "@lucide/vue";
import ChatLayout from "../layouts/ChatLayout.vue";
import ChatMessage from "../components/app/ChatMessage.vue";
import {
    ref,
    inject,
    onMounted,
    onUnmounted,
    computed,
    nextTick,
    watch,
} from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { InfiniteScroll } from "@inertiajs/vue3";
import WhenVisibleWrapper from "../components/WhenVisibleWrapper.vue";
import { useOnlineUsersStore } from "../stores/onlineUsersStore.js";
import axios from "axios";

defineOptions({
    layout: ChatLayout,
});

const props = defineProps({
    conversation: Object,
    messages: Object, // Laravel paginator wrapped with Inertia::scroll()
});

const page = usePage();

const { handleBackToLists } = inject("BackToListsHandaler");

const authUser = computed(() => page.props.auth.user);

const otherUser = computed(() => {
    if (!props.conversation) return null;

    return props.conversation.users.find(
        (user) => user.id !== authUser.value.id
    );
});


const realtimeMessages = ref([]);
// const messages = computed(() => [
//     // ...props.messages.data,
//     ...realtimeMessages.value
// ]);
const messages = ref([]);

const reply_message_id = ref(null);
const type = ref("text");
const message = ref("");

const isOtherUserTyping = ref(false);
const isOtherUserTypingTimer = ref(null);

// const messagesContainer = ref(null);

const isAtBottom = ref(false);
const showScrollButton = ref(false);
const unReadMsgCount = ref(0);


const messagesContainer = ref(null);

const handleScroll = () => {
    const el = messagesContainer.value;
    const threshold = 50;
    isAtBottom.value =
        el.scrollHeight - el.scrollTop - el.clientHeight < threshold;
};

const scrollToBottom = async () => {
    await nextTick();
    if (!messagesContainer.value) return;
    messagesContainer.value.scrollTop = messagesContainer.value.scrollHeight;
    showScrollButton.value = false;
    unReadMsgCount.value = 0;
};


// watch(
//     () => props.conversation?.id,
//     async () => {
//         await nextTick();
//         scrollToBottom();
//     }
// );

const previousHeight = ref(0);

// const handleScroll = (e) => {
//     const el = e.target;

//     if (el.scrollTop < 100) {
//         previousHeight.value = el.scrollHeight;
//     }
// };

watch(
    () => messages.value.length,
    async () => {
        await nextTick();

        const el = messagesContainer.value;

        if (!el) return;

        if (previousHeight.value) {
            el.scrollTop = el.scrollHeight - previousHeight.value;
            previousHeight.value = 0;
        }
    }
);

// const handleScroll = () => {

//     const el = messagesContainer.value;

//     if (!el) return;


//     const distance =
//         el.scrollHeight -
//         el.scrollTop -
//         el.clientHeight;


//     isAtBottom.value = distance < 50;

//     if (isAtBottom.value) {
//         showScrollButton.value = false;
//     }
// };

const firstUnreadMessageId = ref(null);

const bottomAnchor = ref(null);

// const scrollToBottom = async () => {
//     await nextTick();

//         // bottomAnchor.value.scrollIntoView({
//         //     behavior: "smooth",
//         //     block: "end",
//         // });

//     const el = messagesContainer.value;

//     console.log(el.clientHeight, el.scrollHeight, el.scrollTop);

// }


const latestMessageId = 255;
// const scrollToBottom = async (latestMessageId) => {
//     await nextTick();

//     requestAnimationFrame(() => {
//         const el = messagesContainer.value;

//         if (!el) return;

//         const element = document.getElementById(
//             `message-${latestMessageId}`
//         );

//         if (element) {
//             element.scrollIntoView({
//                 behavior: "smooth",
//                 block: "end",
//             });
//         } else {
//             // fallback: scroll container to bottom
//             el.scrollTop = el.scrollHeight;
//         }
//     });
// };

const markConversationAsRead = (messageId = null) => {
    if (!props.conversation?.id) return;

    axios.post(`/chats/${props.conversation.id}/read`, {
        message_id: messageId,
    });
};

const handleSendMessage = async () => {
    try {
        await axios.post("/messages", {
            conversation_id: props.conversation.id,
            user_id: authUser.value.id,
            body: message.value,
            type: type.value,
            reply_message_id: reply_message_id.value,
        });

        message.value = "";
    } catch (error) {
        console.log(error);
    }
};

const sendTypingEvent = () => {
    Echo.private(`chats.${props.conversation.id}`).whisper("typing", {
        user_id: authUser.value.id,
    });
};


const onlineUsersStore = useOnlineUsersStore();

const getMessages = async () => {
    const response = await axios.get(`/chats/${props.conversation?.id}/messages`);
    messages.value = response.data.reverse();
    console.log("Fetched messages:", messages.value);
};

onMounted(() => {
    // await nextTick();

    // scrollToBottom();
    // isAtBottom.value = true;

    getMessages();
    scrollToBottom();


    Echo.private(`chats.${props.conversation?.id}`)
        .listen(".message.sent", async (e) => {

            realtimeMessages.value.push(e);
            await nextTick();

            // if (isAtBottom.value) {

            //     scrollToBottom();

            // } else {

            //     unReadMsgCount.value++;

            //     if (!firstUnreadMessageId.value) {
            //         firstUnreadMessageId.value = e.id;
            //     }

            //     showScrollButton.value = true;
            // }

        })
        .listenForWhisper("typing", (response) => {
            isOtherUserTyping.value =
                response.user_id === otherUser.value?.id;

            clearTimeout(isOtherUserTypingTimer.value);

            isOtherUserTypingTimer.value = setTimeout(() => {
                isOtherUserTyping.value = false;
            }, 1000);
        });
});

onUnmounted(() => {
    Echo.leave(`chats.${props.conversation?.id}`);
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
                    <img v-if="otherUser.avatar" :src="otherUser.avatar"
                        class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700" />

                    <div v-else
                        class="size-full bg-zinc-500 flex items-center justify-center rounded-full font-bold text-xl text-white">
                        {{ otherUser?.name?.charAt(0).toUpperCase() }}
                    </div>

                    <span v-if="onlineUsersStore.isOnline(otherUser.id)"
                        class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>

                <div>
                    <h2 class="font-semibold">
                        {{ otherUser?.name }}
                    </h2>

                    <p v-if="isOtherUserTyping" class="text-[12px] text-white animate-pulse">
                        {{ otherUser.name }} is typing....
                    </p>

                    <p v-if="!onlineUsersStore.isOnline(otherUser.id)" class="text-xs text-gray-500">
                        {{ otherUser.last_seen_at }}
                    </p>
                </div>

            </div>
        </div>


        <!-- Messages: Scroll area -->
        <div class="flex-1 min-h-0 relative overflow-hidden">

            <div ref="messagesContainer" class="h-full border overflow-y-auto p-4" @scroll="handleScroll">

                <ChatMessage v-for="message in messages" :key="message.id" :message="message" />

            </div>


            <!-- Scroll down button -->
            <button v-if="showScrollButton && !isAtBottom" @click="scrollToBottom" class="absolute bottom-6 left-1/2 -translate-x-1/2
                size-10 rounded-full flex items-center justify-center
                bg-gray-500 z-20">

                <span v-if="unReadMsgCount" class="absolute -top-2.5 left-1/2 -translate-x-1/2
                    size-5 rounded-full bg-brand-500 text-xs text-white">
                    {{ unReadMsgCount }}
                </span>

                <ArrowDown />

            </button>

        </div>


        <!-- Input: Fixed bottom -->
        <div class="border-t border-slate-200 dark:border-slate-700
            bg-white dark:bg-slate-800 p-4 shrink-0">

            <form @submit.prevent="handleSendMessage" class="flex items-end gap-3">

                <div class="flex-1">

                    <textarea v-model="message" rows="1" placeholder="Type a message..." class="w-full resize-none rounded-2xl border
                        border-slate-300 dark:border-slate-600
                        bg-slate-100 dark:bg-slate-700
                        px-4 py-3 text-sm text-slate-900
                        dark:text-white placeholder:text-slate-400
                        focus:outline-none focus:ring-2
                        focus:ring-blue-500 focus:border-transparent" @keydown.enter.exact.prevent="handleSendMessage"
                        @keydown.shift.enter.stop @keydown="sendTypingEvent" />

                </div>


                <button type="submit" :disabled="!message.trim()" class="flex h-12 w-12 items-center justify-center
                    rounded-full bg-blue-600 text-white transition
                    hover:bg-blue-700 disabled:cursor-not-allowed
                    disabled:opacity-50">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13" />

                        <path stroke-linecap="round" stroke-linejoin="round" d="M22 2L15 22L11 13L2 9L22 2Z" />
                    </svg>

                </button>

            </form>

        </div>

    </div>
</template>