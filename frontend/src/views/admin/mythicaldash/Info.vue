<template>
    <LayoutDashboard>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-400">MythicalDash Instance Details</h1>
            <button
                @click="router.push('/mc-admin/mythicaldash')"
                class="bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:bg-gray-600 flex items-center"
            >
                <ArrowLeftIcon class="w-4 h-4 mr-2" />
                Back to Instances
            </button>
        </div>

        <div v-if="loading" class="flex justify-center items-center py-10">
            <LoaderCircle class="h-8 w-8 animate-spin text-pink-400" />
        </div>

        <div v-else-if="instance" class="space-y-6">
            <!-- Company Information -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-pink-400 mb-4">Company Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Company Name</p>
                        <p class="text-white">{{ instance.companyName }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Company Website</p>
                        <a :href="instance.companyWebsite" target="_blank" class="text-pink-400 hover:text-pink-300">
                            {{ instance.companyWebsite }}
                        </a>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-400">Business Description</p>
                        <p class="text-white">{{ instance.businessDescription }}</p>
                    </div>
                </div>
            </div>

            <!-- Hosting Information -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-pink-400 mb-4">Hosting Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Hosting Type</p>
                        <span
                            :class="{
                                'px-2 py-1 rounded-full text-xs font-medium': true,
                                'bg-green-500/20 text-green-400': instance.hostingType === 'free',
                                'bg-blue-500/20 text-blue-400': instance.hostingType === 'paid',
                                'bg-purple-500/20 text-purple-400': instance.hostingType === 'both',
                            }"
                        >
                            {{ instance.hostingType.charAt(0).toUpperCase() + instance.hostingType.slice(1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Server Type</p>
                        <span
                            :class="{
                                'px-2 py-1 rounded-full text-xs font-medium': true,
                                'bg-blue-500/20 text-blue-400': instance.serverType === 'vps',
                                'bg-green-500/20 text-green-400': instance.serverType === 'dedicated',
                                'bg-purple-500/20 text-purple-400': instance.serverType === 'docker',
                                'bg-gray-500/20 text-gray-400': instance.serverType === 'other',
                            }"
                        >
                            {{ instance.serverType.charAt(0).toUpperCase() + instance.serverType.slice(1) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Current Users</p>
                        <p class="text-white">{{ instance.currentUsers }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Expected Users</p>
                        <p class="text-white">{{ instance.expectedUsers }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Server Count</p>
                        <p class="text-white">{{ instance.serverCount }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Instance URL</p>
                        <a :href="instance.instanceUrl" target="_blank" class="text-pink-400 hover:text-pink-300">
                            {{ instance.instanceUrl }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-pink-400 mb-4">Contact Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Primary Email</p>
                        <a :href="'mailto:' + instance.primaryEmail" class="text-pink-400 hover:text-pink-300">
                            {{ instance.primaryEmail }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Abuse Email</p>
                        <a :href="'mailto:' + instance.abuseEmail" class="text-pink-400 hover:text-pink-300">
                            {{ instance.abuseEmail }}
                        </a>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Support Email</p>
                        <a :href="'mailto:' + instance.supportEmail" class="text-pink-400 hover:text-pink-300">
                            {{ instance.supportEmail }}
                        </a>
                    </div>
                </div>
            </div>

            <!-- Owner Information -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-pink-400 mb-4">Owner Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Owner Name</p>
                        <p class="text-white">{{ instance.ownerFirstName }} {{ instance.ownerLastName }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Birth Date</p>
                        <p class="text-white">{{ new Date(instance.ownerBirthDate).toLocaleDateString() }}</p>
                    </div>
                </div>
            </div>

            <!-- License Information -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-pink-400 mb-4">License Information</h2>
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm text-gray-400">License Key ID</p>
                        <p class="text-white">{{ instance.license_key }}</p>
                    </div>
                    <button
                        @click="viewLicense"
                        class="bg-gradient-to-r from-pink-500 to-violet-500 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:opacity-80 flex items-center"
                    >
                        <ExternalLinkIcon class="w-4 h-4 mr-2" />
                        View License
                    </button>
                </div>
            </div>

            <!-- Metadata -->
            <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
                <h2 class="text-xl font-semibold text-pink-400 mb-4">Metadata</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">Instance ID</p>
                        <p class="text-white">{{ instance.id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">UUID</p>
                        <p class="text-white">{{ instance.uuid }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Created At</p>
                        <p class="text-white">{{ new Date(instance.created_at).toLocaleString() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Last Updated</p>
                        <p class="text-white">{{ new Date(instance.updated_at).toLocaleString() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        <div class="flex items-center space-x-2">
                            <span
                                :class="{
                                    'px-2 py-1 rounded-full text-xs font-medium': true,
                                    'bg-red-500/20 text-red-400': instance.deleted === 'true',
                                    'bg-green-500/20 text-green-400': instance.deleted === 'false',
                                }"
                            >
                                {{ instance.deleted === 'true' ? 'Deleted' : 'Active' }}
                            </span>
                            <span
                                :class="{
                                    'px-2 py-1 rounded-full text-xs font-medium': true,
                                    'bg-yellow-500/20 text-yellow-400': instance.locked === 'true',
                                    'bg-green-500/20 text-green-400': instance.locked === 'false',
                                }"
                            >
                                {{ instance.locked === 'true' ? 'Locked' : 'Unlocked' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="text-center py-10">
            <p class="text-gray-400">Instance not found</p>
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import { ArrowLeftIcon, ExternalLinkIcon, LoaderCircle } from 'lucide-vue-next';
import MythicalDash from '@/mymythicalid/admin/MythicalDash';

// Instance interface with the fields from the API
interface MythicalDashInstance {
    id: number;
    uuid: string;
    user: string;
    project: number;
    license_key: number;
    companyName: string;
    companyWebsite: string;
    businessDescription: string;
    hostingType: 'free' | 'paid' | 'both';
    currentUsers: number;
    expectedUsers: number;
    instanceUrl: string;
    serverType: 'vps' | 'dedicated' | 'docker' | 'other';
    serverCount: number;
    primaryEmail: string;
    abuseEmail: string;
    supportEmail: string;
    ownerFirstName: string;
    ownerLastName: string;
    ownerBirthDate: string;
    deleted: 'false' | 'true';
    locked: 'false' | 'true';
    updated_at: string;
    created_at: string;
}

const router = useRouter();
const route = useRoute();
const instance = ref<MythicalDashInstance | null>(null);
const loading = ref(true);

const fetchInstance = async () => {
    loading.value = true;
    try {
        const response = await MythicalDash.getInstance(route.params.id as string);

        if (response.success) {
            instance.value = response.instance;
        } else {
            console.error('Failed to load instance:', response.message);
        }
    } catch (error) {
        console.error('Error fetching instance:', error);
    } finally {
        loading.value = false;
    }
};

const viewLicense = () => {
    if (instance.value) {
        router.push(`/mc-admin/license-keys/${instance.value.license_key}/edit`);
    }
};

onMounted(() => {
    fetchInstance();
});
</script>
