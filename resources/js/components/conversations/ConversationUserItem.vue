<script setup>
import { Link, usePage } from '@inertiajs/vue3' // Adjust import path if needed (e.g. Inertia / Vue Router)
import { computed, onMounted } from 'vue';
import { useOnlineUsersStore } from '../../stores/onlineUsersStore';

const page = usePage();
const props = defineProps({
    conversation: {
        type: Object,
        required: true,
    },
    isSelected: {
        type: Boolean,
        default: false,
    },
    formatConversationTime: {
        type: Function,
        required: true,
    },
    otherUser: {
        type: Object,
        required: false,
    }
})

const authUser = computed(() => page.props.auth.user);
const getOtherUser = (users) => {
    return users.find((user) => user.id !== authUser.value.id ?? null);
}
const onlineUsersStore = useOnlineUsersStore();
</script>

<template>
    <Link :href="`/chats/${props.conversation.id}`" :class="[
        isSelected
            ? 'bg-blue-500/15 dark:bg-blue-500/20 shadow-sm'
            : 'hover:bg-slate-100 dark:hover:bg-slate-800/60',
    ]" class="group relative flex items-center gap-3.5 p-3 rounded-2xl cursor-pointer transition-colors duration-200">
        <!-- User Avatar & Status Indicator -->
        <div class="relative size-11 shrink-0">
            <img v-if="props.otherUser?.avatar" :src="props.otherUser.avatar" :alt="props.conversation.name || 'User avatar'"
                class="size-full object-cover rounded-full ring-2 ring-slate-100 dark:ring-slate-800 group-hover:ring-slate-200 dark:group-hover:ring-slate-700 transition-all duration-200" />
            <div v-else
                class="size-full flex items-center justify-center rounded-full ring-2 ring-slate-100 dark:ring-slate-800 bg-zinc-500 text-white font-semibold group-hover:ring-slate-200 dark:group-hover:ring-slate-700 transition-all duration-200">
                {{ props.otherUser?.name?.charAt(0).toUpperCase() }}
            </div>

            <span v-if="onlineUsersStore.isOnline(props.otherUser?.id)"
                class="absolute bottom-0 right-0 size-3 bg-emerald-500 ring-2 ring-white dark:ring-slate-900 rounded-full"
                title="Online"></span>
        </div>

        <!-- Conversation Details -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2 mb-1">
                <h3 :class="[
                    'text-sm truncate tracking-tight',
                    props.conversation.unread_count > 0
                        ? 'font-bold text-slate-900 dark:text-slate-50'
                        : 'font-medium text-slate-700 dark:text-slate-300',
                ]">
                    {{
                        props.conversation.type === 'group'
                            ? props.conversation.name
                            : props.otherUser?.name
                    }}
                </h3>

                <span v-if="props.conversation.last_message_at"
                    class="text-[11px] font-medium text-slate-400 dark:text-slate-500 whitespace-nowrap shrink-0">
                    {{ formatConversationTime(props.conversation.last_message_at) }}
                </span>
            </div>

            <div class="flex items-center justify-between gap-2">
                <p :class="[
                    'text-xs truncate flex-1 leading-relaxed',
                    props.conversation.unread_count > 0
                        ? 'text-slate-900 dark:text-slate-200 font-semibold'
                        : 'text-slate-500 dark:text-slate-400',
                ]">
                    {{ props.conversation.last_message || 'No messages yet' }}
                </p>

                <!-- Unread Count Badge -->
                <span v-if="conversation.unread_count > 0"
                    class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-blue-600 rounded-full shrink-0 shadow-xs animate-pulse">
                    {{ props.conversation.unread_count }}
                </span>
            </div>
        </div>
    </Link>
</template>