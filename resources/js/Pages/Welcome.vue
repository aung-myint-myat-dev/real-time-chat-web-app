<template>
  <GuestLayout>
    <div class="max-w-2xl text-center space-y-6 py-12">
      <div
        class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-brand-500/10 text-brand-500 dark:text-brand-400 text-xs font-semibold tracking-wide uppercase">
        🚀 Welcome to CheckChat
      </div>
      <h1 class="text-4xl font-poppin sm:text-5xl font-medium text-slate-900 dark:text-white tracking-tight leading-15 transition-duration">
        Simple and modern <br/> <span class="text-brand-500">real-time</span> chat web app.
      </h1>
      <p class="text-md font-geist-pixel transition-duration tracking-wide text-slate-500 dark:text-slate-400 max-w-xl mx-auto">
        Connect, chat, and keep up with your workspace projects instantly. Experience clean messaging layouts without
        any added clutter.
      </p>
      <div class="pt-4 flex justify-center gap-4">
        <Link href="/auth/register"
          class="px-6 py-3 rounded-lg bg-brand-500 hover:bg-brand-600 text-white font-semibold shadow-lg shadow-brand-500/20 transition-all flex items-center gap-2">
          Get Started
          <ArrowRightCircle size="20" class="animate-pulse"/>
        </Link>
      </div>
    </div>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { Link } from '@inertiajs/vue3';
import { ArrowRight, ArrowRightCircle } from '@lucide/vue';
</script>