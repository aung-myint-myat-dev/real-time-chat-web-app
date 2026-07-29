<template>
  <WhenVisible v-bind="$attrs" @onVisible="handleVisible" />
</template>

<script setup>
import { WhenVisible } from '@inertiajs/vue3';

const emit = defineEmits(['onVisible']);

const handleVisible = () => {
  emit('onVisible');
};
</script>