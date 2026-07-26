<template>
    <div
        class="min-h-screen bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased selection:bg-brand-500 selection:text-white">
        <GuestLayoutHeader v-if="!isAuthenticating" />

        <main :class="[
            isAuthenticating && 'min-h-screen flex items-center justify-center px-2',
            'font-brand'
        ]">
            <Link v-if="isAuthenticating" class="fixed top-2 left-2 z-50 px-4 py-1.5 rounded-md bg-brand-500 hover:bg-brand-600 text-white font-medium transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/40 hover:-translate-y-0.5" href="/">
                <ArrowLeft size="16" />
            </Link>
            <slot />
        </main>
    </div>
</template>

<script setup>
import { ArrowLeft } from '@lucide/vue';
import GuestLayoutHeader from '../components/app/GuestLayoutHeader.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    isAuthenticating: {
        type: Boolean,
        default: false,
    }
})

</script>