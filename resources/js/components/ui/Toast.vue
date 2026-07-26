<script setup>
import { computed, onMounted } from 'vue';

const props = defineProps({
    show: {
        type: Boolean,
        default: true
    },
    message: {
        type: String,
        required: true
    },
    type: {
        type: String,
        default: 'success' // 'success' | 'error' | 'warning'
    },
    duration: {
        type: Number,
        default: 4000 // ၄ စက္ကန့်ကြာရင် အလိုအလျောက် ပိတ်မည် (0 ဆိုလျှင် မပိတ်ပါ)
    }
});

const emit = defineEmits(['close']);

// Auto close logic
onMounted(() => {
    if (props.duration > 0) {
        setTimeout(() => {
            emit('close');
        }, props.duration);
    }
});

// Type အလိုက် အရောင်နှင့် Styles များ
const config = computed(() => {
    switch (props.type) {
        case 'error':
            return {
                title: 'Error',
                border: 'border-rose-500/20 dark:border-rose-500/30',
                bg: 'bg-rose-50/95 dark:bg-rose-950/50',
                text: 'text-rose-900 dark:text-rose-200',
                iconColor: 'text-rose-500 dark:text-rose-400',
                closeBtn: 'hover:bg-rose-200/50 dark:hover:bg-rose-900/40 text-rose-500'
            };
        case 'warning':
            return {
                title: 'Warning',
                border: 'border-amber-500/20 dark:border-amber-500/30',
                bg: 'bg-amber-50/95 dark:bg-amber-950/50',
                text: 'text-amber-900 dark:text-amber-200',
                iconColor: 'text-amber-500 dark:text-amber-400',
                closeBtn: 'hover:bg-amber-200/50 dark:hover:bg-amber-900/40 text-amber-500'
            };
        case 'success':
        default:
            return {
                title: 'Success',
                border: 'border-emerald-500/20 dark:border-emerald-500/30',
                bg: 'bg-emerald-50/95 dark:bg-emerald-950/50',
                text: 'text-emerald-900 dark:text-emerald-200',
                iconColor: 'text-emerald-500 dark:text-emerald-400',
                closeBtn: 'hover:bg-emerald-200/50 dark:hover:bg-emerald-900/40 text-emerald-500'
            };
    }
});
</script>

<template>
    <!-- Slide Down Animation Transition -->
    <Transition appear enter-active-class="transform ease-out duration-300 transition-all"
        enter-from-class="-translate-y-8 opacity-0 scale-95" enter-to-class="translate-y-0 opacity-100 scale-100"
        leave-active-class="transform ease-in duration-200 transition-all"
        leave-from-class="translate-y-0 opacity-100 scale-100" leave-to-class="-translate-y-4 opacity-0 scale-95">
        <div v-if="show" :class="[
            config.bg,
            config.border,
            'pointer-events-auto flex items-center gap-3 p-3.5 rounded-2xl border shadow-xl backdrop-blur-md transition-all duration-300 hover:scale-[1.02] dark:border-zinc-800 dark:bg-zinc-900/95'
        ]">
            <!-- Type Icon -->
            <div
                class="size-9 shrink-0 rounded-full flex items-center justify-center bg-white/80 dark:bg-zinc-800/80 shadow-sm border border-black/5 dark:border-white/5">
                <!-- Success Icon -->
                <svg v-if="type === 'success'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" :class="['size-5', config.iconColor]">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>

                <!-- Error Icon -->
                <svg v-else-if="type === 'error'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" :class="['size-5', config.iconColor]">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>

                <!-- Warning Icon -->
                <svg v-else-if="type === 'warning'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" :class="['size-5', config.iconColor]">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>

            <!-- Content Area -->
            <div class="flex-1 min-w-0">
                <h4 class="font-semibold text-xs capitalize text-zinc-900 dark:text-white">
                    {{ config.title }}
                </h4>
                <p :class="['mt-0.5 text-xs font-medium tracking-wide truncate', config.text]">
                    {{ message }}
                </p>
            </div>

            <!-- Close Button -->
            <button @click.stop.prevent="emit('close')" :class="[
                config.closeBtn,
                'rounded-full p-1.5 transition-colors duration-150 text-zinc-400 hover:bg-zinc-200/60 dark:hover:bg-zinc-800 dark:hover:text-white'
            ]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="size-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </Transition>
</template>