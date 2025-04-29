<template>
    <LayoutDashboard>
        <div v-if="loading" class="flex justify-center items-center h-64">
            <LoaderCircle class="h-10 w-10 animate-spin text-pink-400" />
        </div>
        <template v-else-if="licenseKey">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-pink-400">Delete License Key: {{ licenseKey.license_key_uuid }}</h1>
                <button
                    @click="router.push('/mc-admin/license-keys')"
                    class="bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:bg-gray-600 flex items-center"
                >
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    Back to License Keys
                </button>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <div class="mb-6 flex items-start">
                    <div class="mr-4 mt-1 text-red-400">
                        <AlertTriangleIcon class="w-8 h-8" />
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-white">Confirm License Key Deletion</h2>
                        <p class="text-gray-400 mt-2">
                            You are about to delete the license key
                            <span class="font-bold text-white">"{{ licenseKey.license_key_uuid }}"</span>. This action
                            cannot be undone.
                        </p>
                        <p class="text-gray-400 mt-2">
                            If any data is associated with this license key, it may become inaccessible or display
                            incorrectly.
                        </p>
                    </div>
                </div>

                <div class="bg-gray-900/50 rounded-lg p-4 mb-6">
                    <h3 class="text-white font-medium mb-2">License Key Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-400">ID</p>
                            <p class="text-white">{{ licenseKey.id }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Project ID</p>
                            <p class="text-white">{{ licenseKey.project }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">User UUID</p>
                            <p class="text-white">{{ licenseKey.uuid }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">License Key UUID</p>
                            <p class="text-white">{{ licenseKey.license_key_uuid }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400">Status</p>
                            <p class="text-white">
                                {{ licenseKey.status.charAt(0).toUpperCase() + licenseKey.status.slice(1) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400">Expires At</p>
                            <p class="text-white">{{ new Date(licenseKey.expires_at).toLocaleString() }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-gray-400">Context</p>
                            <p class="text-white">{{ licenseKey.context }}</p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="confirmDelete" class="space-y-4">
                    <div class="flex items-center">
                        <input
                            id="confirm"
                            v-model="confirmationChecked"
                            type="checkbox"
                            class="w-4 h-4 text-pink-500 bg-gray-800 border-gray-600 rounded focus:ring-pink-500"
                        />
                        <label for="confirm" class="ml-2 text-sm text-gray-300">
                            I understand that this action cannot be undone
                        </label>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                        <button
                            type="button"
                            @click="router.push('/mc-admin/license-keys')"
                            class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="deleting || !confirmationChecked"
                            class="px-4 py-2 bg-red-500 rounded-lg text-white hover:bg-red-600 transition-colors flex items-center disabled:opacity-50 disabled:pointer-events-none"
                        >
                            <TrashIcon v-if="!deleting" class="w-4 h-4 mr-2" />
                            <LoaderIcon v-else class="animate-spin w-4 h-4 mr-2" />
                            Delete License Key
                        </button>
                    </div>
                </form>
            </div>
        </template>
        <div v-else class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6 text-center">
            <div class="text-red-400 mb-4">
                <AlertCircleIcon class="w-16 h-16 mx-auto" />
            </div>
            <h2 class="text-xl font-semibold text-white">License Key Not Found</h2>
            <p class="text-gray-400 mt-2">
                The license key you're trying to delete doesn't exist or has already been deleted.
            </p>
            <button
                @click="router.push('/mc-admin/license-keys')"
                class="mt-4 px-4 py-2 bg-gray-700 text-white rounded-lg transition-all duration-200 hover:bg-gray-600"
            >
                Back to License Keys
            </button>
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import {
    ArrowLeftIcon,
    TrashIcon,
    LoaderIcon,
    LoaderCircle,
    AlertCircleIcon,
    AlertTriangleIcon,
} from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { useSound } from '@vueuse/sound';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import LicenseKeys from '@/mymythicalid/admin/LicenseKeys';

// Define interfaces for the data structures
interface LicenseKey {
    id: number;
    project: number;
    uuid: string;
    license_key_uuid: string;
    context: string;
    status: 'active' | 'inactive' | 'expired';
    expires_at: string;
}

const router = useRouter();
const route = useRoute();
const licenseKeyId = route.params.id as string;

const loading = ref<boolean>(true);
const deleting = ref<boolean>(false);
const licenseKey = ref<LicenseKey | null>(null);
const confirmationChecked = ref<boolean>(false);
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);

// Fetch license key details
const fetchLicenseKey = async (): Promise<void> => {
    loading.value = true;
    try {
        const response = await LicenseKeys.getLicenseKey(licenseKeyId);

        if (response.success && response.license_key) {
            licenseKey.value = response.license_key;
        } else {
            console.error('Failed to load license key:', response.message);
        }
    } catch (error) {
        console.error('Error fetching license key:', error);
    } finally {
        loading.value = false;
    }
};

const confirmDelete = async () => {
    if (!confirmationChecked.value) {
        playError();
        Swal.fire({
            icon: 'error',
            title: 'Confirmation Required',
            text: 'Please confirm that you understand this action cannot be undone',
            showConfirmButton: true,
        });
        return;
    }

    deleting.value = true;

    try {
        const response = await LicenseKeys.deleteLicenseKey(licenseKeyId);

        if (response.success) {
            playSuccess();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'License key deleted successfully',
                showConfirmButton: true,
            });

            // Navigate back to license keys list after a short delay
            setTimeout(() => {
                router.push('/mc-admin/license-keys');
            }, 1500);
        } else {
            const errorMessages = {
                INVALID_LICENSE_KEY_ID: 'Invalid license key ID',
                LICENSE_KEY_NOT_FOUND: 'License key not found',
                FAILED_TO_DELETE_LICENSE_KEY: 'Server failed to delete the license key',
                INVALID_SESSION: 'Your session has expired. Please log in again.',
            };

            const error_code = response.error_code as keyof typeof errorMessages;
            const errorMessage = errorMessages[error_code] || 'Failed to delete license key';

            playError();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
                footer: 'Please try again or contact support if the issue persists.',
                showConfirmButton: true,
            });

            // If session expired, redirect to login
            if (error_code === 'INVALID_SESSION') {
                setTimeout(() => {
                    window.location.href = '/login?redirect=' + encodeURIComponent(window.location.pathname);
                }, 1500);
            }
        }
    } catch (error) {
        console.error('Error deleting license key:', error);
        playError();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred while deleting the license key',
            footer: 'Please try again or contact support if the issue persists.',
            showConfirmButton: true,
        });
    } finally {
        deleting.value = false;
    }
};

onMounted(() => {
    fetchLicenseKey();
});
</script>
