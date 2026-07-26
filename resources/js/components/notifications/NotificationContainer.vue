<script setup>
import { Divide } from '@lucide/vue';
import NotificationItem from './NotificationItem.vue';

defineProps({
    notifications: Array,
});

const emit = defineEmits(['close'])

</script>

<template>
    <div
        class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
        <NotificationItem 
        v-for="notification in notifications" 
        :key="notification.id" 
        :notification="notification" 
        @close="emit('close', $event)"/>
    </div>
</template>