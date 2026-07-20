<script setup>
import AppLayout from '../../layouts/AppLayout.vue';

defineOptions({
    layout: (props) => [AppLayout, { title: 'Profile' }],
})

const props = defineProps({
    user: {
        type: Object,
        default: null
    }
})

</script>

<template>
    <ul>
        <li>{{ props.user?.name }}</li>
        <li>{{ props.user?.username }}</li>
        <li>{{ props.user?.email }}</li>
    </ul>
</template>