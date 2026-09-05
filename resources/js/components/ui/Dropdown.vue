<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

const props = defineProps({
    menuClass: {
        type: String,
        default: "right-8 top-1/2 -translate-y-1/2",
    },
});

const isOpen = ref(false);
const dropdownRef = ref(null);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const close = () => {
    isOpen.value = false;
};

const handleClickOutside = (event) => {
    if (!dropdownRef.value?.contains(event.target)) {
        close();
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<template>
    <div
        ref="dropdownRef"
        class="relative inline-block"
    >
        <button
            type="button"
            @click.stop="toggle"
        >
            <slot name="trigger" />
        </button>

        <div
            v-if="isOpen"
            :class="[
                'absolute z-50 mt-2 w-40 overflow-hidden rounded-lg',
                'border border-slate-200 bg-white shadow-lg',
                'dark:border-slate-700 dark:bg-slate-800',
                props.menuClass,
            ]"
            @click.stop="close"
        >
            <slot />
        </div>
    </div>
</template>
