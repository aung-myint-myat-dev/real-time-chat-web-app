<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';
import { useOnlineUsersStore } from '../../stores/onlineUsersStore';
import { useNotificationStore } from '../../stores/notificationStore';

const props = defineProps({
    notification: {
        type: Object,
        default: null,
    },
});

const onlineUsersStore = useOnlineUsersStore();
const notificationStore = useNotificationStore();

const close = (id) => {
    notificationStore.remove(id);
}

onMounted(() => {
    setTimeout(() => {
        close(props.notification?.id);
    }, 10000);
});
</script>

<template>
    <!-- Slide-Down Animation အတွက် Transition Component -->
    <Transition appear enter-active-class="transform ease-out duration-300 transition-all"
        enter-from-class="-translate-y-8 opacity-0 scale-95" enter-to-class="translate-y-0 opacity-100 scale-100"
        leave-active-class="transform ease-in duration-200 transition-all"
        leave-from-class="translate-y-0 opacity-100 scale-100" leave-to-class="-translate-y-4 opacity-0 scale-95">
       <Link
    @click="close(props.notification.id)"
    :href="`/chats/${props.notification.conversation_id}`"
    class="flex items-center mb-2 gap-3.5 rounded-2xl p-4 transition-all duration-300 hover:scale-[1.02] cursor-pointer shadow-xl backdrop-blur-md border border-slate-200/80 bg-white/95 dark:border-zinc-800 dark:bg-zinc-900/95"
>
            <!-- Avatar -->
            <div class="relative size-10 shrink-0">
                <img v-if="notification?.user?.avatar" :src="notification.user.avatar"
                    class="size-full object-cover rounded-full border border-slate-200 dark:border-zinc-700 shadow-sm" />
                <div v-else
                    class="size-full bg-slate-200 dark:bg-zinc-700 flex items-center justify-center rounded-full font-bold text-lg text-slate-700 dark:text-zinc-200 shadow-sm">
                    {{ notification?.user?.name?.charAt(0).toUpperCase() }}
                </div>

                <!-- Online Status Dot -->
                <span v-if="onlineUsersStore.isOnline(notification?.user?.id)"
                    class="absolute bottom-0 right-0 size-3 bg-emerald-500 border-2 border-white dark:border-zinc-900 rounded-full shadow-sm"></span>
            </div>

            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <h3 class="font-semibold text-sm truncate text-slate-900 dark:text-white">
                        {{ notification?.user?.name }}
                    </h3>

                    <span class="text-[11px] font-medium text-slate-400 dark:text-zinc-400 shrink-0">
                        now
                    </span>
                </div>

                <p class="mt-0.5 truncate text-xs text-slate-600 dark:text-zinc-300 font-medium">
                    {{ notification?.body?.slice(0, 30) }} {{ notification?.body?.length > 30 ? '....' : '' }}
                </p>
            </div>

            <!-- Close Button -->
            <button @click.stop.prevent="close(props.notification.id)"
                class="rounded-full p-1.5 transition-colors duration-150 text-slate-400 hover:bg-slate-100 hover:text-slate-700 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </Link>
    </Transition>
</template>