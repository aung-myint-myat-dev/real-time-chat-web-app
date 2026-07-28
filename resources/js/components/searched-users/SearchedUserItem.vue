<script setup>
import { computed, inject, onMounted } from 'vue';

const props = defineProps({
    user: Object,
})

// Generate fallback initial from name
const initial = computed(() => {
    return props.user.name?.charAt(0).toUpperCase() || '?';
});

const { handleSelectedSearchedUser } = inject("handleSelectedSearchedUser");

onMounted(() => {
    console.log(props.user);
})
</script>

<template>
    <button type="button" @click="handleSelectedSearchedUser(props.user)"
        class="w-full text-left group relative flex items-center gap-3.5 p-3 rounded-2xl cursor-pointer transition-colors duration-200 hover:bg-slate-100 dark:hover:bg-slate-800/60">
        <!-- Avatar Container -->
        <div class="relative size-11 shrink-0">
            <img v-if="props.user.avatar" :src="props.user.avatar" :alt="props.user.name"
                class="size-full object-cover rounded-full ring-2 ring-slate-100 dark:ring-slate-800 group-hover:ring-slate-200 dark:group-hover:ring-slate-700 transition-all duration-200" />
            <div v-else
                class="size-full flex items-center justify-center rounded-full ring-2 ring-slate-100 dark:ring-slate-800 bg-zinc-500 text-white font-semibold group-hover:ring-slate-200 dark:group-hover:ring-slate-700 transition-all duration-200">
                {{ initial }}
            </div>
        </div>

        <!-- User Information -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2 mb-0.5">
                <h3 class="text-sm font-semibold truncate tracking-tight text-slate-800 dark:text-slate-100">
                    {{ props.user.name }}
                </h3>
            </div>

            <p class="text-xs text-slate-500 dark:text-slate-400 truncate leading-relaxed">
                @{{ props.user.username }}
            </p>
        </div>
    </button>
</template>