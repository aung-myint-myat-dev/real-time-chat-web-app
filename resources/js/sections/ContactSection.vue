<template>
    <section class="min-h-screen py-24 flex items-center bg-slate-100/50 dark:bg-slate-900/30">
        <div class="max-w-7xl mx-auto px-6 w-full">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Left Side -->
                <div class="space-y-6">
                    <h2 class="text-4xl font-bold text-slate-900 dark:text-slate-100">Let's Connect</h2>
                    <p class="text-lg text-slate-600 dark:text-slate-300">
                        Need to ask something?
                    </p>
                    <blockquote class="text-slate-500 dark:text-slate-400 italic border-l-4 border-brand-500 pl-4 py-1">
                        "We are always happy to hear feedback, answer inquiries, or collaborate on open-source
                        projects."
                    </blockquote>
                </div>

                <!-- Right Side -->
                <div
                    class="p-8 rounded-2xl bg-white/70 dark:bg-slate-900/70 border border-slate-200/80 dark:border-slate-800 backdrop-blur-md space-y-6">
                    <div class="space-y-4">
                        <a href="mailto:contact@checkchat.com"
                            class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 hover:border-brand-500/50 transition-colors">
                            <span class="text-2xl">✉️</span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">Email Us</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                                    contact@checkchat.com</p>
                            </div>
                        </a>

                        <a href="https://github.com" target="_blank"
                            class="flex items-center gap-4 p-4 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60 hover:border-brand-500/50 transition-colors">
                            <span class="text-2xl">💻</span>
                            <div>
                                <p class="text-xs text-slate-400 uppercase font-semibold">GitHub Repository</p>
                                <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">github.com/checkchat
                                </p>
                            </div>
                        </a>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <a href="https://github.com" target="_blank"
                            class="flex-1 py-3 text-center rounded-xl bg-slate-900 dark:bg-slate-100 hover:bg-slate-800 dark:hover:bg-white text-white dark:text-slate-900 font-semibold text-sm transition-all hover:-translate-y-0.5">
                            GitHub
                        </a>
                        <a href="mailto:contact@checkchat.com"
                            class="flex-1 py-3 text-center rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-semibold text-sm transition-all hover:-translate-y-0.5 shadow-lg shadow-brand-500/20">
                            Send Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>