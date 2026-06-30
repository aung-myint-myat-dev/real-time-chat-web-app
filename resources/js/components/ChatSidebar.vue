<template>
    <div class="flex flex-col h-full bg-white dark:bg-slate-800 transition-colors duration-200">

        <div class="p-4 border-b border-slate-100 dark:border-slate-700/50 shrink-0">
            <div class="relative">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400 dark:text-slate-500">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>

                <input v-model="searchQuery" type="text" placeholder="Search chats..."
                    class="w-full pl-9 pr-4 py-2 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 dark:focus:ring-brand-500/10 focus:outline-hidden transition-all" />
            </div>
        </div>

        <div
            class="grow overflow-y-auto divide-y divide-slate-50 dark:divide-slate-700/30 p-2 space-y-0.5 custom-scrollbar">

            <div v-if="filteredUsers.length === 0" class="text-center py-8 text-sm text-slate-400 dark:text-slate-500">
                No chats found
            </div>

            <button v-for="user in filteredUsers" :key="user.id" @click="selectUser(user)" :class="[
                'w-full flex items-center gap-3 p-3 rounded-xl text-left transition-all duration-200 cursor-pointer group',
                activeUserId === user.id
                    ? 'bg-brand-50/80 dark:bg-brand-500/10 border-l-4 border-brand-500 pl-2'
                    : 'hover:bg-slate-50 dark:hover:bg-slate-700/40'
            ]">
                <div class="relative shrink-0">
                    <div :class="[
                        'w-11 h-11 rounded-full flex items-center justify-center font-bold text-sm text-white shadow-xs',
                        user.avatarBg || 'bg-slate-400'
                    ]">
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <span v-if="user.isOnline"
                        class="absolute bottom-0 right-0 block h-3 w-3 rounded-full bg-emerald-500 ring-2 ring-white dark:ring-slate-800"></span>
                </div>

                <div class="grow min-w-0">
                    <div class="flex items-center justify-between">
                        <h3 :class="[
                            'text-sm font-semibold truncate',
                            activeUserId === user.id ? 'text-brand-600 dark:text-brand-400' : 'text-slate-800 dark:text-slate-200'
                        ]">
                            {{ user.name }}
                        </h3>
                        <span class="text-[11px] text-slate-400 dark:text-slate-500 shrink-0">
                            {{ user.lastMessageTime }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-0.5">
                        <p class="text-xs text-slate-500 dark:text-slate-400 truncate pr-2">
                            {{ user.lastMessage }}
                        </p>
                        <span v-if="user.unreadCount > 0"
                            class="bg-brand-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-4 text-center">
                            {{ user.unreadCount }}
                        </span>
                    </div>
                </div>

            </button>

        </div>

        <ChatSidebarFooter />
    </div>
</template>

<script setup>
import { Settings, Star, UserPlus, Users } from '@lucide/vue'
import { ref, computed } from 'vue'
import ChatSidebarFooter from './ChatSidebarFooter.vue'

// Search State
const searchQuery = ref('')
const activeUserId = ref(1) // လက်ရှိ ရွေးထားတဲ့ user ID အမှတ်အသား

// Mock Data (သင့်ရဲ့ database/API ကလာမယ့် data ပုံစံမျိုး ထည့်သွင်းထားပါတယ်)
const users = ref([
    {
        id: 1,
        name: 'Kyaw Kyaw',
        lastMessage: 'ဒီနေ့ ညနေ အလုပ်ဆင်းရင် စာပို့လိုက်ဦးနော်။',
        lastMessageTime: '5:32 PM',
        isOnline: true,
        unreadCount: 2,
        avatarBg: 'bg-indigo-500'
    },
    {
        id: 2,
        name: 'Mya Mya',
        lastMessage: 'ဓါတ်ပုံတွေ ပို့ပေးထားတယ်၊ စစ်ပေးပါဦး။',
        lastMessageTime: '4:15 PM',
        isOnline: false,
        unreadCount: 0,
        avatarBg: 'bg-emerald-500'
    },
    {
        id: 3,
        name: 'Aung Aung',
        lastMessage: 'အိုကေဗျာ၊ မနက်ဖြန်မှ တွေ့ကြမယ်။',
        lastMessageTime: 'Yesterday',
        isOnline: true,
        unreadCount: 0,
        avatarBg: 'bg-amber-500'
    },
    {
        id: 4,
        name: 'Thiri Hlaing',
        lastMessage: 'CheckChat project setup က အဆင်ပြေရဲ့လား?',
        lastMessageTime: '26 Jun',
        isOnline: false,
        unreadCount: 0,
        avatarBg: 'bg-rose-500'
    }
])

// Search Query အပေါ်မူတည်ပြီး User Name ကို စစ်ထုတ်ပေးမည့် Computed Property
const filteredUsers = computed(() => {
    return users.value.filter(user =>
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
})

// User ကို နှိပ်လိုက်ရင် Chat room ပြောင်းဖို့ လုပ်ဆောင်ချက်
const selectUser = (user) => {
    activeUserId.value = user.id
    console.log('Selected chat user profile context:', user)
    // ဒီနေရာမှာ Inertia.visit သို့မဟုတ် chat room လှမ်းခေါ်တဲ့ logic ထည့်လို့ရပါတယ်
}
</script>

<style scoped>
/* Scrollbar လေးကို Dark Mode မှာပါ လှပအောင် ညှိထားခြင်း */
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}
</style>