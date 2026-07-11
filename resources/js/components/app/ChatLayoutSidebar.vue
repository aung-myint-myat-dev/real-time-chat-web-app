<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { Bookmark, MessageCircle, MessageCircleWarningIcon, Search, Settings, UserCircle } from '@lucide/vue';
import { computed, inject, ref, watch } from 'vue';

const page = usePage();
defineProps({
    chats: Array,
    activeChatId: String,
    searchUsers: {
        default: null
    }
})

const searchInput = ref('');
let timeout;
watch(searchInput, value => {

    clearTimeout(timeout)

    if (value.length < 2) {
        router.get('/chat', {}, { preserveState: true, replace: true });
        return;
    }

    timeout = setTimeout(() => {

        router.get('/users/search',
            { q: value },
            { preserveState: true, replace: true, preserveScroll: true });

    }, 300)

})


defineEmits(['selectChat'])

const links = [
    { id: 1, label: 'Chats', icon: MessageCircle, href: '/chat' },
    { id: 3, label: 'Saved', icon: Bookmark, href: '/saved/messages' },
    { id: 2, label: 'Settings', icon: Settings, href: '/settings' },
    { id: 4, label: 'Profile', icon: UserCircle, href: '/profile' },
];

const isLinkActive = (href) => {
    return page.url.startsWith(href);
};
</script>
<template>
    <div class="w-full h-full relative flex flex-col gap-2 border-r border-border-color">

        <!-- Search Input -->
        <div class="h-16 p-4 flex items-center justify-center">
            <div
                class="flex gap-2 p-1.5 w-full border border-border-color shadow-xs rounded-full focus-within:outline focus-within:outline-offset-2 focus-within:outline-brand-500">
                <div
                    class="w-6 h-6 rounded-full flex items-center justify-center bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                    <Search class="w-3 h-3" />
                </div>
                <input v-model="searchInput" type="text" placeholder="Search username"
                    class="flex-1 text-sm focus:outline-none">
            </div>
        </div>

        <div v-if="searchUsers && searchUsers.length > 0">
            <p class="text-sm text-slate-500 dark:text-slate-400 p-2">Search Results:</p>
            <div class="flex flex-col gap-2 p-2">
                <div v-for="user in searchUsers" :key="user.id"
                    class="flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all duration-200">
                    <img :src="user.avatar"
                        class="size-11 object-cover rounded-full border border-slate-200 dark:border-slate-700">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-semibold text-slate-800 dark:text-slate-200 truncate">
                            {{ user.name }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="searchUsers !== null && searchUsers.length === 0"
            class="flex flex-col flex-1 justify-center items-center gap-2">
            
            <div class="h-14 w-14 text-white bg-red-500 rounded-full flex items-center justify-center">
                <MessageCircleWarningIcon />
            </div>
            <h2 class="font-bold text-gray-500">No results yet
            </h2>
            
        </div>


        <!-- Chat lists -->
        <div v-if="searchUsers === null" class="flex-1 h-full flex flex-col overflow-hidden overflow-y-auto p-2 pb-18">
            <Link v-for="chat in chats" :key="chat.id" :href="`/chat/${chat.id}`" :class="[
                activeChatId == chat.id ? 'bg-blue-500/20' : ''
            ]" class="
                flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all duration-200"
                @click="$emit('selectChat', chat.id)">

                <div class="relative size-11 shrink-0">
                    <img :src="chat.avatar"
                        class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700">
                    <span
                        class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-0.5">
                        <h3 :class="[
                            'text-sm truncate',
                            chat.unread_count > 0 ? 'font-bold text-slate-900 dark:text-white' : 'font-semibold text-slate-800 dark:text-slate-200'
                        ]">
                            {{ chat.name }}
                        </h3>
                        <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ chat.time }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-2">
                        <p :class="[
                            'text-xs truncate flex-1',
                            chat.unread_count > 0 ? 'text-slate-900 dark:text-slate-100 font-medium' : 'text-slate-500'
                        ]">
                            {{ chat.last_message }}
                        </p>

                        <span v-if="chat.unread_count > 0"
                            class="bg-blue-500 text-white text-[10px] font-bold min-w-5 h-5 px-1 rounded-full flex items-center justify-center animate-pulse">
                            {{ chat.unread_count }}
                        </span>
                    </div>
                </div>
            </Link>
        </div>

        <!-- Floating Nav Buttons -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-50">
            <div
                class="bg-backgrounds/80 backdrop-blur-xl border border-slate-200/60 dark:border-slate-800/60 rounded-3xl px-4 py-1.5 flex items-center justify-around shadow-[0_20px_50px_rgba(0,0,0,0.15)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.4)]">

                <Link v-for="link in links" :key="link.id" :href="link.href"
                    class="relative flex flex-col items-center justify-center py-2 px-3 rounded-2xl transition-all duration-300 group">
                    <div :class="[
                        'w-11 h-11 flex items-center justify-center rounded-2xl transition-all duration-300 transform',
                        isLinkActive(link.href)
                            ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/30'
                            : 'text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-900'
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
    </div>
</template>