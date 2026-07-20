<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { Bookmark, MessageCircle, MessageCircleWarningIcon, Search, Settings, UserCircle, X } from '@lucide/vue';
import axios from 'axios';
import { computed, inject, onMounted, ref, watch } from 'vue';

const page = usePage();

const conversations = computed(() => page.props.conversations);
const selectedChatId = computed(() => page.props.selectedChatId);

const searchInput = ref('');
const searchInputRef = ref(null);
const isSearching = ref(false);
const searchResult = ref([]);
const hasSearched = ref(false);
const noResult = ref(false);

let timeout;

const searchUser = async (query) => {

    if (query.length > 2) {
        try {

            const response = await axios.get(
                "/users/search",
                {
                    params: {
                        q: query
                    }
                }
            )
            searchResult.value = response.data;
            if (isSearching && response.data.length === 0) {
                hasSearched.value = true;
                noResult.value = true;
            }

        } catch (error) {
            console.log(error);
        }
    }

}

const handleOnFocusSearchInput = () => {
    isSearching.value = true;
};

watch(searchInput, (value) => {

    if (value.length == 0) {
        isSearching.value = false;
        hasSearched.value = false;
        searchResult.value = [];
        noResult.value = false;

        clearTimeout(timeout);

        if (searchInputRef.value) {
            searchInputRef.value.blur();
        }

        return;
    }

    timeout = setTimeout(() => {
        searchUser(value);
    }, 300)
})

const cancleSearch = () => {
    searchInput.value = '';
    searchResult.value = [];
    isSearching.value = false;
    hasSearched.value = false;
    noResult.value = false;
}

defineEmits(['whenSelectAChatList', 'whenSelectASearchedUser', 'cancelSearchUser'])

const links = [
    { id: 1, label: 'Chats', icon: MessageCircle, href: '/chat' },
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
                <input v-model="searchInput" ref="searchInputRef" @focus="handleOnFocusSearchInput" type="text"
                    placeholder="Search username" class="flex-1 text-sm focus:outline-none">
                <button v-if="isSearching && searchInput.length >= 0" @click="cancleSearch"
                    class="text-xs bg-zinc-200/50 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 cursor-pointer h-6 w-6 flex items-center justify-center rounded-full transition-duration">
                    <X size="13" />
                </button>
            </div>
        </div>

        <div v-if="isSearching && searchResult.length > 0">
            <div class="flex items-center justify-between p-2">
                <p class="text-sm text-slate-500 dark:text-slate-400">Search Results:</p>
            </div>

            <div class="flex-1 h-full flex flex-col overflow-hidden overflow-y-auto p-2 pb-18">
                <div v-for="user in searchResult" :key="user.id">
                    <Link v-if="user.conversation_id" :href="`/chats/${user.conversation_id}`" :class="[
                        selectedChatId == user.id ? 'bg-blue-500/20' : ''
                    ]" class="
                        flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all duration-200"
                        @click="$emit('whenSelectAChatList', user.id)">

                        <div class="relative size-11 shrink-0">
                            <img :src="user.avatar"
                                class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700">
                            <span
                                class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <h3>
                                    {{ user.name }}
                                </h3>
                                <!-- <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ conversation.time }}</span> -->
                            </div>


                        </div>
                    </Link>

                    <button v-else @click="$emit('whenSelectASearchedUser', user)" class="w-full
                        flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all duration-200">
                        <img v-if="user.avatar" :src="user.avatar"
                            class="size-11 object-cover rounded-full border border-slate-200 dark:border-slate-700">
                        <div v-else class="relative group">
                            <div
                                class="size-12 shrink-0 bg-zinc-100 dark:bg-zinc-800 font-bold text-lg text-zinc-700 dark:text-zinc-300 rounded-full flex items-center justify-center border-2 border-border-color shadow-xs">
                                PF
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col items-start min-w-0">
                            <h3 class="text-lg font-semibold text-slate-800 dark:text-slate-200 truncate">
                                {{ user.name }}
                            </h3>
                            <p class="text-sm text-gray-500">@{{ user.username }}</p>
                        </div>
                    </button>
                </div>
            </div>
        </div>

        <div v-else-if="isSearching" class="flex flex-1">

            <div v-if="hasSearched && noResult" class="flex flex-col flex-1 justify-center items-center gap-2">
                <div class="h-14 w-14 text-white bg-red-500 rounded-full flex items-center justify-center">
                    <MessageCircleWarningIcon />
                </div>
                <h2 class="font-bold text-gray-500">No results yet
                </h2>
            </div>
        </div>

        <div v-else>
            <div class="flex-1 h-full flex flex-col overflow-hidden overflow-y-auto p-2 pb-18">
                <Link v-for="conversation in conversations" :key="conversation.id" :href="`/chats/${conversation.id}`"
                    :class="[
                        selectedChatId == conversation.id ? 'bg-blue-500/20' : ''
                    ]" class="
                    flex items-center gap-3 p-3 rounded-xl cursor-pointer transition-all duration-200"
                    @click="$emit('whenSelectAChatList', conversation.id)">

                    <div class="relative size-11 shrink-0">
                        <img :src="conversation.avatar"
                            class="size-full object-cover rounded-full border border-slate-200 dark:border-slate-700">
                        <span
                            class="absolute bottom-0 right-0 size-3 bg-green-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <h3 :class="[
                                'text-sm truncate',
                                conversation.length > 0 ? 'font-bold text-slate-900 dark:text-white' : 'font-semibold text-slate-800 dark:text-slate-200'
                            ]">
                                {{ conversation.type == 'group' ? conversation.name : conversation.users[0].name }}

                            </h3>
                            <!-- <span class="text-[10px] text-slate-400 whitespace-nowrap">{{ conversation.time }}</span> -->
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <p :class="[
                                'text-xs truncate flex-1',
                                conversation.unread_count > 0 ? 'text-slate-900 dark:text-slate-100 font-medium' : 'text-slate-500'
                            ]">
                                <!-- {{ conversation.last_message }} -->
                            </p>

                            <span v-if="conversation.unread_count > 0"
                                class="bg-blue-500 text-white text-[10px] font-bold min-w-5 h-5 px-1 rounded-full flex items-center justify-center animate-pulse">
                                <!-- {{ conversation.unread_count }} -->
                            </span>
                        </div>
                    </div>
                </Link>
            </div>
        </div>

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