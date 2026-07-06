<template>
    <header
        class="w-full border-b border-slate-200/80 dark:border-slate-800 bg-white/70 dark:bg-slate-900/70 backdrop-blur-md z-50 sticky top-0 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

            <!-- App Logo with '/' link -->
            <Link href="/" class="flex items-center gap-2.5 group">
                <div
                    class="w-9 h-9 rounded-xl bg-brand-500 flex items-center justify-center text-white font-bold text-lg shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform">
                    C
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-900 dark:text-white">
                    Check<span class="text-brand-500">Chat</span>
                </span>
            </Link>

            <!-- Nav bar button group ['theme toggle button', 'login button', 'register button'] -->
            <div class="hidden md:flex items-center gap-4 ">
                <button @click="toggleTheme"
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-hidden">
                    <Sun v-if="isDarkMode" size="20" />
                    <Moon v-else size="20" />
                </button>

                <Link href="/auth/login"
                    class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-500 dark:hover:text-brand-400 transition-colors">
                    Login
                </Link>
                <Link href="/auth/register"
                    class="px-4 py-2 text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 rounded-xl shadow-xs transition-all">
                    Register
                </Link>
            </div>

            <!-- Mobile view -->
            <div class="md:hidden space-x-1">
                <button @click="toggleTheme"
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-hidden">
                    <Sun v-if="isDarkMode" size="20" />
                    <Moon v-else size="20" />
                </button>
                <button @click="isMobileMenubarOpen = !isMobileMenubarOpen"
                    class="p-2 rounded-xl text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:outline-hidden">
                    <Menu v-if="!isMobileMenubarOpen" size="20" />
                    <X v-else size="20"/>
                </button>
            </div>
        </div>
    </header>
    <!-- Mobile Nav bar -->
    <div class="flex md:hidden flex-col p-4 bg-white dark:bg-slate-800/80 rounded-b-2xl shadow-xl border border-slate-100 dark:border-slate-800/50 space-y-4 transition duration-300"
        :class="{
            '-translate-y-full opacity-0': !isMobileMenubarOpen,
            'translate-y-0': isMobileMenubarOpen,
        }">
        <Link href="/login"
            class="px-4 py-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
            Login
        </Link>
        <Link href="/register"
            class="px-4 py-2 text-sm font-semibold text-white bg-brand-500 hover:bg-brand-600 rounded-xl shadow-xs transition-all">
            Register
        </Link>
    </div>

</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { useTheme } from '../composables/useTheme';
import { Menu, Moon, Sun, X } from '@lucide/vue';
import { ref } from 'vue';

const isMobileMenubarOpen = ref(false);
const { isDarkMode, toggleTheme } = useTheme();


</script>