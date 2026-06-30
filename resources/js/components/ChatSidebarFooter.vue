<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Bookmark, Settings, Star, UserPlus, Users } from '@lucide/vue';

</script>

<template>
    <div
        class="shrink-0 px-5 py-4 flex items-center justify-between border-t border-slate-100 dark:border-slate-800/60 bg-white dark:bg-slate-800 transition-colors duration-200">

        <div class="relative group">
            <Link href="/add-contact"
                class="flex items-center justify-center w-10 h-10 bg-brand-500 hover:bg-brand-600 active:bg-brand-700 text-white rounded-xl shadow-md shadow-brand-500/10 hover:shadow-brand-500/20 hover:scale-105 active:scale-98 transition-all duration-200 cursor-pointer">
            <UserPlus :size="20" stroke-width="2.2" />
            </Link>

            <span
                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-slate-900 dark:bg-slate-700 text-white text-[11px] font-medium rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 shadow-sm whitespace-nowrap z-30">
                Add Contact
            </span>
        </div>

        <div class="relative group">
            <Link href="/contacts"
                class="flex items-center justify-center w-10 h-10 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/40 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:scale-105 active:scale-98 transition-all duration-200 cursor-pointer border border-slate-100 dark:border-slate-700/30">
            <Users :size="20" stroke-width="2.2" />
            </Link>

            <span
                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-slate-900 dark:bg-slate-700 text-white text-[11px] font-medium rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 shadow-sm whitespace-nowrap z-30">
                All Contacts
            </span>
        </div>

        <div class="relative group">
            <Link href="/contacts/favorites"
                class="flex items-center justify-center w-10 h-10 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/40 dark:hover:bg-slate-700 text-amber-500 dark:text-amber-400 rounded-xl hover:scale-105 active:scale-98 transition-all duration-200 cursor-pointer border border-slate-100 dark:border-slate-700/30">
            <Star :size="20" stroke-width="2.2" />
            </Link>

            <span
                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-slate-900 dark:bg-slate-700 text-white text-[11px] font-medium rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 shadow-sm whitespace-nowrap z-30">
                Favorites
            </span>
        </div>

        <div class="relative group">
            <Link href="/settings"
                class="flex items-center justify-center w-10 h-10 bg-slate-50 hover:bg-slate-100 dark:bg-slate-700/40 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl hover:scale-105 active:scale-98 transition-all duration-200 cursor-pointer border border-slate-100 dark:border-slate-700/30 group/btn">
            <Settings :size="20" stroke-width="2.2"
                class="group-hover/btn:rotate-45 transition-transform duration-200" />
            </Link>

            <span
                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2.5 py-1.5 bg-slate-900 dark:bg-slate-700 text-white text-[11px] font-medium rounded-lg opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-200 shadow-sm whitespace-nowrap z-30">
                Settings
            </span>
        </div>

    </div>
</template>