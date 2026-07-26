<template>
    <section class="min-h-screen py-24 flex items-center">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="mb-16">
                <h2 class="text-4xl font-bold mb-3 text-slate-900 dark:text-slate-100">Our Features</h2>
                <p class="text-slate-500 dark:text-slate-400 max-w-2xl">
                    Everything required for production-ready, interactive real-time communications out-of-the-box.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <FeatureCard v-for="feature in features" :key="feature.title" v-bind="feature" />
            </div>
        </div>
    </section>
</template>

<script setup>
import FeatureCard from '../components/app/FeatureCard.vue';

const features = [
    { icon: '💬', title: 'Real-Time Messaging', description: 'Instant message delivery via WebSocket channels with zero latencies.' },
    { icon: '👥', title: 'Presence Status', description: 'Track active channels and group participant presence automatically.' },
    { icon: '🟢', title: 'Online Indicator', description: 'See real-time user availability status with low-overhead heartbeat checks.' },
    { icon: '🔔', title: 'Notifications', description: 'In-app and broadcast event notifications for incoming direct messages.' },
    { icon: '🔒', title: 'Authentication', description: 'Secured login and register flow powered by Laravel authentication services.' },
    { icon: '📱', title: 'Responsive Design', description: 'Optimized touch-friendly UI adapting seamlessly across mobile, tablet, and desktop.' },
    { icon: '⚡', title: 'Fast SPA Navigation', description: 'Fluid page transitions without full page reloads powered by Inertia.js.' },
]
</script>