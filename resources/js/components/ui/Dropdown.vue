<script setup>
import { ref, onMounted, onBeforeUnmount } from "vue";

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
        <!-- Trigger -->
        <button
            type="button"
            @click="toggle"
        >
            <slot name="trigger" />
        </button>

        <!-- Dropdown -->
        <div
            v-if="isOpen"
            class="absolute right-8 top-1/2 -translate-y-1/2 z-50 mt-2
                   w-32 overflow-hidden rounded-lg
                   border border-slate-200
                   bg-white shadow-lg
                   dark:border-slate-700
                   dark:bg-slate-800"
            @click="close"
        >
            <slot />
        </div>
    </div>
</template>