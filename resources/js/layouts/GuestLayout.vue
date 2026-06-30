<template>
    <div>
        <div
            class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-300 relative overflow-hidden">

            <div class="absolute -top-40 -left-40 w-80 h-80 bg-brand-500/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <div class="absolute top-1/4 -right-40 w-80 h-80 bg-brand-600/10 rounded-full blur-3xl pointer-events-none">
            </div>

            <GuestLayoutHeader/>

            <main class="flex-1 flex flex-col justify-center items-center p-6 z-10">
                <slot />
            </main>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { provideTheme } from '../composables/useTheme';
import GuestLayoutHeader from '../components/GuestLayoutHeader.vue';
const { isDarkMode } = provideTheme();
</script>