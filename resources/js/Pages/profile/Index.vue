<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '../../layouts/AppLayout.vue';
import { ref } from 'vue';
import Card from '../../components/ui/Card.vue';
import { Camera, Image, Pencil, X } from '@lucide/vue';
import Button from '../../components/ui/Button.vue';

defineOptions({
    layout: (props) => [AppLayout, { title: 'Profile', backUrl: "/chats" }]
});

const page = usePage();
const props = defineProps({
    user: {
        type: Object,
        default: null,
    }
})

/**
 * Updating User Info
 */
const isProfileEditing = ref(false);
const updateForm = useForm({
    name: props.user?.name || '',
    username: props.user?.username || '',
})
const handleEditProfile = () => {
    isProfileEditing.value = true;
    isUploading.value = false;
}
const handleCancelEditProfile = () => {
    isProfileEditing.value = false;
}
const updateUser = () => {
    updateForm.put('/profile', {
        preserveScroll: true,
        onSuccess: () => handleCancelEditProfile(),
    });
}

/**
 * Deleting User Account
 */
const deleteForm = useForm({
    password: '',
})
const deleteUser = () => {
    deleteForm.delete('/profile');
}

/**
 * Uploading User Avatar
 */
const isUploading = ref(false)
const avatarInput = ref(null)
const previewProfileAvatar = ref(null)
const uploadAvatarForm = useForm({
    avatar: null,
})
const handleUploadAvatar = () => {
    isUploading.value = true;
    isProfileEditing.value = false;
}
const handleProfileAvatarFileChange = (e) => {
    const file = e.target.files[0]
    if (file) {
        if (previewProfileAvatar.value) {
            URL.revokeObjectURL(previewProfileAvatar.value);
        }
        previewProfileAvatar.value = URL.createObjectURL(file);
        uploadAvatarForm.avatar = file
    }
}
const handleCancelUploadProfileAvatar = () => {
    isUploading.value = false;
    if (previewProfileAvatar.value) {
        URL.revokeObjectURL(previewProfileAvatar.value)
        previewProfileAvatar.value = null
    }
    uploadAvatarForm.reset()
    if (avatarInput.value) {
        avatarInput.value.value = ''
    }
}

const uploadUserAvatar = () => {
    uploadAvatarForm
        .transform((data) => ({
            ...data,
            _method: 'put',
        }))
        .post('/profile/avatar', {
            preserveScroll: true,
            onSuccess: () => {
                handleCancelUploadProfileAvatar()
            }
        });
}

const deleteUserAvatar = () => {
    if (confirm('Are you sure you want to delete your avatar?')) {
        router.delete('/profile/avatar', {
            preserveScroll: true,
            onSuccess: () => handleCancelUploadProfileAvatar()
        })
    }
}

const handleLogout = () => {

    router.post('/logout', {}, {
        onBefore: () => {
            navigator.sendBeacon('/users/update-last-seen-at');
        }
    });

}

</script>

