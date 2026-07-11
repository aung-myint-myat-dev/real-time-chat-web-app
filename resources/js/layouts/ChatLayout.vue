<script setup>
import { Link } from '@inertiajs/vue3';
import ChatLayoutSidebar from '../components/app/ChatLayoutSidebar.vue';
import { computed, provide, ref } from 'vue';
import { provideTheme } from '../composables/useTheme.js';
import { MessageCircleWarningIcon } from '@lucide/vue';

provideTheme();

defineProps({
  chats: Array,
  activeChatId: String,
  searchUsers: Array
})

const selectedChatId = ref(null);
const currentView = ref('lists');

const handleSelectedChatId = (id) => {
  selectedChatId.value = id;
  currentView.value = 'chat';
}

const handleBackToLists = () => {
  currentView.value = 'lists';
  selectedChatId.value = null;
}

provide('BackToListsHandaler', { handleBackToLists });

</script>

<template>
  <div class="h-screen font-brand flex bg-background text-text-color">

    <!-- Layout Sidebar -->
    <div :class="[
      currentView === 'lists' ? 'block' : 'hidden sm:block',
      'w-full sm:w-sm'
    ]">
      <ChatLayoutSidebar :chats="chats" :active-chat-id="selectedChatId" :searchUsers="searchUsers" @select-chat="handleSelectedChatId($event)" />
    </div>

    <!-- Main Content -->
    <div v-if="selectedChatId" :class="[
      currentView === 'chat' ? 'block' : 'hidden sm:block',
      'flex-1',
    ]">
      <slot />
    </div>

    <div v-else :class="[
      'flex-1 items-center justify-center',
      currentView === 'chat' ? 'flex' : 'hidden sm:flex'
    ]">
      <div class="flex flex-col justify-center items-center gap-2">
        <div class="h-14 w-14 text-white bg-brand-500 rounded-full flex items-center justify-center">
          <MessageCircleWarningIcon/>
        </div>
        <h2 class="font-bold text-gray-500">No messages yet
        </h2>
        <p class="text-gray-500">Start a conversation with your friends and connect with them instantly.</p>
      </div>
    </div>
  </div>
</template>