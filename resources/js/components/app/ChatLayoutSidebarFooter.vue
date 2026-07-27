<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { MessageCircle, Settings, UserCircle } from '@lucide/vue';

const page = usePage();
const links = [
    { id: 1, label: "Chats", icon: MessageCircle, href: "/chat" },
    { id: 4, label: "Profile", icon: UserCircle, href: "/profile" },
];

const isLinkActive = (href) => {
    return page.url.startsWith(href);
};
</script>
<template>
    <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-50">
        <div
            class="bg-backgrounds/80 backdrop-blur-xl border border-slate-200/60 dark:border-slate-800/60 rounded-3xl px-4 py-1.5 flex items-center justify-around shadow-[0_20px_50px_rgba(0,0,0,0.15)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.4)]">
            <Link v-for="link in links" :key="link.id" :href="link.href"
                class="relative flex flex-col items-center justify-center py-2 px-3 rounded-2xl transition-all duration-300 group">
            <div :class="[
                'w-11 h-11 flex items-center justify-center rounded-2xl transition-all duration-300 transform',
                isLinkActive(link.href)
                    ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/30'
                    : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-900',
            ]">
                <component :is="link.icon" :size="20" :stroke-width="isLinkActive(link.href) ? 2.5 : 2" />
            </div>
            <span
                class="absolute -top-12 scale-0 transition-all rounded-xl bg-slate-900/90 dark:bg-slate-800/90 backdrop-blur-sm px-2.5 py-1.5 text-xs text-white group-hover:scale-100 font-medium whitespace-nowrap shadow-md pointer-events-none border border-slate-700/30">
                {{ link.label }}
            </span>
            </Link>
        </div>
    </div>
</template>