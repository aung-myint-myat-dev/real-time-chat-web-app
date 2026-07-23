<script setup>
import { inject, onMounted, computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    message: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const authUser = computed(() => page.props.auth.user);

const senderName = computed(() => props.message.user.name);
const isMe = computed(
    () => authUser.value.id === props.message.user_id ?? false,
);

const dateFormatter = (timestamp) => {
    const date = new Date(timestamp);

    // const year = date.getFullYear();
    // const month = String(date.getMonth() + 1).padStart(2, "0");
    // const day = String(date.getDate()).padStart(2, "0");

    // 1. Get raw hours
    const rawHour = date.getHours();

    // 2. Determine AM or PM
    const ampm = rawHour >= 12 ? "PM" : "AM";

    // 3. Convert 24-hour to 12-hour format (and handle midnight as 12)
    const hour12 = rawHour % 12 || 12;

    // 4. Pad the 12-hour variable
    const hour = String(hour12).padStart(2, "0");

    const minute = String(date.getMinutes()).padStart(2, "0");
    const second = String(date.getSeconds()).padStart(2, "0");

    // 5. Append AM/PM to your final string
    const formatted = `${hour}:${minute} ${ampm}`;

    return formatted;
    // console.log(formatted); // Outputs: "2026-07-17 04:09:35 PM"
};
</script>

<template>
    <div
        :class="[
            'flex items-end space-x-2 p-2 max-w-[85%] sm:max-w-[70%]',
            isMe ? 'ml-auto flex-row-reverse space-x-reverse' : 'mr-auto',
        ]"
    >
        <div
            class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 shrink-0 border border-transparent dark:border-slate-700/50"
        >
            <img v-if="message.user.avatar" :src="message.user.avatar" class="rounded-full"/>
            <span v-else class="rounded-full">{{ senderName ? senderName.charAt(0).toUpperCase() : "U" }}</span>
        </div>

        <div class="flex flex-col">
            <div
                :class="[
                    'px-4 py-2.5 text-sm shadow-xs wrap-break-word max-w-full transition-colors duration-200',
                    isMe
                        ? 'bg-brand-500 text-white rounded-2xl rounded-br-none shadow-brand-500/5'
                        : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl rounded-bl-none border border-slate-200 dark:border-slate-700/60',
                ]"
            >
                <p class="leading-relaxed">{{ message.body }}</p>
            </div>

            <span
                :class="[
                    'text-[10px] text-slate-400 dark:text-slate-500 mt-1 block px-1',
                    isMe ? 'text-right' : 'text-left',
                ]"
            >
                {{ dateFormatter(message.created_at) }}
            </span>
        </div>
    </div>
</template>
