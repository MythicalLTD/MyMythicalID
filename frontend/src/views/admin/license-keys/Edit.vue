<template>
    <LayoutDashboard>
        <div v-if="loading" class="flex justify-center items-center h-64">
            <LoaderCircle class="h-10 w-10 animate-spin text-pink-400" />
        </div>
        <template v-else-if="licenseKey">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-pink-400">Edit License Key: {{ licenseKey.license_key_uuid }}</h1>
                <button
                    @click="router.push('/mc-admin/license-keys')"
                    class="bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:bg-gray-600 flex items-center"
                >
                    <ArrowLeftIcon class="w-4 h-4 mr-2" />
                    Back to License Keys
                </button>
            </div>

            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <form @submit.prevent="saveLicenseKey" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="project" class="block text-sm font-medium text-gray-400 mb-1">Project</label>
                            <select
                                id="project"
                                v-model="licenseKeyForm.project"
                                required
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            >
                                <option v-for="project in projects" :key="project.id" :value="project.id">
                                    {{ project.name }}
                                </option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Select the project this license key belongs to</p>
                        </div>

                        <div>
                            <label for="user" class="block text-sm font-medium text-gray-400 mb-1">User</label>
                            <select
                                id="user"
                                v-model="licenseKeyForm.uuid"
                                required
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            >
                                <option value="" disabled>Select a user</option>
                                <option v-for="user in users" :key="user.uuid" :value="user.uuid">
                                    {{ user.username }} ({{ user.email }})
                                </option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Select the user this license key belongs to</p>
                        </div>

                        <div>
                            <label for="license_key_uuid" class="block text-sm font-medium text-gray-400 mb-1"
                                >License Key UUID</label
                            >
                            <div class="flex space-x-2">
                                <input
                                    id="license_key_uuid"
                                    v-model="licenseKeyForm.license_key_uuid"
                                    type="text"
                                    required
                                    class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                                    placeholder="e.g. xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx"
                                />
                                <button
                                    type="button"
                                    @click="generateLicenseKey"
                                    class="px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors flex items-center"
                                >
                                    <KeyIcon class="w-4 h-4 mr-2" />
                                    Generate
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-1">The unique identifier for this license key</p>
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-400 mb-1">Status</label>
                            <select
                                id="status"
                                v-model="licenseKeyForm.status"
                                required
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            >
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="expired">Expired</option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">The current status of this license key</p>
                        </div>

                        <div>
                            <label for="instance" class="block text-sm font-medium text-gray-400 mb-1">Instance</label>
                            <select
                                id="instance"
                                v-model="licenseKeyForm.instance"
                                required
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            >
                                <option value="0">Select an instance</option>
                                <option v-for="instance in instances" :key="instance.id" :value="instance.id">
                                    {{ instance.companyName }} ({{ instance.instanceUrl }}) - {{ instance.hostingType }}
                                </option>
                            </select>
                            <p class="text-xs text-gray-400 mt-1">Select the instance this license key belongs to</p>
                        </div>

                        <div class="md:col-span-2">
                            <label for="context" class="block text-sm font-medium text-gray-400 mb-1">Context</label>
                            <textarea
                                id="context"
                                v-model="licenseKeyForm.context"
                                required
                                rows="3"
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="e.g. License key for premium features"
                            ></textarea>
                            <p class="text-xs text-gray-400 mt-1">Additional context or notes about this license key</p>
                        </div>

                        <div>
                            <label for="expires_at" class="block text-sm font-medium text-gray-400 mb-1"
                                >Expiration Date</label
                            >
                            <input
                                id="expires_at"
                                v-model="licenseKeyForm.expires_at"
                                type="datetime-local"
                                required
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            />
                            <p class="text-xs text-gray-400 mt-1">When this license key expires</p>
                        </div>
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
                            :disabled="saving"
                            class="px-4 py-2 bg-gradient-to-r from-pink-500 to-violet-500 rounded-lg text-white hover:opacity-90 transition-colors flex items-center"
                        >
                            <LoaderIcon v-if="saving" class="animate-spin w-4 h-4 mr-2" />
                            <SaveIcon v-else class="w-4 h-4 mr-2" />
                            Save Changes
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
            <p class="text-gray-400 mt-2">The license key you're trying to edit doesn't exist or has been deleted.</p>
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
import { ArrowLeftIcon, SaveIcon, LoaderIcon, LoaderCircle, AlertCircleIcon, KeyIcon } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { useSound } from '@vueuse/sound';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import LicenseKeys from '@/mymythicalid/admin/LicenseKeys';
import Projects from '@/mymythicalid/admin/Projects';

interface Project {
    id: number;
    name: string;
}

interface User {
    id: number;
    username: string;
    email: string;
    uuid: string;
}

