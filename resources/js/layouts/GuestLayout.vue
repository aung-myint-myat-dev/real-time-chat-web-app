<template>
    <div class="min-h-screen font-brand bg-background flex gap-4">
        <div class="relative flex-1 flex flex-col border border-border-color shadow-xs overflow-hidden">
            <!-- Background image -->
            <img src="https://i.pinimg.com/736x/10/6b/f0/106bf080d95bae41fa7cb0ae72ecddd0.jpg" alt="background image" class="absolute opacity-40 dark:opacity-0 transition-opacity duration-300 w-full h-full object-cover">
            
            <!-- Headr -->
            <GuestLayoutHeader/>

            <!-- Main Container -->
            <div class="flex-1 flex items-center justify-center z-10 px-4">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>
import GuestLayoutHeader from '../components/app/GuestLayoutHeader.vue';
</script>