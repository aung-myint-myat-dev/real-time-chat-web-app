<script setup>
import { usePage } from '@inertiajs/vue3';
import ConversationUserItem from './ConversationUserItem.vue';
import { computed, onMounted } from 'vue';

const page = usePage();
const props = defineProps({
    conversations: {
        type: Array,
        required: true,
    },
    selectedChatId: {
        type: [Number, String],
        default: null,
    },
    formatConversationTime: {
        type: Function,
        required: true,
    },
});

const authUser = computed(() => page.props.auth.user || null);
const otherUser = (users) => {
    return users.find((user) => user.id !== authUser.value.id ?? null);
}
</script>

<template>
    <div class="space-y-1 relative px-4">
        <TransitionGroup name="conversation-list">
            <ConversationUserItem v-for="conversation in conversations" 
                :key="conversation.id"
                :conversation="conversation" 
                :is-selected="selectedChatId === conversation.id"
                :other-user="otherUser(conversation.users)"
                :format-conversation-time="formatConversationTime" />
        </TransitionGroup>
    </div>
</template>

<style scoped>
/* 
  Transition Group Animations for Smooth Reordering 
*/
.conversation-list-move,
.conversation-list-enter-active,
.conversation-list-leave-active {
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}

.conversation-list-enter-from,
.conversation-list-leave-to {
    opacity: 0;
    transform: translateY(-12px) scale(0.97);
}

/* Ensure leaving elements don't disrupt the flow during shift */
.conversation-list-leave-active {
    position: absolute;
    width: 100%;
}
</style>