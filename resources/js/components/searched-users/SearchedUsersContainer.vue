<script setup>
import { MessageCircleWarning } from '@lucide/vue';
import { useConversationStore } from '../../stores/conversationStore.js';
import { formatConversationTime } from '../../utils/formatConversationTime.js';
import ConversationUserItem from '../conversations/ConversationUserItem.vue';
import SearchedUserItem from './SearchedUserItem.vue';

const props = defineProps({
    users: Array,
    isSearching: Boolean,
    selectedUserId: Number || String,
    selectedChatId: Number,
    hasSearched: Boolean,
    noResult: Boolean,
})
const conversationStore = useConversationStore();

const getConversation = (id) => {
    return conversationStore.conversations.find((con) => con.id === id);
}
</script>

<template>
    <div v-if="props.users.length > 0" class="flex flex-col h-full overflow-hidden">
        <!-- Header -->
        <div class="px-3 py-2">
            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                Search Results
            </p>
        </div>

        <!-- Scrollable Results Container -->
        <div class="flex-1 h-full flex flex-col overflow-y-auto p-2 space-y-1 pb-18">
            <template v-for="user in props.users" :key="user.id">
                <ConversationUserItem v-if="user.conversation_id" :other-user="user"
                    :conversation="getConversation(user.conversation_id)" :is-selected="false"
                    :format-conversation-time="formatConversationTime" />
                <SearchedUserItem v-else :user="user" />
            </template>
        </div>
    </div>

    <div v-else-if="props.hasSearched && props.noResult" class="flex h-full flex-1">
        <div class="flex flex-col flex-1 justify-center items-center gap-2">
            <div class="h-14 w-14 text-white bg-red-500 rounded-full flex items-center justify-center">
                <MessageCircleWarning />
            </div>
            <h2 class="font-bold text-gray-500">No users found matching your search.</h2>
        </div>
    </div>
</template>