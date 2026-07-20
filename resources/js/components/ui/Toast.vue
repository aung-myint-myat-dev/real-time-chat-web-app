<template>
    <div class="fixed bottom-5 right-5 z-50 space-y-3 max-w-sm w-full pointer-events-none">
        <TransitionGroup name="toast" enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100"
            leave-to-class="opacity-0">
            <div v-if="show" :class="[
                type === 'error'
                    ? 'bg-rose-50 dark:bg-rose-950/20 border-rose-200 dark:border-rose-900/50 text-rose-800 dark:text-rose-300'
                    : 'bg-emerald-50 dark:bg-emerald-950/20 border-emerald-200 dark:border-emerald-900/50 text-emerald-800 dark:text-emerald-300',
                'pointer-events-auto p-4 rounded-2xl border shadow-lg backdrop-blur-md flex items-start space-x-3 text-sm transition-all'
            ]">
                <div class="flex-shrink-0 mt-0.5">
                    <svg v-if="type !== 'error'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-5 h-5 text-rose-600 dark:text-rose-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                    </svg>
                </div>

                <div class="flex-1 font-medium tracking-wide">
                    {{ message }}
                </div>

                <div class="flex-shrink-0 flex">
                    <button @click="show = false" :class="[
                        type === 'error'
                            ? 'text-rose-400 hover:text-rose-600 dark:hover:text-rose-200 hover:bg-rose-100/50 dark:hover:bg-rose-900/30'
                            : 'text-emerald-400 hover:text-emerald-600 dark:hover:text-emerald-200 hover:bg-emerald-100/50 dark:hover:bg-emerald-900/30',
                        'p-1 rounded-lg transition-colors duration-150'
                    ]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>

const show = defineModel('show', { type: Boolean, default: false })

defineProps({
    message: { type: String, default: '' },
    type: { type: String, default: 'success' },
})
</script>