<script setup>
import { Link, router, usePage } from "@inertiajs/vue3";
import { Search, X, } from "@lucide/vue";
import axios from "axios";
import { computed, inject, onMounted, onUnmounted, ref, watch } from "vue";
import { useConversationStore } from "../../stores/conversationStore.js";
import { useOnlineUsersStore } from "../../stores/onlineUsersStore.js";
import { formatConversationTime } from "../../utils/formatConversationTime.js";
import ConversationUsersContainer from "../conversations/ConversationUsersContainer.vue";
import ChatLayoutSidebarFooter from "./ChatLayoutSidebarFooter.vue";
import SearchedUsersContainer from "../searched-users/SearchedUsersContainer.vue";

const page = usePage();
const authUser = page.props.auth.user;
const selectedChatId = computed(() => page.props.selectedChatId);

const props = defineProps({
    conversations: Array,
    selectedChatId: Number || String,
});

const conversationStore = useConversationStore();
const onlineUsersStore = useOnlineUsersStore();

const { handleSelectedChatId } = inject("HaldleSelectedChatId");

const searchInput = ref("");
const searchInputRef = ref(null);
const isSearching = ref(false);
const searchResult = ref([]);
const hasSearched = ref(false);
const noResult = ref(false);
let timeout;

const searchUser = async (query) => {
    if (query.length > 2) {
        try {
            const response = await axios.get("/users/search", {
                params: {
                    q: query,
                },
            });
            searchResult.value = response.data;
            if (isSearching && response.data.length === 0) {
                hasSearched.value = true;
                noResult.value = true;
            }
        } catch (error) {
            console.log(error);
        }
    }
};
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
    }, 300);
});
const cancleSearch = () => {
    searchInput.value = "";
    searchResult.value = [];
    isSearching.value = false;
    hasSearched.value = false;
    noResult.value = false;
};

onMounted(() => {
    Echo.private(`users.${authUser.id}`)
        .listen(".conversation.created", (e) => {
            conversationStore.addConversation(e.conversation);
        })
        .listen(".conversation.updated", (e) => {
            conversationStore.updateConversation(e.conversation)
        });
});
onUnmounted(() => {
    Echo.leave(`users.${authUser.id}`);
    Echo.leave('online');
});
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
                    placeholder="Search username" class="flex-1 text-sm focus:outline-none" />
                <button v-if="isSearching && searchInput.length >= 0" @click="cancleSearch"
                    class="text-xs bg-zinc-200/50 hover:bg-zinc-300 dark:bg-zinc-800 dark:hover:bg-zinc-700 cursor-pointer h-6 w-6 flex items-center justify-center rounded-full transition-duration">
                    <X size="13" />
                </button>
            </div>
        </div>

        <SearchedUsersContainer v-if="isSearching" :users="searchResult" :is-searching="isSearching"
            :selected-user-id="props.selectedChatId" :selected-chat-id="props.selectedChatId"
            :has-searched="hasSearched" :no-result="noResult" />

        <ConversationUsersContainer v-else :conversations="conversationStore.conversations"
            :selected-chat-id="props.selectedChatId" :format-conversation-time="formatConversationTime" />


        <ChatLayoutSidebarFooter />
    </div>
</template>
