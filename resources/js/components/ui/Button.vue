<template>
    <button :type="type" :disabled="disabled || isLoading" :class="[
        'inline-flex items-center justify-center font-medium transition-all duration-200 cursor-pointer focus:outline-hidden active:scale-98 disabled:opacity-50 disabled:cursor-not-allowed rounded-md',
        variants[variant],
        sizes[size],
        fullWidth ? 'w-full' : ''
    ]" v-bind="$attrs">

        <svg v-if="isLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-current" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>

        <span v-if="$slots.icon && !isLoading" class="mr-2 inline-flex items-center">
            <slot name="icon" />
        </span>

        <slot>Button</slot>
    </button>
</template>

<script setup>

const props = defineProps({
    type: {
        type: String,
        default: 'button'
    },
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'danger', 'outline'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    disabled: {
        type: Boolean,
        default: false
    },
    isLoading: {
        type: Boolean,
        default: false
    },
    fullWidth: {
        type: Boolean,
        default: false
    }
})

const variants = {
    primary: 'bg-brand-500 text-white hover:bg-brand-600 shadow-sm',
    secondary: 'bg-gray-100 text-gray-900 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-100 dark:hover:bg-gray-700',
    danger: 'bg-red-600 text-white hover:bg-red-700 shadow-sm',
    outline: 'border border-border-color text-text-color bg-transparent hover:bg-gray-50 dark:hover:bg-gray-800',
    ghost: 'hover:bg-gray-200 hover:dark:bg-gray-800'
}

// Sizes Object
const sizes = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-5 py-2.5 text-base'
}
</script>