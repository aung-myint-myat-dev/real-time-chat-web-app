<template>
    <div
        :class="['flex items-end space-x-2 p-2 max-w-[85%] sm:max-w-[70%]', isMe ? 'ml-auto flex-row-reverse space-x-reverse' : 'mr-auto']">

        <div v-if="!isMe"
            class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 shrink-0 border border-transparent dark:border-slate-700/50">
            {{ senderName ? senderName.charAt(0).toUpperCase() : 'U' }}
        </div>

        <div class="flex flex-col">
            <div :class="[
                'px-4 py-2.5 text-sm shadow-xs wrap-break-word max-w-full transition-colors duration-200',
                isMe
                    ? 'bg-brand-500 text-white rounded-2xl rounded-br-none shadow-brand-500/5'
                    : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl rounded-bl-none border border-slate-200 dark:border-slate-700/60'
            ]">
                <p class="leading-relaxed">{{ message.text }}</p>
            </div>

            <span
                :class="['text-[10px] text-slate-400 dark:text-slate-500 mt-1 block px-1', isMe ? 'text-right' : 'text-left']">
                {{ message.time }}
            </span>
        </div>

    </div>
</template>

<script setup>
defineProps({
    message: {
        type: Object,
        required: true
    },
    isMe: {
        type: Boolean,
        default: false
    },
    senderName: {
        type: String,
        default: ''
    }
})
</script>