<template>
    <div class="max-w-2xl mx-auto sm:py-4 lg:py-8 space-y-4">
        <div class="dark:bg-zinc-900 rounded-2xl border border-border-color shadow-sm overflow-hidden transition-all duration-200">
            <!-- Avatar -->
            <div class="h-58 flex items-center justify-center">
                <div v-if="props.user.avatar"
                    class="aspect-square size-45 rounded-full overflow-hidden">
                    <img :src="props.user.avatar" :alt="props.user.name + '-profile'"
                        class="w-full h-full object-cover" />
                </div>
                <div v-else
                    class="size-45 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-800 text-4xl font-bold flex items-center justify-center">
                    {{ props.user?.name?.charAt(0) || 'U' }}
                </div>
            </div>

            <!-- Name and username-->
            <div class="px-4 pt-4 pb-2 text-center">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white leading-snug">
                    {{ props.user.name }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    @{{ props.user.username }}
                </p>
            </div>

            <div class="flex items-center pt-4 px-4 gap-4">
                <Button @click="handleEditProfile" variant="secondary" class="w-full flex flex-col items-center gap-2">
                    <Pencil size="18" />
                    <span class="text-xs text-gray-500">Edit Info</span>
                </Button>

                <Button @click="handleUploadAvatar" variant="secondary" class="w-full flex flex-col items-center gap-2">
                    <Camera size="18" />
                    <span class="text-xs text-gray-500">{{ props.user.avatar ? 'Change Avatar' : 'Upload Avatar'
                        }}</span>
                </Button>
            </div>

            <div class="p-4 text-gray-600 dark:text-gray-300">
                <div v-if="isUploading">
                    <form @submit.prevent="uploadUserAvatar" class="flex flex-col items-start gap-2">
                        <input ref="avatarInput" type="file" accept="image/*" class="hidden" id="avatar-upload-input"
                            @change="handleProfileAvatarFileChange">
                        <div v-if="previewProfileAvatar">
                            <img :src="previewProfileAvatar" alt="privew image" class="aspect-video">
                        </div>

                        <div class="flex flex-col gap-2 justify-center sm:justify-start w-full">
                            <!-- Select File Button -->
                            <label for="avatar-upload-input"
                                class="px-4 py-2 flex items-center gap-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-md text-sm font-medium cursor-pointer transition">
                                <Image size="16" />
                                Choose New Photo
                            </label>

                            <Button v-if="previewProfileAvatar" type="submit" :full-width="true"
                                :disabled="uploadAvatarForm.processing">{{ uploadAvatarForm.processing ? 'Uploading...'
                                    : 'Save Photo' }}</Button>

                            <Button variant="danger" :full-width="true"
                                @click="handleCancelUploadProfileAvatar">Cancel</Button>

                            <Button v-if="props.user.avatar" @click="deleteUserAvatar" variant="danger"
                                :full-width="true">Delete Avatar</Button>

                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">JPG, PNG. Max size 2MB.</p>
                    </form>
                </div>

                <div v-if="isProfileEditing" class="mt-2">
                    <h2 class="text-md mb-2 font-semibold text-gray-900 dark:text-white">Edit Information</h2>

                    <form @submit.prevent="updateUser" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                            <input type="text" v-model="updateForm.name"
                                class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                                placeholder="Your name">
                            <span v-if="updateForm.errors.name" class="text-xs text-rose-500 mt-1 block">{{
                                updateForm.errors.name }}</span>
                        </div>

                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Username</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400 text-sm">@</span>
                                <input type="text" v-model="updateForm.username"
                                    class="w-full pl-8 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-950 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 text-sm transition"
                                    placeholder="username">
                            </div>
                            <span v-if="updateForm.errors.username" class="text-xs text-rose-500 mt-1 block">{{
                                updateForm.errors.username }}</span>
                        </div>

                        <div class="flex justify-end pt-2 gap-2">
                            <Button type="submit" :disabled="updateForm.processing">{{ updateForm.processing ?
                                'Saving...' : 'Save Changes' }}</Button>
                            <Button @click="handleCancelEditProfile" variant="danger">Cancel</Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div
            class="bg-rose-50/50 dark:bg-rose-950/20 p-6 rounded-2xl border border-rose-200 dark:border-rose-900/50 space-y-4">
            <h2 class="text-red-500 font-bold text-2xl">Danger Section</h2>

            <div>
                <div>
                    <h2 class="text-md font-semibold text-red-500">Delete Account</h2>
                    <p class="text-xs text-red-600 my-2">Once your account is deleted, all chat
                        history and data will be permanently removed.</p>
                </div>
    
                <form @submit.prevent="deleteUser" class="space-y-3">
                    <div class="max-w-md">
                        <input type="password" v-model="deleteForm.password" placeholder="Confirm with your password"
                            class="w-full px-4 py-2.5 rounded-xl border border-rose-200 dark:border-rose-900/50 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 text-sm">
                        <span v-if="deleteForm.errors.password" class="text-xs text-rose-500 mt-1 block">{{
                            deleteForm.errors.password }}</span>
                    </div>
                    <Button type="submit" :disabled="deleteForm.processing" variant="danger">{{ deleteForm.processing ? 'Deleting...' : 'Permanently Delete Account' }}</Button>
                </form>
            </div>

            <div>
                <div>
                    <h2 class="text-md font-semibold text-red-500">Logout Account</h2>
                    <p class="text-xs text-red-600 my-2">Once your account is logout, you must be re login again.</p>
                </div>
                <Button @click="handleLogout" variant="danger">
                    Logout
                    <!-- <Link as="button" href="/logout" method="POST">Logout</Link> -->
                </Button>
            </div>
        </div>
    </div>
</template>