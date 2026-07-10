<script setup>
import { ArrowLeft } from '@lucide/vue';
import ChatLayout from '../layouts/ChatLayout.vue';
import { inject } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useTheme } from '../composables/useTheme.js';

defineOptions({
    layout: ChatLayout,
})

const { handleBackToLists } = inject('BackToListsHandaler')
const { isDarkMode, toggleTheme } = useTheme();
defineProps({
    chat: Object,
})

</script>
<template>
    <div>
        <div class="border-b border-border-color h-16 flex items-center px-4">
            <div class="flex items-center gap-4">
                <button class="sm:hidden" @click="handleBackToLists">
                    <ArrowLeft/>
                </button>
                <div class="relative size-10 shrink-0">
                    <img :src="chat.avatar"
                        class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700">
                    <span
                        class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>
                <div>
                    <h2 class="font-semibold">{{ chat.name }}</h2>
                    <p class="text-xs text-gray-500">{{ chat.time }}</p>
                </div>
                <button @click="toggleTheme" class="bg-brand-500 rounded-full px-4 py-2">Mode switch</button>
            </div>
        </div>
    </div>
</template>