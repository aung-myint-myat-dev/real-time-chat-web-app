<script setup>
import { Link } from '@inertiajs/vue3'
import { provideTheme } from '../composables/useTheme'
import { ArrowLeft } from '@lucide/vue';
provideTheme();

defineProps({
    title: {
        type: String,
        default: 'My App'
    },
    backUrl: {
        type: String,
        default: ''
    }
})

const goBack = () => {
    window.history.back()
}
</script>

<template>
    <div class="min-h-screen bg-background flex flex-col text-text-color">
        <header class="sticky top-0 z-50 bg-background border-b border-border-color h-16  flex items-center justify-between px-4 sm:px-8">

            <div class="flex-1">
                <Link v-if="backUrl" :href="backUrl"
                    class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 transition">
                    ← Back
                </Link>
                <button v-else @click="goBack"
                    class="w-8 h-8 rounded-full hover:bg-gray-200 dark:bg-brand-900 dark:hover:bg-brand-800 transition-duration inline-flex items-center justify-center cursor-pointer">
                    <ArrowLeft class="w-4 h-4" stroke-width="2.5"/>
                </button>
            </div>

            <div class="flex-none max-w-xs sm:max-w-md text-center">
                <h1 class="text-lg font-semibold truncate">{{ title }}</h1>
            </div>

            <div class="flex-1"></div>
        </header>

        <main class="flex-1 p-4 max-w-7xl w-full mx-auto">
            <slot />
        </main>
    </div>
</template>