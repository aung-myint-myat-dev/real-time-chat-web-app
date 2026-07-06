<template>
    <GuestLayout>
        <div class="min-h-[80vh] flex flex-col justify-center items-center px-4">
            <div
                class="w-full max-w-md bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 text-center space-y-6">

                <div
                    class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 animate-pulse">
                    <Mail/>
                        
                </div>

                <div class="space-y-2">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Verify your email
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed px-2">
                        We sent a verification link to your email address. Please check your inbox and click the link to
                        activate your account.
                    </p>
                </div>

                <div
                    class="bg-amber-50 dark:bg-amber-950/20 border border-amber-100 dark:border-amber-900/50 rounded-xl p-3 text-xs text-amber-700 dark:text-amber-400">
                    💡 If you don't receive the email within a few minutes, please check your spam folder or click below
                    to resend.
                </div>

                <div class="pt-2">
                    <button @click="handleResend" :disabled="isProcessing"
                        class="w-full inline-flex justify-center items-center px-5 py-3 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-indigo-200 dark:hover:shadow-none transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <span v-if="isProcessing" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Sending Link...
                        </span>
                        <span v-else>Resend Link</span>
                    </button>
                </div>

            </div>
        </div>
    </GuestLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '../../layouts/GuestLayout.vue';
import { Mail } from '@lucide/vue';

const form = useForm({});
const isProcessing = ref(false);

const handleResend = () => {
    isProcessing.value = true;

    form.post('/email/verification-notification', {
        onFinish: () => {
            isProcessing.value = false;
        }
    });
};
</script>