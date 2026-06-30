<template>
    <div
        class="flex flex-col items-center text-center py-12 px-4 border-b border-slate-100 dark:border-slate-800/40 bg-linear-to-b from-slate-50/50 to-transparent dark:from-slate-900/20 transition-colors duration-200 shrink-0">

        <div class="relative mb-4 group">
            <div
                class="absolute inset-0 bg-brand-500/10 dark:bg-brand-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300">
            </div>

            <div :class="[
                'w-24 h-24 rounded-full flex items-center justify-center text-3xl font-bold text-white shadow-lg relative z-10 select-none transform group-hover:scale-102 transition-transform duration-200',
                user.avatarBg || 'bg-brand-500'
            ]">
                <img v-if="user.avatarUrl" :src="user.avatarUrl" :alt="user.name"
                    class="w-full h-full rounded-full object-cover" />
                <span v-else>{{ user.name.charAt(0).toUpperCase() }}</span>
            </div>

            <span v-if="user.isOnline"
                class="absolute bottom-1 right-1 block h-5 w-5 rounded-full bg-emerald-500 ring-4 ring-slate-50 dark:ring-slate-900 z-20 animate-pulse"
                title="Online"></span>
        </div>

        <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">
            {{ user.name }}
        </h2>

        <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5 font-medium">
            @{{ user.username || user.name.toLowerCase().replace(/\s+/g, '') }}
        </p>

        <div class="max-w-sm mt-3 space-y-1">
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                This is the very beginning of your direct message history with <span
                    class="font-semibold text-slate-700 dark:text-slate-300">{{ user.name }}</span>.
            </p>
            <p class="text-xs text-slate-400 dark:text-slate-500 italic">
                Say hello to start the conversation! 👋
            </p>
        </div>

    </div>
</template>

<script setup>
// Chat ဖွင့်ထားတဲ့ တစ်ဖက်လူရဲ့ အချက်အလက်တွေကို Parent ဆီကနေ Props နဲ့ လှမ်းယူပါမယ်
defineProps({
    user: {
        type: Object,
        required: false,
        default: () => ({
            id: 1,
            name: 'Kyaw Kyaw',
            username: 'kyawkyaw.dev',
            avatarUrl: null, // တကယ်လို့ image path ရှိရင် ထည့်ပေးလို့ရပါတယ်
            avatarBg: 'bg-indigo-500',
            isOnline: true
        })
    }
})
</script>