<script setup>
import { Moon, Sun } from '@lucide/vue';
import { useTheme } from '../composables/useTheme';
import { useSidebar } from '../composables/useSidebar';
import { Link } from '@inertiajs/vue3';

const { isDarkMode, toggleTheme } = useTheme();
const { isOpen, toggleChatSidebar } = useSidebar();

</script>

<template>
    <header
        class="h-16 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700/60 flex items-center justify-between px-4 sm:px-6 z-20 shrink-0 transition-colors">
        <div class="flex items-center space-x-3">
            <button @click="toggleChatSidebar"
                class="p-2 rounded-md text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 md:hidden focus:outline-none cursor-pointer">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <Link href="/chat" class="flex items-center gap-2.5 group">
            <div
                class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform">
                C
            </div>
            <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">
                Check<span class="text-brand-500">Chat</span>
            </span>
            </Link>
        </div>

        <div class="flex items-center space-x-4">
            <!-- <button @click="toggleTheme"
                class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition-colors cursor-pointer"
                title="Toggle Theme">
                <Sun v-if="isDarkMode" size="20"/>
                <Moon v-else size="20"/>
            </button> -->

            <div class="flex items-center space-x-2">
                <div
                    class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold shadow-sm">
                    TU
                </div>
                <span class="hidden sm:inline text-sm font-medium text-slate-700 dark:text-slate-300">
                    Test User
                </span>
            </div>

            <!-- <Link href="/logout" method="post" as="button"
                class="text-xs font-semibold text-slate-500 dark:text-slate-400 hover:text-red-600 dark:hover:text-rose-400 border border-slate-200 dark:border-slate-700 hover:border-red-200 dark:hover:border-rose-500/30 px-3 py-1.5 rounded-md transition bg-white dark:bg-slate-800 cursor-pointer">
            Logout
            </Link> -->
        </div>
    </header>
</template>