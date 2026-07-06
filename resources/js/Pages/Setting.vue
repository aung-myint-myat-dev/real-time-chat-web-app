<script setup>
import { ref } from 'vue';
import { useForm, usePage } from '@inertiajs/vue3';
// Laravel Auth user ရဲ့ လက်ရှိ Email Verification Status ကို ယူသုံးဖို့ (Optional)
// သို့မဟုတ် client side မှာပဲ အရင်စမ်းချင်ရင် ref(false) နဲ့ စမ်းနိုင်ပါတယ်
const user = usePage().props.auth?.user;
const isEmailVerified = ref(user?.email_verified_at ? true : false); 

// Form state အတွက် Inertia form helper
const form = useForm({
    notifications: true,
    darkMode: false,
    language: 'en',
    email: user?.email || 'user@example.com',
});

// Email Verification လှမ်းလုပ်မယ့် function
const verifyEmail = () => {
    if (isEmailVerified.value) return;

    // Laravel API/Route ဆီကို Inertia နဲ့ post တင်မယ့်ပုံစံ
    form.post(route('verification.send'), {
        onSuccess: () => {
            // Success ဖြစ်သွားရင် Button ကို disabled လုပ်ဖို့ state ချိန်းမယ်
            isEmailVerified.value = true;
            alert('Verification email sent successfully!');
        },
        onError: (errors) => {
            console.error(errors);
        }
    });
};

const savePreferences = () => {
    form.put(route('settings.preferences.update'), {
        onSuccess: () => alert('Preferences updated!')
    });
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto space-y-6">
            
            <div>
                <h1 class="text-2xl font-bold text-gray-950 dark:text-white">Settings</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Manage your chat application preferences and account status.</p>
            </div>

            <hr class="border-gray-200 dark:border-gray-800" />

            <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Account Settings</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                        <input 
                            type="email" 
                            v-model="form.email" 
                            disabled
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 cursor-not-allowed text-sm"
                        />
                    </div>

                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">Email Verification</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ isEmailVerified ? 'Your email is verified and secure.' : 'Please verify your email to secure your account.' }}
                            </p>
                        </div>
                        
                        <div>
                            <button 
                                @click="verifyEmail"
                                :disabled="isEmailVerified || form.processing"
                                :class="[
                                    'w-full sm:w-auto px-4 py-2 rounded-md text-xs font-semibold shadow-sm transition-colors duration-200',
                                    isEmailVerified 
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 cursor-not-allowed border border-emerald-200 dark:border-emerald-800' 
                                        : 'bg-indigo-600 text-white hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50'
                                ]"
                            >
                                <span v-if="isEmailVerified">✓ Verified Email</span>
                                <span v-else-if="form.processing">Sending...</span>
                                <span v-else>Verify Email</span>
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Chat Preferences</h2>
                
                <form @submit.prevent="savePreferences" class="space-y-4">
                    <div class="flex items-center justify-between py-2">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Push Notifications</label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Receive alerts for new messages.</span>
                        </div>
                        <input 
                            type="checkbox" 
                            v-model="form.notifications"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                    </div>

                    <div class="flex items-center justify-between py-2">
                        <div class="flex flex-col">
                            <label class="text-sm font-medium text-gray-900 dark:text-white">Dark Mode</label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Switch between light and dark themes.</span>
                        </div>
                        <input 
                            type="checkbox" 
                            v-model="form.darkMode"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Language</label>
                        <select 
                            v-model="form.language"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        >
                            <option value="en">English</option>
                            <option value="mm">မြန်မာဘာသာ</option>
                        </select>
                    </div>

                    <div class="pt-4 flex justify-end">
                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="w-full sm:w-auto px-4 py-2 bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-gray-800 dark:hover:bg-gray-100 font-medium text-sm rounded-md shadow transition-colors"
                        >
                            Save Changes
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</template>