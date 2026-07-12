<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import ChatLayoutSidebar from '../components/app/ChatLayoutSidebar.vue';
import { computed, onMounted, provide, ref, watch } from 'vue';
import { provideTheme } from '../composables/useTheme.js';
import { ArrowLeft, MessageCircleWarningIcon } from '@lucide/vue';
import { useChatStore } from '../stores/chatStore.js';
import Button from '../components/ui/Button.vue';

provideTheme();

const page = usePage();
const chatStore = useChatStore();
const chatLists = computed(() => page.props.chats);
const activeChatId = computed(() => page.props?.activeChatId || null);

watch(chatLists, (newChats) => {
  if (newChats) {
    chatStore.setChats(newChats);
  }
}, { immediate: true });

const selectedChatId = ref(null);
const currentView = ref('lists');
const selectedSearchUser = ref(null);

const handleSelectedChatId = (id) => {
  selectedChatId.value = id;
  currentView.value = 'chat';
}

const handleBackToLists = () => {
  router.get('/chat');
  currentView.value = 'lists';
  selectedChatId.value = null;
  selectedSearchUser.value = null;
}

const handleSelectedSearchedUser = (user) => {
  router.get('/chat');
  currentView.value = 'chat';
  selectedChatId.value = null;
  selectedSearchUser.value = user;
  console.log(user.name)
}

provide('BackToListsHandaler', { handleBackToLists });

onMounted(() => {
  if (activeChatId) {
    // console.log(activeChatId.value)
    selectedChatId.value = activeChatId.value;
    currentView.value = 'chat';
  }
})

</script>

<template>
  <div class="h-screen font-brand flex bg-background text-text-color">

    <!-- Layout Sidebar -->
    <div :class="[
      currentView === 'lists' ? 'block' : 'hidden md:block',
      'w-full md:w-sm'
    ]">
      <ChatLayoutSidebar :chats="chatStore.chatLists" :selected-chat-id="selectedChatId"
        @when-select-a-chat-list="handleSelectedChatId($event)"
        @when-select-a-searched-user="handleSelectedSearchedUser($event)" />
    </div>

    <!-- Main Content -->
    <div v-if="selectedChatId" :class="[
      currentView === 'chat' ? 'block' : 'hidden md:block',
      'flex-1',
    ]">
      <slot />
    </div>

    <!-- Searched User -->
    <div v-else-if="selectedSearchUser" :class="[
      currentView === 'chat' ? 'block' : 'hidden md:block',
      'flex-1 flex items-center justify-center',
    ]">
      <Button class="md:hidden fixed top-4 left-4" @click="handleBackToLists">
        <ArrowLeft size="20"/>
      </Button>
      <div
        class="flex flex-col items-center text-center gap-5 rounded-2xl bg-white dark:bg-zinc-900 py-10 px-6 w-full max-w-sm transition-all duration-300">

        <div class="relative group">
          <div
            class="size-16 shrink-0 bg-zinc-100 dark:bg-zinc-800 font-bold text-lg text-zinc-700 dark:text-zinc-300 rounded-full flex items-center justify-center border-2 border-zinc-200 dark:border-zinc-700 shadow-xs">
            PF
          </div>
          <span
            class="absolute bottom-0 right-0 size-3.5 bg-green-500 border-2 border-white dark:border-zinc-900 rounded-full shadow-sm"></span>
        </div>

        <div class="flex flex-col gap-1">
          <h2 class="text-zinc-900 dark:text-zinc-100 font-extrabold text-xl tracking-tight">
            {{ selectedSearchUser.name }}
          </h2>
          <p class="text-xs text-zinc-400 dark:text-zinc-500">@{{selectedSearchUser.username}}</p>
        </div>

        <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed px-2">
          This is a really interesting way to start a conversation with
          <span class="font-semibold text-zinc-800 dark:text-zinc-200">{{ selectedSearchUser.name }}</span>.
        </p>

        <div class="w-full mt-2">
          <Button
            class="w-full py-2.5 px-4 bg-brand-500 hover:bg-brand-600 text-white font-medium rounded-xl shadow-xs transition-colors duration-200">
            Start a conversation
          </Button>
        </div>
      </div>
    </div>

    <!-- No chats display -->
    <div v-else :class="[
      'flex-1 items-center justify-center',
      currentView === 'chat' ? 'flex' : 'hidden md:flex'
    ]">
      <div class="flex flex-col justify-center items-center gap-2">
        <div class="h-14 w-14 text-white bg-brand-500 rounded-full flex items-center justify-center">
          <MessageCircleWarningIcon />
        </div>
        <h2 class="font-bold text-gray-500">No messages yet
        </h2>
        <p class="text-gray-500 w-full text-center">Start a conversation with your friends and connect with them
          instantly.</p>
      </div>
    </div>
  </div>
</template>