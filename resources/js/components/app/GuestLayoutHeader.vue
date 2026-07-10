<template>
    <header class="w-full z-50 sticky top-0 transition-duration">

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div>
                <Link v-if="isAuthenticating" href="/" class="text-text-color font-light hover:underline text-sm">
                    Back to home
                </Link>
            </div>

            <div v-if="!isAuthenticating" class="flex items-center gap-4 text-sm font-normal text-text-color tracking-wide">
                <Link href="/auth/login" class="text-text-color font-light hover:underline">Login</Link>
                <Link href="/auth/register" class="text-text-color font-light hover:underline">Register</Link>
            </div>
        </div>
    </header>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const isAuthenticating = computed(() => page.url.startsWith('/auth/'));

</script>