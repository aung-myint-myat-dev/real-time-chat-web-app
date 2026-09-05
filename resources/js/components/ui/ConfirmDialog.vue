<script setup>
import Button from "./Button.vue";

defineProps({
    title: {
        type: String,
        default: "Are you sure?",
    },
    description: {
        type: String,
        default: "",
    },
    confirmLabel: {
        type: String,
        default: "Delete",
    },
    cancelLabel: {
        type: String,
        default: "Cancel",
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

defineEmits(["cancel", "confirm"]);
</script>

<template>
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="$emit('cancel')"></div>

        <div class="relative w-full max-w-sm rounded-2xl border border-slate-200 bg-white p-5 shadow-xl dark:border-slate-700 dark:bg-slate-900">
            <h3 class="text-base font-semibold text-slate-900 dark:text-slate-50">
                {{ title }}
            </h3>
            <p v-if="description" class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ description }}
            </p>

            <div class="mt-5 flex justify-end gap-2">
                <Button variant="outline" size="sm" :disabled="isLoading" @click="$emit('cancel')">
                    {{ cancelLabel }}
                </Button>
                <Button variant="danger" size="sm" :is-loading="isLoading" @click="$emit('confirm')">
                    {{ confirmLabel }}
                </Button>
            </div>
        </div>
    </div>
</template>
