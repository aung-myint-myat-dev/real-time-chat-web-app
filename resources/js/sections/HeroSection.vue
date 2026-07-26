<template>
    <section class="min-h-screen py-24 flex items-center relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-6">
                <span
                    class="px-4 py-1.5 rounded-full text-xs font-semibold tracking-wide bg-brand-500/10 text-brand-500 border border-brand-500/20 inline-block animate-pulse">
                    Hi! This is
                </span>
                <h1 class="text-5xl md:text-6xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">
                    CHECKCHAT
                </h1>
                <p class="text-xl md:text-2xl font-medium text-slate-600 dark:text-slate-300">
                    Real-time Chat Web Application
                </p>
                <p class="text-slate-500 dark:text-slate-400 max-w-2xl mx-auto leading-relaxed">
                    Welcome to CheckChat. A modern real-time messaging platform developed with Laravel, Vue, Inertia.js,
                    and Laravel Echo demonstrating clean architecture and seamless responsiveness.
                </p>
                <div class="flex flex-wrap justify-center gap-4 pt-2">
                    <Link class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-medium transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5" href="/auth/register">Get Started</Link>
                    <a class="px-6 py-3 rounded-xl border border-border-color font-medium transition-all shadow-lg hover:-translate-y-0.5" href="https://github.com/aung-myint-myat-dev/real-time-chat-web-app">
                        Project Repo
                    </a>
                </div>
            </div>

            <!-- Developer Cards Side-by-Side -->
            <p class="text-center text-xs font-semibold uppercase tracking-wider text-slate-400 mb-6">Developed By</p>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto mb-16">
                <DeveloperCard avatar="/images/amm-luffy.jpeg" name="Aung Myint Myat" github="https://github.com/aung-myint-myat-dev" />
                <DeveloperCard avatar="/images/mad-luffy.jpeg" name="Thura Aung" github="https://github.com/MadThura" />
            </div>

            <!-- Tech Stack Pills -->
            <div class="pt-8 border-t border-slate-200/60 dark:border-slate-800/60">
                <p class="text-center text-xs font-semibold uppercase tracking-wider text-slate-400 mb-6">Powered By</p>
                <div class="flex flex-wrap justify-center gap-3">
                    <span v-for="tech in techStack" :key="tech"
                        class="px-4 py-2 rounded-full text-sm font-medium bg-white/50 dark:bg-slate-900/50 border border-slate-200 dark:border-slate-800 text-slate-700 dark:text-slate-300 backdrop-blur-sm hover:border-brand-500/40 transition-colors">
                        {{ tech }}
                    </span>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import DeveloperCard from '../components/app/DeveloperCard.vue';

const techStack = [
    'Laravel', 'Vue 3', 'Inertia', 'Laravel Echo', 'Pusher', 'TailwindCSS', 'Pinia', 'Axios'
]
</script>