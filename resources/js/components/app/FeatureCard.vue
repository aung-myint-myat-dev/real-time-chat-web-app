<template>
    <div
        class="p-6 rounded-2xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:border-slate-300 dark:hover:border-slate-700 group">
        <div
            class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
            {{ icon }}
        </div>
        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 mb-2">{{ title }}</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">{{ description }}</p>
    </div>
</template>

<script setup>
defineProps({
    icon: { type: String, required: true },
    title: { type: String, required: true },
    description: { type: String, required: true }
})
</script>