<template>
    <div
        class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-brand-500 selection:text-white">
        <GuestLayoutHeader v-if="!isAuthenticating" />
        <div v-if="isAuthenticating" class="fixed top-4 left-4">
            <button type="button" @click="$window ? window.history.back() : $inertia.visit('/')"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 dark:text-slate-400 dark:hover:text-white transition-colors group">
                <ArrowLeft size="16" />
                <span>Back</span>
            </button>
        </div>
        <main :class="[
            isAuthenticating && 'min-h-screen flex items-center justify-center',
            'font-brand'
        ]">
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ArrowLeft } from '@lucide/vue';
import GuestLayoutHeader from '../components/app/GuestLayoutHeader.vue';

defineProps({
    isAuthenticating: {
        type: Boolean,
        default: false,
    }
})

</script>