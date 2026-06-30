<template>
    <div
        class="p-4 bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700/60 shrink-0 transition-colors duration-200">
        <form @submit.prevent="handleSendMessage" class="flex items-center gap-2.5 max-w-7xl mx-auto">

            <div class="relative grow flex items-center">
                <input v-model="messageText" type="text" placeholder="Type a message..."
                    class="w-full pl-4 pr-12 py-3 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:focus:ring-brand-500/10 focus:outline-hidden transition-all" />

                <button type="button"
                    class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 p-1 rounded-lg transition-colors cursor-pointer"
                    title="Add Emoji">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>

            <button type="submit" 
            :disabled="!messageText.trim()" 
            :class="[
                'p-3 rounded-xl text-white font-semibold shadow-md transition-all duration-200 focus:outline-hidden focus:ring-2 focus:ring-brand-500/50 flex items-center justify-center shrink-0 cursor-pointer',
                messageText.trim()
                    ? 'bg-brand-500 hover:bg-brand-600 active:bg-brand-700 shadow-brand-500/20 hover:scale-105'
                    : 'bg-slate-300 dark:bg-slate-700 text-slate-400 dark:text-slate-500 shadow-none cursor-not-allowed'
            ]" title="Send Message">
                <!-- <svg class="h-5 w-5 transform rotate-45 -translate-x-0.5 translate-y-0.5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg> -->
                <Send size="16"/>
            </button>

        </form>
    </div>
</template>

<script setup>
import { Send } from '@lucide/vue'
import { ref } from 'vue'

const messageText = ref('')

// Parent Component ဆီကို message ပို့လိုက်တဲ့အကြောင်း လှမ်းသတင်းပို့ဖို့ event သတ်မှတ်ခြင်း
const emit = defineEmits(['sendMessage'])

const handleSendMessage = () => {
    // စာအလွတ်ကြီးဆိုရင် ပို့လို့မရအောင် ကာကွယ်ခြင်း
    if (!messageText.value.trim()) return

    // လက်ရှိအချိန်ကို Format လုပ်ခြင်း (ဥပမာ - 6:15 PM)
    const currentTime = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })

    // Parent (Chat Area) ဆီကို Data လှမ်းပစ်ပေးခြင်း
    emit('sendMessage', {
        text: messageText.value.trim(),
        time: currentTime
    })

    // ပို့ပြီးရင် Input Box ကို ပြန်ရှင်းပစ်မယ်
    messageText.value = ''
}
</script>