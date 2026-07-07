<template>
    <GuestLayout>
        <div
            class="w-full max-w-md bg-white dark:bg-slate-800/80 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800/50 p-8 transition-all">

            <!-- From Head -->
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Welcome back</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Please login to your CheckChat account</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Email Input -->
                <div class="space-y-2">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Email Address</label>
                    <input v-model="form.email" type="email" required placeholder="name@example.com"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.email" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.email }}
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-2">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <input v-model="form.password" type="password" required placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.password" class="text-xs text-rose-500 mt-1 font-medium">{{
                        form.errors.password }}</div>
                </div>

                <!-- Remember Me Input -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center">
                        <input v-model="form.remember_me" type="checkbox"
                            class="rounded border-slate-200 dark:border-slate-700 bg-transparent text-brand-500 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                        <span class="ml-2 text-sm text-slate-700 dark:text-slate-300">Remember me</span>
                    </label>
                </div>

                <!-- Signin Button -->
                <button type="submit" :disabled="form.processing"
                    class="w-full py-2.5 mt-2 rounded-xl bg-brand-500 hover:bg-brand-600 disabled:opacity-50 text-white font-semibold transition-all shadow-md shadow-brand-500/10">
                    {{ form.processing ? 'Signing In...' : 'Sign In' }}
                </button>
            </form>

            <p class="mt-6 text-sm text-center text-slate-500 dark:text-slate-400">
                New here? <Link href="/auth/register" class="text-brand-500 font-medium hover:underline">Create an account
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    email: '',
    password: '',
    remember_me: ''
})

const submit = () => {
    form.post('/auth/login', {
        onFinish: () => form.reset('password'),
    })
}
</script>