<template>
  <div
    class="h-screen flex flex-col bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-300 overflow-hidden">

    <ChatLayoutHeader/>

    <div class="grow flex relative overflow-hidden">

      <aside
        class="hidden md:flex flex-col w-80 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700/60 shrink-0 transition-colors">
        <slot name="sidebar" />
      </aside>

      <div v-if="isOpen" class="md:hidden fixed inset-0 z-40 flex">
        <div @click="toggleChatSidebar"
          class="fixed inset-0 bg-slate-900/50 dark:bg-slate-950/60 backdrop-blur-xs"></div>

        <aside
          class="relative flex flex-col w-72 max-w-xs bg-white dark:bg-slate-800 h-full shadow-xl animate-slide-in transition-colors">
          <div class="p-4 border-b border-slate-100 dark:border-slate-700/50 flex items-center justify-between">
            <span class="font-bold text-slate-700 dark:text-slate-300">Chats list</span>
            <button @click="toggleChatSidebar"
              class="text-slate-400 dark:text-slate-500 hover:text-slate-600 dark:hover:text-slate-300 cursor-pointer">
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          <div class="grow overflow-y-auto" @click="toggleChatSidebar">
            <slot name="sidebar" />
          </div>
        </aside>
      </div>

      <main class="grow flex flex-col min-w-0 bg-slate-50 dark:bg-slate-900 relative overflow-hidden transition-colors">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { provideTheme } from '../composables/useTheme';
import ChatLayoutHeader from '../components/ChatLayoutHeader.vue';
import { provideSidebar } from '../composables/useSidebar.js';

const { isOpen, toggleChatSidebar} = provideSidebar();

const isMobileSidebarOpen = ref(false);

const { isDarkMode, toggleTheme } = provideTheme();
</script>

<style scoped>
.animate-slide-in {
  animation: slideIn 0.2s ease-out forwards;
}

@keyframes slideIn {
  from {
    transform: translateX(-100%);
  }

  to {
    transform: translateX(0);
  }
}
</style>