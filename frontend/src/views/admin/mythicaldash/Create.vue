<template>
    <LayoutDashboard>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-400">Create MythicalDash Instance</h1>
            <button
                @click="router.push('/mc-admin/mythicaldash')"
                class="bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:bg-gray-600 flex items-center"
            >
                <ArrowLeftIcon class="w-4 h-4 mr-2" />
                Back to Instances
            </button>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
            <form @submit.prevent="saveInstance" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project and User Selection -->
                    <div>
                        <label for="project" class="block text-sm font-medium text-gray-400 mb-1">Project</label>
                        <select
                            id="project"
                            v-model="instanceForm.project"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        >
                            <option value="">Select a project</option>
                            <option v-for="project in projects" :key="project.id" :value="project.id">
                                {{ project.name }}
                            </option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Select the project for this instance</p>
                    </div>

                    <div>
                        <label for="user" class="block text-sm font-medium text-gray-400 mb-1">User</label>
                        <select
                            id="user"
                            v-model="instanceForm.user"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        >
                            <option value="">Select a user</option>
                            <option v-for="user in users" :key="user.uuid" :value="user.uuid">
                                {{ user.first_name }} {{ user.last_name }} ({{ user.email }})
                            </option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Select the user who will own this instance</p>
                    </div>

                    <!-- Company Information -->
                    <div>
                        <label for="companyName" class="block text-sm font-medium text-gray-400 mb-1"
                            >Company Name</label
                        >
                        <input
                            id="companyName"
                            v-model="instanceForm.companyName"
                            type="text"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="e.g. My Company"
                        />
                    </div>

                    <div>
                        <label for="companyWebsite" class="block text-sm font-medium text-gray-400 mb-1"
                            >Company Website</label
                        >
                        <input
                            id="companyWebsite"
                            v-model="instanceForm.companyWebsite"
                            type="url"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="e.g. https://mycompany.com"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label for="businessDescription" class="block text-sm font-medium text-gray-400 mb-1"
                            >Business Description</label
                        >
                        <textarea
                            id="businessDescription"
                            v-model="instanceForm.businessDescription"
                            required
                            rows="3"
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="Describe your business"
                        ></textarea>
                    </div>

                    <!-- Hosting Information -->
                    <div>
                        <label for="hostingType" class="block text-sm font-medium text-gray-400 mb-1"
                            >Hosting Type</label
                        >
                        <select
                            id="hostingType"
                            v-model="instanceForm.hostingType"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        >
                            <option value="free">Free</option>
                            <option value="paid">Paid</option>
                            <option value="both">Both</option>
                        </select>
                    </div>

                    <div>
                        <label for="serverType" class="block text-sm font-medium text-gray-400 mb-1">Server Type</label>
                        <select
                            id="serverType"
                            v-model="instanceForm.serverType"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        >
                            <option value="vps">VPS</option>
                            <option value="dedicated">Dedicated</option>
                            <option value="docker">Docker</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="currentUsers" class="block text-sm font-medium text-gray-400 mb-1"
                            >Current Users</label
                        >
                        <input
                            id="currentUsers"
                            v-model="instanceForm.currentUsers"
                            type="number"
                            required
                            min="0"
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="expectedUsers" class="block text-sm font-medium text-gray-400 mb-1"
                            >Expected Users</label
                        >
                        <input
                            id="expectedUsers"
                            v-model="instanceForm.expectedUsers"
                            type="number"
                            required
                            min="0"
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="serverCount" class="block text-sm font-medium text-gray-400 mb-1"
                            >Server Count</label
                        >
                        <input
                            id="serverCount"
                            v-model="instanceForm.serverCount"
                            type="number"
                            required
                            min="1"
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="instanceUrl" class="block text-sm font-medium text-gray-400 mb-1"
                            >Instance URL</label
                        >
                        <input
                            id="instanceUrl"
                            v-model="instanceForm.instanceUrl"
                            type="url"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="e.g. https://instance.example.com"
                        />
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <label for="primaryEmail" class="block text-sm font-medium text-gray-400 mb-1"
                            >Primary Email</label
                        >
                        <input
                            id="primaryEmail"
                            v-model="instanceForm.primaryEmail"
                            type="email"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="abuseEmail" class="block text-sm font-medium text-gray-400 mb-1">Abuse Email</label>
                        <input
                            id="abuseEmail"
                            v-model="instanceForm.abuseEmail"
                            type="email"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="supportEmail" class="block text-sm font-medium text-gray-400 mb-1"
                            >Support Email</label
                        >
                        <input
                            id="supportEmail"
                            v-model="instanceForm.supportEmail"
                            type="email"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <!-- Owner Information -->
                    <div>
                        <label for="ownerFirstName" class="block text-sm font-medium text-gray-400 mb-1"
                            >Owner First Name</label
                        >
                        <input
                            id="ownerFirstName"
                            v-model="instanceForm.ownerFirstName"
                            type="text"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="ownerLastName" class="block text-sm font-medium text-gray-400 mb-1"
                            >Owner Last Name</label
                        >
                        <input
                            id="ownerLastName"
                            v-model="instanceForm.ownerLastName"
                            type="text"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>

                    <div>
                        <label for="ownerBirthDate" class="block text-sm font-medium text-gray-400 mb-1"
                            >Owner Birth Date</label
                        >
                        <input
                            id="ownerBirthDate"
                            v-model="instanceForm.ownerBirthDate"
                            type="date"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        />
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                    <button
                        type="button"
                        @click="router.push('/mc-admin/mythicaldash')"
                        class="px-4 py-2 border border-gray-600 rounded-lg text-gray-300 hover:bg-gray-700 transition-colors"
                    >
                        Cancel
                    </button>
                    <button
                        type="submit"
                        :disabled="loading"
                        class="px-4 py-2 bg-gradient-to-r from-pink-500 to-violet-500 rounded-lg text-white hover:opacity-90 transition-colors flex items-center"
                    >
                        <LoaderIcon v-if="loading" class="animate-spin w-4 h-4 mr-2" />
                        <SaveIcon v-else class="w-4 h-4 mr-2" />
                        Create Instance
                    </button>
                </div>
            </form>
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import { ArrowLeftIcon, SaveIcon, LoaderIcon } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { useSound } from '@vueuse/sound';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import MythicalDash from '@/mymythicalid/admin/MythicalDash';
import Projects from '@/mymythicalid/admin/Projects';
import User from '@/mymythicalid/admin/Users';

