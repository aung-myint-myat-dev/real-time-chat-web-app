<script setup>
import NotificationItem from './NotificationItem.vue';

defineProps({
    notifications: Array,
});

const emit = defineEmits(['close']);
</script>

<template>
    <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4 pointer-events-none">
        <TransitionGroup tag="div" class="space-y-2" enter-active-class="transform ease-out duration-300 transition-all"
            enter-from-class="-translate-y-8 opacity-0 scale-95" enter-to-class="translate-y-0 opacity-100 scale-100"
            leave-active-class="transform ease-in duration-200 transition-all"
            leave-from-class="translate-y-0 opacity-100 scale-100" leave-to-class="-translate-y-4 opacity-0 scale-95"
            move-class="transition-all duration-300">
            <NotificationItem v-for="notification in notifications" :key="notification.id" :notification="notification"
                class="pointer-events-auto" @close="emit('close', $event)" />
        </TransitionGroup>
    </div>
</template>