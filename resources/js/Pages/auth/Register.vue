<template>
    <GuestLayout>
        <div
            class="w-full max-w-md bg-white dark:bg-slate-800/80 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-800/50 p-8 transition-all">
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-slate-900 dark:text-white">Create your account</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Get started with CheckChat today</p>
            </div>

            <form @submit.prevent="submit" class="space-y-4">

                <!-- Name Input -->
                <div class="space-y-2">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Full Name</label>
                    <input v-model="form.name" type="text" required placeholder="Alex Mercer"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.name" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.name }}
                    </div>
                </div>

                <!-- Username Input -->
                <div class="space-y-2">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Username</label>
                    <input v-model="form.username" type="text" required placeholder="alex_mercer"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.username" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.username }}
                    </div>
                </div>

                <!-- Email Input -->
                <div class="space-y-1">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Email Address</label>
                    <input v-model="form.email" type="email" required placeholder="name@example.com"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.email" class="text-xs text-rose-500 mt-1 font-medium">{{ form.errors.email }}
                    </div>
                </div>

                <!-- Password Input -->
                <div class="space-y-1">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Password</label>
                    <input v-model="form.password" type="password" required placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.password" class="text-xs text-rose-500 mt-1 font-medium">{{
                        form.errors.password }}</div>
                </div>

                <!-- Password Confirmation Input -->
                <div class="space-y-1">
                    <label class="text-sm block font-medium text-slate-700 dark:text-slate-300">Confirm Password</label>
                    <input v-model="form.password_confirmation" type="password" required placeholder="••••••••"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-transparent text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-hidden transition-all" />
                    <div v-if="form.errors.password_confirmation" class="text-xs text-rose-500 mt-1 font-medium">{{
                        form.errors.password_confirmation }}</div>
                </div>

                <!-- Register Button -->
                <button type="submit" :disabled="form.processing"
                    class="w-full py-2.5 mt-2 rounded-xl bg-brand-500 hover:bg-brand-600 disabled:opacity-50 text-white font-semibold transition-all shadow-md shadow-brand-500/10">
                    {{ form.processing ? 'Registering...' : 'Register Account' }}
                </button>
            </form>

            <!-- Login link -->
            <p class="mt-6 text-sm text-center text-slate-500 dark:text-slate-400">
                Already have an account? <Link href="/auth/login" class="text-brand-500 font-medium hover:underline">Log in
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>

<script setup>
import GuestLayout from '@/layouts/GuestLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    username: '',
    email: '',
    password: '',
    password_confirmation: '',
})

const submit = () => {
    form.post('/auth/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}
</script>