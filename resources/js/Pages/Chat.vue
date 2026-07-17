<script setup>
import { ArrowLeft } from "@lucide/vue";
import ChatLayout from "../layouts/ChatLayout.vue";
import ChatMessage from "../components/app/ChatMessage.vue";
import { ref, inject, onMounted, onUnmounted, computed, nextTick } from "vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { useEcho } from "@laravel/echo-vue";

defineOptions({
    layout: ChatLayout,
});

const props = defineProps({
    conversation: Object,
});

const { handleBackToLists } = inject("BackToListsHandaler");

const page = usePage();

const messages = ref([...(props.conversation?.messages ?? [])]);

const reply_message_id = ref(null);

const type = ref("text");

const authUser = computed(() => page.props.auth.user);
const otherUser = computed(() => {
    if (!props.conversation) return null;

    return props.conversation?.users.find(
        (user) => user.id !== authUser.value.id,
    );
});

const form = useForm({
    conversation_id: props.conversation?.id,
    user_id: authUser.value.id,
    body: "",
    type: type.value,
    reply_message_id: reply_message_id.value ?? null,
});

const messagesContainer = ref(null);
const scrollToBottom = async () => {
    await nextTick();

    if (!messagesContainer.value) return;

    messagesContainer.value.scrollTop =
        messagesContainer.value.scrollHeight;
};

const handleSendMessage = () => {
    form.post("/messages", {
        onSuccess: () => {
            form.reset(),
            scrollToBottom()
        },
    });
};

onMounted(() => {
    if (props.conversation) {
        scrollToBottom();
        Echo.private(`chats.${props.conversation?.id}`).listen(
            ".message.sent",
            (e) => {
                messages.value.push(e);
                scrollToBottom();
            },
        );
    }
});

onUnmounted(() => {
    Echo.leave(`chats.${props.conversation?.id}`);
});
</script>
<template>
    <div
        v-if="props.conversation"
        class="h-screen overflow-hidden flex flex-col"
    >
        <!-- Header: Fixed height -->
        <div
            class="border-b border-border-color h-16 flex items-center px-4 shrink-0"
        >
            <div class="flex items-center gap-4">
                <button class="md:hidden" @click="handleBackToLists">
                    <ArrowLeft />
                </button>
                <div v-if="otherUser.avatar" class="relative size-10 shrink-0">
                    <img
                        :src="otherUser.avatar"
                        class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700"
                    />
                    <span
                        class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"
                    ></span>
                </div>
                <div
                    v-else
                    class="size-10 shrink-0 bg-zinc-200 font-semibold text-text-color dark:bg-zinc-500 rounded-full flex items-center justify-center"
                >
                    PF
                </div>
                <div>
                    <h2 class="font-semibold">{{ otherUser?.name }}</h2>
                    <p class="text-xs text-gray-500">
                        {{ props.conversation?.time }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages: Fills available space and scrolls -->
        <div ref="messagesContainer" class="flex-1 overflow-y-auto p-4">
            <ChatMessage
                v-for="message in messages"
                :key="message.id"
                :message="message"
            ></ChatMessage>
        </div>

        <!-- Input Form: Stays at the bottom -->
        <!-- Input Form -->
        <div
            class="border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 p-4 shrink-0"
        >
            <form
                @submit.prevent="handleSendMessage"
                class="flex items-end gap-3"
            >
                <!-- Textarea -->
                <div class="flex-1">
                    <textarea
                        v-model="form.body"
                        rows="1"
                        placeholder="Type a message..."
                        class="w-full resize-none rounded-2xl border border-slate-300 dark:border-slate-600 bg-slate-100 dark:bg-slate-700 px-4 py-3 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        @keydown.enter.exact.prevent="handleSendMessage"
                        @keydown.shift.enter.stop
                    ></textarea>
                </div>

                <!-- Send Button -->
                <button
                    type="submit"
                    :disabled="!form.body.trim()"
                    class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-600 text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-50"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M22 2L11 13"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M22 2L15 22L11 13L2 9L22 2Z"
                        />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</template>
