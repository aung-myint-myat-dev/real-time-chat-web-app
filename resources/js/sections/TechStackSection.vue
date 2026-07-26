<template>
    <section class="min-h-screen py-24 flex items-center bg-slate-100/50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="mb-16">
                <h2 class="text-4xl font-bold mb-3 text-slate-900 dark:text-slate-100">Our Technology Stack</h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-2xl">
                    Engineered using industry-standard tools to provide a seamless, real-time experience with zero page
                    refreshes.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <TechCard v-for="tech in technologies" :key="tech.name" v-bind="tech" />
            </div>
        </div>
    </section>
</template>

<script setup>
import TechCard from '../components/app/TechCard.vue';

const technologies = [
    {
        name: 'Laravel',
        icon: '🔴',
        svg: '/logos/Laravel-Logo.wine.svg',
        description: 'Robust backend framework driving application logic, web sockets broadcasting, and state synchronization.',
        features: ['Authentication', 'Broadcasting']
    },
    {
        name: 'Vue.js 3',
        icon: '🟢',
        svg: '/logos/Vue.js-Logo.wine.svg',
        description: 'Progressive JavaScript framework delivering responsive reactive UI states and real-time interface elements.',
        features: ['Composition API', 'Reactive State']
    },
    {
        name: 'Laravel Echo & Pusher',
        icon: '⚡',
        svg: '/logos/Laravel-Logo.wine.svg',
        description: 'Scalable WebSocket connection hub handling real-time push events and continuous bidirectional messaging.',
        features: ['WebSockets', 'Presence Channels', 'Event Listening']
    },
    {
        name: 'TailwindCSS',
        icon: '🎨',
        svg: '/logos/tailwind-svgrepo-com.svg',
        description: 'Utility-first CSS solution enabling sleek responsive custom layouts, fluid dark-mode, and animations.',
        features: ['Utility First', 'Dark Mode Support', 'Responsive Chat Layout']
    },
    {
        name: 'Inertia.js',
        icon: '🟣',
        svg: '/logos/inertiajs-plain.svg',
        description: 'Monolithic single-page architecture eliminating complex client-side route handlers or API layers.',
        features: ['SPA experience', 'Server-driven Routing']
    },
]
</script>