<script setup>
import { ArrowLeft } from "@lucide/vue";
import ChatLayout from "../layouts/ChatLayout.vue";
import ChatMessage from "../components/app/ChatMessage.vue";
import { ref, inject, onMounted, computed } from "vue";
import { Link, usePage, useForm } from "@inertiajs/vue3";

const props = defineProps({
    conversation: Object,
});

defineOptions({
    layout: ChatLayout,
});

const { handleBackToLists } = inject("BackToListsHandaler");

const page = usePage();

const authUser = computed(() => page.props.auth.user);

const messages = computed(() => props.conversation?.messages);

const otherUser = computed(() => {
    if (!props.conversation) return null;

    return props.conversation.users.find(
        (user) => user.id !== authUser.value.id,
    );
});

const reply_message_id = ref(null);
const type = ref("text");

const form = useForm({
    conversation_id: props.conversation.id,
    user_id: authUser.value.id,
    body: "",
    type: type.value,
    reply_message_id: reply_message_id.value ?? null,
});

const handleSendMessage = () => {
    form.post("/messages", {
        onSuccess: () => form.reset(),
    });
};
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
        <div class="flex-1 overflow-y-auto p-4">
            <ChatMessage
                v-for="message in messages"
                :key="message.id"
                :message="message"
            ></ChatMessage>
        </div>

        <!-- Input Form: Stays at the bottom -->
        <div
            class="min-h-20 p-4 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700/60 shrink-0 transition-colors duration-200"
        >
            <form @submit.prevent="handleSendMessage">
                <textarea v-model="form.body"></textarea>
                <button type="submit" class="bg-brand-500">Send</button>
            </form>
        </div>
    </div>
</template>