// Add interfaces for project and user types
interface Project {
    id: number;
    name: string;
    type: string;
    price: number;
    description: string;
    features: string[];
    created_at: string;
    updated_at: string;
}

interface UserData {
    uuid: string;
    first_name: string;
    last_name: string;
    email: string;
    role_id: number;
    created_at: string;
    updated_at: string;
}

const router = useRouter();
const loading = ref(false);
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);

// Form state
const instanceForm = ref({
    user: '',
    project: 0,
    companyName: '',
    companyWebsite: '',
    businessDescription: '',
    hostingType: 'free' as 'free' | 'paid' | 'both',
    currentUsers: 0,
    expectedUsers: 0,
    instanceUrl: '',
    serverType: 'vps' as 'vps' | 'dedicated' | 'docker' | 'other',
    serverCount: 1,
    primaryEmail: '',
    abuseEmail: '',
    supportEmail: '',
    ownerFirstName: '',
    ownerLastName: '',
    ownerBirthDate: '',
});

// Add project and user data with proper types
const projects = ref<Project[]>([]);
const users = ref<UserData[]>([]);

// Fetch projects and users
const fetchProjects = async () => {
    try {
        const response = await Projects.getProjects();
        if (response.success) {
            projects.value = response.projects;
        }
    } catch (error) {
        console.error('Error fetching projects:', error);
    }
};

const fetchUsers = async () => {
    try {
        const response = await User.getUsers();
        if (response.success) {
            users.value = response.users;
        }
    } catch (error) {
        console.error('Error fetching users:', error);
    }
};

onMounted(() => {
    fetchProjects();
    fetchUsers();
});

const saveInstance = async () => {
    loading.value = true;

    try {
        const response = await MythicalDash.createInstance(
            instanceForm.value.user,
            instanceForm.value.project,
            instanceForm.value.companyName,
            instanceForm.value.companyWebsite,
            instanceForm.value.businessDescription,
            instanceForm.value.hostingType,
            instanceForm.value.currentUsers,
            instanceForm.value.expectedUsers,
            instanceForm.value.instanceUrl,
            instanceForm.value.serverType,
            instanceForm.value.serverCount,
            instanceForm.value.primaryEmail,
            instanceForm.value.abuseEmail,
            instanceForm.value.supportEmail,
            instanceForm.value.ownerFirstName,
            instanceForm.value.ownerLastName,
            instanceForm.value.ownerBirthDate,
        );

        if (response.success) {
            playSuccess();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'MythicalDash instance created successfully',
                showConfirmButton: true,
            });

            // Navigate back to instances list after a short delay
            setTimeout(() => {
                router.push('/mc-admin/mythicaldash');
            }, 1500);
        } else {
            const errorMessages = {
                MISSING_REQUIRED_FIELDS: 'Please fill in all required fields',
                FAILED_TO_CREATE_INSTANCE: 'Server failed to create the instance',
                INVALID_SESSION: 'Your session has expired. Please log in again.',
            };

            const error_code = response.error_code as keyof typeof errorMessages;
            const errorMessage = errorMessages[error_code] || 'Failed to create instance';

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
        console.error('Error creating instance:', error);
        playError();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred while creating the instance',
            footer: 'Please try again or contact support if the issue persists.',
            showConfirmButton: true,
        });
    } finally {
        loading.value = false;
    }
};
</script>
