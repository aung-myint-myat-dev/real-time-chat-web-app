<script setup>
defineProps({
    title: {
        type: String,
        default: '',
    },
    subtitle: {
        type: String,
        default: '',
    },
    image: {
        type: String,
        default: '',
    },
    imageAlt: {
        type: String,
        default: 'Card image',
    },
    hoverable: {
        type: Boolean,
        default: false,
    }
})
</script>

<template>
    <div class="dark:bg-zinc-900 rounded-2xl shadow-sm overflow-hidden transition-all duration-200"
        :class="{ 'hover:shadow-md hover:-translate-y-0.5 cursor-pointer': hoverable }">
        <!-- Card Top Image (အကယ်၍ image prop ပါလာပါက) -->
        <div v-if="image" class="aspect-video w-full overflow-hidden bg-gray-100 dark:bg-gray-800">
            <img :src="image" :alt="imageAlt" class="w-full h-full object-cover" />
        </div>

        <!-- Header Section (Slot သို့မဟုတ် Title/Subtitle) -->
        <div v-if="$slots.header || title" class="px-6 pt-6 pb-2">
            <slot name="header">
                <h3 v-if="title" class="text-lg font-semibold text-gray-900 dark:text-white leading-snug">
                    {{ title }}
                </h3>
                <p v-if="subtitle" class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ subtitle }}
                </p>
            </slot>
        </div>

        <!-- Card Body (Default Slot) -->
        <div class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
            <slot />
        </div>

        <!-- Footer Section (Optional) -->
        <div v-if="$slots.footer"
            class="px-6 py-4 bg-gray-50/50 dark:bg-gray-900/50 border-t border-gray-100 dark:border-gray-800/60 flex items-center justify-between text-xs">
            <slot name="footer" />
        </div>
    </div>
</template>