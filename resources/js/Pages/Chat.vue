<script setup>
import { onMounted, ref } from 'vue';
import ChatLayout from '@/layouts/ChatLayout.vue';
import ChatMessage from '../components/ChatMessage.vue';
import { chatMessages } from './data.js';
import ChatSidebar from '../components/ChatSidebar.vue';
import ChatInput from '../components/ChatInput.vue';
import ChatStarterProfile from '../components/ChatStarterProfile.vue';

const messages = ref([]);

onMounted(() => {
    if (typeof window !== 'undefined') {
        messages.value = chatMessages;
    }
})

</script>

<template>
    <ChatLayout>
        <template #sidebar>
            <ChatSidebar/>
        </template>
        <div class="h-full flex flex-col overflow-hidden">
            <!-- Chat messages -->
            <div class="flex-1 overflow-hidden overflow-y-auto px-4">
                <ChatStarterProfile/>
                <ChatMessage v-for="message in messages" :message="message.message" :key="message.id"
                    :is-me="message.isMe" :sender-name="message.sender" />
            </div>

            <!-- Sent message form -->
            <div>
                <ChatInput />
            </div>
        </div>
    </ChatLayout>
</template>