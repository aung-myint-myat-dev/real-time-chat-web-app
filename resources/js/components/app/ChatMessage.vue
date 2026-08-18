<script setup>
import { inject, onMounted, computed, ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import Dropdown from "../ui/Dropdown.vue";
import { MoreVertical, PenBox, Pencil, Trash2 } from "@lucide/vue";

const props = defineProps({
    message: {
        type: Object,
        required: true,
    },
});

defineEmits([
    'edit',
    'delete',
])

const page = usePage();
const authUser = computed(() => page.props.auth.user);
const senderName = computed(() => props.message.user?.name);
const isMe = computed(() => authUser.value.id === props.message.user.id ?? false,);
const isUpdated = computed(() => props.message.created_at !== props.message.updated_at);

const dateFormatter = (timestamp) => {
    const date = new Date(timestamp);
    const rawHour = date.getHours();
    const ampm = rawHour >= 12 ? "PM" : "AM";
    const hour12 = rawHour % 12 || 12;
    const hour = String(hour12).padStart(2, "0");
    const minute = String(date.getMinutes()).padStart(2, "0");
    const second = String(date.getSeconds()).padStart(2, "0");
    const formatted = `${hour}:${minute} ${ampm}`;
    return formatted;
};

// onMounted(() => console.log(props.message.user))
</script>

<template>
    <div :id="`message-${message.id}`" :class="[
        'flex items-end space-x-2 p-2 max-w-[85%] sm:max-w-[70%]',
        isMe ? 'ml-auto flex-row-reverse space-x-reverse' : 'mr-auto',
    ]">
        <div v-if="!isMe"
            class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-800 flex md:hidden items-center justify-center text-xs font-bold text-slate-600 dark:text-slate-300 shrink-0 border border-transparent dark:border-slate-700/50"
        >
            <img v-if="message.user?.avatar" :src="message.user.avatar" class="rounded-full"/>
            <span v-else class="rounded-full">{{ senderName ? senderName.charAt(0).toUpperCase() : "U" }}</span>
        </div>

        <div class="flex flex-col relative">
            <div :class="[
                'px-4 py-2.5 text-sm shadow-xs wrap-break-word max-w-full transition-colors duration-200',
                isMe
                    ? 'bg-brand-500 text-white rounded-2xl rounded-br-none shadow-brand-500/5'
                    : 'bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 rounded-2xl rounded-bl-none border border-slate-200 dark:border-slate-700/60',
            ]">
                <p class="leading-relaxed">{{ message.body }}</p>
            </div>

            <span :class="[
                'text-[10px] text-slate-400 dark:text-slate-500 mt-1',
                message.edited_at ?? 'flex items-center gap-1',
                isMe ? 'text-right justify-end' : 'text-left justify-start',
            ]">
                <span v-if="message.edited_at">Edited </span>
                <span>
                    {{ message.edited_at ? dateFormatter(message.edited_at) : dateFormatter(message.created_at) }}
                </span>
            </span>

            <div class="absolute top-1/2 -translate-y-1/2 -left-8">
                <Dropdown v-if="isMe">
                    <!-- Trigger -->
                    <template #trigger>
                        <button type="button" class="flex items-center justify-center
                                           w-7 h-7 rounded-full
                                           text-slate-400
                                           hover:text-slate-600
                                           dark:text-slate-500
                                           dark:hover:text-slate-300
                                           hover:bg-slate-100
                                           dark:hover:bg-slate-800
                                           transition-colors" aria-label="Message options">
                            <MoreVertical :size="17" />
                        </button>
                    </template>
                    <!-- Edit -->
                    <button type="button" @click="$emit('edit', message)" class="w-full flex items-center gap-2
                                       px-3 py-2 text-sm
                                       text-slate-700 dark:text-slate-200
                                       hover:bg-slate-100
                                       dark:hover:bg-slate-700
                                       transition-colors">
                        <Pencil :size="14" />
                        <span>Edit</span>
                    </button>
                    <!-- Delete -->
                    <button type="button" @click="$emit('delete', message.id)" class="w-full flex items-center gap-2
                                       px-3 py-2 text-sm
                                       text-red-500
                                       hover:bg-red-50
                                       dark:hover:bg-red-950/30
                                       transition-colors">
                        <Trash2 :size="14" />
                        <span>Delete</span>
                    </button>
                </Dropdown>
            </div>
        </div>

    </div>
</template>
