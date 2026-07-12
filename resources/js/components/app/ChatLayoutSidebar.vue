<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { Bookmark, MessageCircle, MessageCircleWarningIcon, Search, Settings, UserCircle, X } from '@lucide/vue';
import axios from 'axios';
import { computed, inject, ref, watch } from 'vue';

const page = usePage();

defineProps({
    chats: {
        type: Array,
        default: [],
    },
    selectedChatId: {
        type: [String, Number],
        default: null
    }
})

const searchInput = ref('');
const isSearching = ref(false);
const searchResult = ref([]);
const hasSearched = ref(false);

let timeout;

const searchUser = async (query) => {
    if (query.length < 2 || !query.startsWith('@')) {
        isSearching.value = false;
        hasSearched.value = false;
        searchResult.value = [];
        return;
    }

    isSearching.value = true;
    hasSearched.value = true;

    try {
        const username = query.substring(1);
        const response = await axios.get(
            "/users/search",
            {
                params: {
                    q: username
                }
            }
        )
        searchResult.value = response.data;
    } catch (error) {
        console.log(error);
    } finally {
        isSearching.value = false;
    }
}

watch(searchInput, (value) => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
        searchUser(value);
    }, 300)
})

const cancleSearch = () => {
    searchInput.value = '';
    searchResult.value = [];
    isSearching.value = false;
    hasSearched.value = false;
}

defineEmits(['whenSelectAChatList', 'whenSelectASearchedUser', 'cancelSearchUser'])

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
        <div class="h-16 p-4 flex items-center justify-center gap-2">
            <div
                class="flex gap-2 p-1.5 w-full border border-border-color shadow-xs rounded-full focus-within:outline focus-within:outline-offset-2 focus-within:outline-brand-500">
                <div
                    class="w-6 h-6 rounded-full flex items-center justify-center bg-gray-200 dark:bg-gray-800 text-gray-500 dark:text-gray-400">
                    <Search class="w-3 h-3" />
                </div>
                <input v-model="searchInput" type="text" placeholder="Search username"
                    class="flex-1 text-sm focus:outline-none">
                <button v-if="hasSearched && searchInput.length >= 0" @click="cancleSearch"
                    class="text-xs bg-zinc-200/50 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 cursor-pointer h-6 w-6 flex items-center justify-center rounded-full transition-duration">
                    <X size="13" />
                </button>
            </div>
        </div>

        <div v-if="searchResult && searchResult.length > 0">
            <div class="flex items-center justify-between p-2">
                <p class="text-sm text-slate-500 dark:text-slate-400">Search Results:</p>
            </div>
            <div class="flex flex-col gap-2 p-2">
                <button v-for="user in searchResult" :key="user.id" @click="$emit('whenSelectASearchedUser', user)"
                    class="flex items-center border border-border-color hover:bg-brand-100/50 dark:hover:bg-brand-800/50 gap-3 p-3 rounded-xl cursor-pointer">
                    <img v-if="user.avatar" :src="user.avatar"
                    class="size-11 object-cover rounded-full border border-slate-200 dark:border-slate-700">
                    <div v-else class="relative group">
                        <div
                            class="size-12 shrink-0 bg-zinc-100 dark:bg-zinc-800 font-bold text-lg text-zinc-700 dark:text-zinc-300 rounded-full flex items-center justify-center border-2 border-border-color shadow-xs">
                            PF
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col items-center min-w-0">
                        <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 truncate">
                            {{ user.name }}
                        </h3>
                        <p class="text-sm text-gray-500">@{{ user.username }}</p>
                    </div>
                </button>
            </div>
        </div>

        <div v-else-if="hasSearched && searchResult.length === 0"
            class="flex flex-col flex-1 justify-center items-center gap-2">

            <div class="h-14 w-14 text-white bg-red-500 rounded-full flex items-center justify-center">
                <MessageCircleWarningIcon />
            </div>
            <h2 class="font-bold text-gray-500">No results yet
            </h2>
        </div>

        <div v-else>
            <div class="flex-1 h-full flex flex-col overflow-hidden overflow-y-auto p-2 pb-18">
                <Link v-for="chat in chats" :key="chat.id" :href="`/chat/${chat.id}`" :class="[
                    selectedChatId == chat.id ? 'bg-blue-500/20' : ''
                ]" class="
                    flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all duration-200"
                    @click="$emit('whenSelectAChatList', chat.id)">

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
        </div>
    </div>
</template>