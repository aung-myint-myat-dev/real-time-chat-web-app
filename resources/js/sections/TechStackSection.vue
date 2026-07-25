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
        description: 'Robust backend framework driving application logic, web sockets broadcasting, and state synchronization.',
        features: ['Authentication', 'Broadcasting', 'Queues', 'REST API']
    },
    {
        name: 'Vue.js 3',
        icon: '🟢',
        description: 'Progressive JavaScript framework delivering responsive reactive UI states and real-time interface elements.',
        features: ['Composition API', 'Reactive State', 'Component Driven', 'Fast Virtual DOM']
    },
    {
        name: 'Inertia.js',
        icon: '🟣',
        description: 'Monolithic single-page architecture eliminating complex client-side route handlers or API layers.',
        features: ['SPA experience', 'Server-driven Routing', 'Shared State', 'Zero Build Overhead']
    },
    {
        name: 'Laravel Echo & Pusher',
        icon: '⚡',
        description: 'Scalable WebSocket connection hub handling real-time push events and continuous bidirectional messaging.',
        features: ['WebSockets', 'Presence Channels', 'Event Listening', 'Encrypted Streams']
    },
    {
        name: 'TailwindCSS',
        icon: '🎨',
        description: 'Utility-first CSS solution enabling sleek responsive custom layouts, fluid dark-mode, and animations.',
        features: ['Utility First', 'Dark Mode Native', 'Responsive Grid', 'Clean Footprint']
    }
]
</script>