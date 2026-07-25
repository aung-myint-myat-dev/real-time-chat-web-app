<template>
    <div
        class="p-6 rounded-2xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800 backdrop-blur-md transition-all duration-300 hover:shadow-xl hover:border-brand-500/30 group">
        <div class="flex items-center justify-between mb-4">
            <h3
                class="text-xl font-bold text-slate-900 dark:text-slate-100 group-hover:text-brand-500 transition-colors">
                {{ name }}</h3>
            <span
                class="text-2xl p-2 rounded-lg bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">{{
                icon }}</span>
        </div>
        <p class="text-sm text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">{{ description }}</p>

        <ul class="space-y-2">
            <li v-for="feature in features" :key="feature"
                class="text-sm text-slate-600 dark:text-slate-300 flex items-center gap-2">
                <span class="text-emerald-500 text-xs">✔</span>
                <span>{{ feature }}</span>
            </li>
        </ul>
    </div>
</template>

<script setup>
defineProps({
    name: { type: String, required: true },
    icon: { type: String, required: true },
    description: { type: String, required: true },
    features: { type: Array, required: true }
})
</script>