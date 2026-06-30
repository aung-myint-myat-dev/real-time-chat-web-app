<template>
  <GuestLayout>
    <div class="max-w-2xl text-center space-y-6 py-12">
      <div
        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/10 text-brand-500 dark:text-brand-400 text-xs font-semibold tracking-wide uppercase">
        🚀 Welcome to CheckChat
      </div>
      <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-none">
        The future of modern real-time team collaboration.
      </h1>
      <p class="text-lg text-slate-500 dark:text-slate-400 max-w-xl mx-auto">
        Connect, chat, and keep up with your workspace projects instantly. Experience clean messaging layouts without
        any added clutter.
      </p>
      <div class="pt-4 flex justify-center gap-4">
        <Link href="/register"
          class="px-6 py-3 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold shadow-lg shadow-brand-500/20 transition-all">
          Get Started Free
        </Link>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { Link } from '@inertiajs/vue3';
</script>