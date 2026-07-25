<template>
    <header
        class="sticky top-0 z-50 backdrop-blur-xl bg-white/70 dark:bg-slate-900/60 border-b border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <!-- Logo -->
            <a href="#home"
                class="font-black text-xl tracking-wider text-slate-900 dark:text-slate-100 flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-brand-500 text-white text-sm">💬</span>
                CHECKCHAT
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center space-x-8 text-sm font-medium text-slate-600 dark:text-slate-300">
                <a href="#home" class="hover:text-brand-500 transition-colors">Home</a>
                <a href="#tech" class="hover:text-brand-500 transition-colors">Tech Stack</a>
                <a href="#features" class="hover:text-brand-500 transition-colors">Features</a>
                <a href="#contact" class="hover:text-brand-500 transition-colors">Contact</a>
            </nav>

            <!-- Auth Action Links -->
            <div class="flex items-center space-x-4">
                <Link href="/auth/login"
                    class="text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-brand-500 transition-colors">
                    Login
                </Link>
                <Link href="/auth/register"
                    class="px-4 py-2 rounded-lg bg-brand-500 hover:bg-brand-600 text-white text-sm font-semibold transition-all shadow-md shadow-brand-500/20">
                    Register
                </Link>
            </div>
        </div>
    </header>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>