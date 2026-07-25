

<template>
  <GuestLayout :is-authenticating="true">
    <div class="w-full max-w-md bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-2xl shadow-2xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-slate-800 p-6 sm:p-8 transition-all duration-300 hover:border-slate-300 dark:hover:border-slate-700">
      
      <!-- Card Header -->
      <div class="text-center mb-6">
        <h2 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white">
          Welcome back
        </h2>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
          Sign in to your <span class="font-semibold text-brand-500">CheckChat</span> account
        </p>
      </div>

      <!-- Status Message (e.g. Password reset link sent) -->
      <div v-if="status" class="mb-4 text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800/50 p-3 rounded-xl">
        {{ status }}
      </div>

      <!-- Login Form -->
      <form @submit.prevent="submit" class="space-y-4">

        <!-- Email or Username Input -->
        <div class="space-y-1.5">
          <label class="text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">
            Email or Username
          </label>
          <input 
            v-model="form.login" 
            type="text" 
            required 
            autofocus
            placeholder="name@example.com"
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all duration-200" 
          />
          <div v-if="form.errors.login || form.errors.email" class="text-xs text-rose-500 font-medium pt-0.5">
            {{ form.errors.login || form.errors.email }}
          </div>
        </div>

        <!-- Password Input -->
        <div class="space-y-1.5">
          <div class="flex items-center justify-between">
            <label class="text-xs font-semibold uppercase tracking-wider text-slate-600 dark:text-slate-300">
              Password
            </label>
            <Link 
              v-if="canResetPassword"
              :href="route('password.request')" 
              class="text-xs text-brand-500 hover:underline font-medium"
            >
              Forgot password?
            </Link>
          </div>
          <input 
            v-model="form.password" 
            type="password" 
            required 
            placeholder="••••••••"
            class="w-full px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700/80 bg-white/50 dark:bg-slate-800/50 text-slate-900 dark:text-white placeholder-slate-400 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 focus:outline-none transition-all duration-200" 
          />
          <div v-if="form.errors.password" class="text-xs text-rose-500 font-medium pt-0.5">
            {{ form.errors.password }}
          </div>
        </div>

        <!-- Remember Me Checkbox -->
        <div class="flex items-center justify-between pt-1">
          <label class="flex items-center gap-2 cursor-pointer">
            <input 
              v-model="form.remember" 
              type="checkbox" 
              class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-brand-500 focus:ring-brand-500/20 dark:bg-slate-800"
            />
            <span class="text-xs font-medium text-slate-600 dark:text-slate-400">Remember me</span>
          </label>
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          :disabled="form.processing"
          class="w-full py-3 mt-2 rounded-xl bg-brand-500 hover:bg-brand-600 active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed text-white font-semibold transition-all shadow-lg shadow-brand-500/25 hover:shadow-brand-500/35"
        >
          <span v-if="form.processing" class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-4 w-4 text-white" viewBox="0 0 24 24" fill="none">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Signing in...
          </span>
          <span v-else>Sign in</span>
        </button>
      </form>

      <!-- Switch to Register Link -->
      <p class="mt-6 text-xs text-center text-slate-500 dark:text-slate-400">
        Don't have an account? 
        <Link 
          href="/auth/register" 
          class="text-brand-500 font-semibold hover:underline hover:text-brand-600 transition-colors"
        >
          Create one
        </Link>
      </p>

    </div>
  </GuestLayout>
</template>

<script setup>
import GuestLayout from '../../layouts/GuestLayout.vue'
import { Link, useForm } from '@inertiajs/vue3'

defineProps({
  canResetPassword: {
    type: Boolean,
    default: false,
  },
  status: {
    type: String,
    default: null,
  },
})

const form = useForm({
  login: '',
  password: '',
  remember: false,
})

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  })
}
</script>