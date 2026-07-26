<script setup>
import { Link } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    notification: Object
})

const emit = defineEmits(['close'])

onMounted(() => {
    setTimeout(() => {
        emit('close', props.notification.id);
    }, 10000);

});
</script>
<template>
    <Link @click="$emit('close', notification.id)" :href="`/chats/${props.notification.conversation_id}`"
        class="flex items-center mb-1 gap-3 rounded-2xl border border-zinc-700 bg-zinc-900/95 backdrop-blur-md shadow-2xl p-4 transition-all duration-300 hover:scale-[1.02] cursor-pointer">
        <!-- Avatar -->
        <img src="" class="h-12 w-12 rounded-full object-cover" />

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-white">
                    {{ notification.user.name }}
                </h3>

                <span class="text-xs text-zinc-400">
                    now
                </span>
            </div>

            <p class="mt-1 truncate text-sm text-zinc-300">
                {{ notification.body.slice(0, 25) }} ....
            </p>
        </div>

        <!-- Close -->
        <button @click="$emit('close', notification.id)"
            class="rounded-full p-1 text-zinc-400 hover:bg-zinc-800 hover:text-white">
            ✕
        </button>
    </Link>
</template>