interface Instance {
    id: number;
    uuid: string;
    companyName: string;
    instanceUrl: string;
    hostingType: string;
}

interface LicenseKey {
    id: number;
    project: number;
    uuid: string;
    license_key_uuid: string;
    context: string;
    status: 'active' | 'inactive' | 'expired';
    expires_at: string;
    instance: number;
}

const router = useRouter();
const route = useRoute();
const licenseKeyId = route.params.id as string;

// Format date for datetime-local input
const formatDateForInput = (dateString: string) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toISOString().slice(0, 16); // Format: YYYY-MM-DDTHH:mm
};

const loading = ref<boolean>(true);
const saving = ref<boolean>(false);
const licenseKey = ref<LicenseKey | null>(null);
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);

const projects = ref<Project[]>([]);
const users = ref<User[]>([]);
const instances = ref<Instance[]>([]);

// Form state
const licenseKeyForm = ref({
    project: '',
    uuid: '',
    license_key_uuid: '',
    context: '',
    status: 'active' as 'active' | 'inactive' | 'expired',
    expires_at: '',
    instance: '',
});

// Generate a random license key UUID
const generateLicenseKey = () => {
    const uuid = 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        const randomValues = new Uint8Array(1);
        window.crypto.getRandomValues(randomValues);
        const r = randomValues[0] % 16;
        const v = c === 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
    licenseKeyForm.value.license_key_uuid = uuid;
};

// Fetch license key details and related data
const fetchData = async () => {
    loading.value = true;
    try {
        const [licenseKeyResponse, projectsResponse, usersResponse, instancesResponse] = await Promise.all([
            LicenseKeys.getLicenseKey(licenseKeyId),
            Projects.getProjects(),
            fetch('/api/admin/users').then((res) => res.json()),
            fetch('/api/admin/mythicaldash/instances').then((res) => res.json()),
        ]);

        if (licenseKeyResponse.success && licenseKeyResponse.license_key) {
            licenseKey.value = licenseKeyResponse.license_key;
            licenseKeyForm.value = {
                project: licenseKeyResponse.license_key.project.toString(),
                uuid: licenseKeyResponse.license_key.uuid,
                license_key_uuid: licenseKeyResponse.license_key.license_key_uuid,
                context: licenseKeyResponse.license_key.context,
                status: licenseKeyResponse.license_key.status,
                expires_at: formatDateForInput(licenseKeyResponse.license_key.expires_at),
                instance: licenseKeyResponse.license_key.instance.toString(),
            };
        }

        if (projectsResponse.success) {
            projects.value = projectsResponse.projects;
        }

        if (usersResponse.success) {
            users.value = usersResponse.users;
        }

        if (instancesResponse.success) {
            instances.value = instancesResponse.instances;
        }
    } catch (error) {
        console.error('Error fetching data:', error);
    } finally {
        loading.value = false;
    }
};

const saveLicenseKey = async () => {
    saving.value = true;

    try {
        const response = await LicenseKeys.updateLicenseKey(
            licenseKeyId,
            licenseKeyForm.value.project,
            licenseKeyForm.value.uuid,
            licenseKeyForm.value.license_key_uuid,
            licenseKeyForm.value.context,
            licenseKeyForm.value.status,
            licenseKeyForm.value.instance,
            licenseKeyForm.value.expires_at,
        );

        if (response.success) {
            playSuccess();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'License key updated successfully',
                showConfirmButton: true,
            });

            // Navigate back to license keys list after a short delay
            setTimeout(() => {
                router.push('/mc-admin/license-keys');
            }, 1500);
        } else {
            const errorMessages = {
                MISSING_REQUIRED_FIELDS: 'Please fill in all required fields',
                INVALID_PROJECT_ID: 'Invalid project selected',
                INVALID_USER_UUID: 'Invalid user selected',
                INVALID_STATUS: 'Invalid status selected',
                INVALID_EXPIRATION_DATE: 'Invalid expiration date',
                FAILED_TO_UPDATE_LICENSE_KEY: 'Server failed to update the license key',
                INVALID_SESSION: 'Your session has expired. Please log in again.',
                INVALID_LICENSE_KEY_ID: 'Invalid license key ID',
                LICENSE_KEY_NOT_FOUND: 'License key not found',
            };

            const error_code = response.error_code as keyof typeof errorMessages;
            const errorMessage = errorMessages[error_code] || 'Failed to update license key';

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
        console.error('Error updating license key:', error);
        playError();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred while updating the license key',
            footer: 'Please try again or contact support if the issue persists.',
            showConfirmButton: true,
        });
    } finally {
        saving.value = false;
    }
};

onMounted(() => {
    fetchData();
});
</script>
