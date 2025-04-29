<template>
    <LayoutDashboard>
        <div class="p-6 space-y-6">
            <BeforeContent />

            <!-- Header Section -->
            <div class="text-center mb-8">
                <h1
                    class="text-4xl font-bold text-white mb-3 bg-gradient-to-r from-indigo-400 to-purple-500 bg-clip-text text-transparent"
                >
                    Upgrade to MythicalDash Premium
                </h1>
                <p class="text-gray-400 max-w-2xl mx-auto">
                    Select your instance to upgrade to premium and unlock all features.
                </p>
            </div>

            <!-- Loading State -->
            <div v-if="loading" class="flex justify-center items-center py-10">
                <LoaderCircle class="h-8 w-8 animate-spin text-pink-400" />
            </div>

            <!-- Instance Selection -->
            <div v-else-if="instances.length > 0" class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div
                        v-for="instance in instances"
                        :key="instance.id"
                        class="bg-[#1a1a2e]/50 border border-[#2a2a3f]/30 rounded-lg p-6 hover:border-pink-500/50 transition-all duration-200 cursor-pointer"
                        @click="selectInstance(instance)"
                    >
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-xl font-semibold text-white">{{ instance.companyName }}</h3>
                                <p class="text-gray-400 text-sm">{{ instance.instanceUrl }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">
                                    Free
                                </span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Current Users:</span>
                                <span class="text-white">{{ instance.currentUsers }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Expected Users:</span>
                                <span class="text-white">{{ instance.expectedUsers }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Server Type:</span>
                                <span class="text-white">{{ instance.serverType }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- No Instances -->
            <div v-else class="text-center py-10">
                <p class="text-gray-400">You don't have any MythicalDash instances to upgrade.</p>
                <router-link to="/store/mythicaldash" class="text-pink-400 hover:text-pink-300 mt-4 inline-block">
                    Create a new instance
                </router-link>
            </div>

            <AfterContent />
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { LoaderCircle } from 'lucide-vue-next';
import LayoutDashboard from '@/components/client/LayoutDashboard.vue';
import BeforeContent from '@/plugins/components/Dashboard/BeforeContent.vue';
import AfterContent from '@/plugins/components/Dashboard/AfterContent.vue';
import { MythicalDOM } from '@/mymythicalid/MythicalDOM';
import MythicalDash from '@/mymythicalid/admin/MythicalDash';
import Session from '@/mymythicalid/Session';
import Swal from 'sweetalert2';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import { useSound } from '@vueuse/sound';

interface Project {
    id: number;
    name: string;
    description: string;
    uuid: string;
    type: string;
    price: number;
    features: string[];
    image?: string;
    link?: string;
}

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

interface ApiResponse {
    success: boolean;
    message?: string;
    instances?: MythicalDashInstance[];
}

const router = useRouter();
const loading = ref(true);
const instances = ref<MythicalDashInstance[]>([]);
const project = ref<Project | null>(null);
const userBalance = ref(0);
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);

const fetchData = async () => {
    try {
        // Fetch project info
        const projectResponse = await fetch('/api/user/projects');
        const projectData = await projectResponse.json();
        if (projectData.success) {
            const premiumProject = projectData.projects.find((p: Project) => p.id === 5);
            if (premiumProject) {
                project.value = premiumProject;
                userBalance.value = projectData.user_balance;
            }
        }

        // Fetch instances
        const response = (await MythicalDash.getInstancesByUser(Session.getInfo('uuid') || '')) as ApiResponse;
        if (response.success && response.instances) {
            instances.value = response.instances;
        } else {
            playError();
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: response.message || 'Failed to fetch instances',
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        playError();
        await Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while fetching data',
            showConfirmButton: true,
        });
    } finally {
        loading.value = false;
    }
};

const selectInstance = async (instance: MythicalDashInstance) => {
    if (!project.value) return;

    if (userBalance.value < project.value.price) {
        playError();
        await Swal.fire({
            icon: 'error',
            title: 'Insufficient Balance',
            text: 'You need more credits to purchase this upgrade',
            showConfirmButton: true,
        });
        router.push('/store/add-credits');
        return;
    }

    const result = await Swal.fire({
        icon: 'question',
        title: 'Upgrade to Premium',
        text: `Are you sure you want to upgrade "${instance.companyName}" to "MythicalDash Premium" for ${project.value.price} credits?`,
        showCancelButton: true,
        confirmButtonText: 'Yes, upgrade',
        cancelButtonText: 'No, cancel',
        confirmButtonColor: '#EC4899',
    });

    if (result.isConfirmed) {
        try {
            const response = await fetch(`/api/user/mythicaldash/instances/${instance.id}/upgrade`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    projectId: project.value.id,
                }),
            });

            const data = await response.json();

            if (data.success) {
                playSuccess();
                await Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Your instance has been upgraded to premium',
                    showConfirmButton: true,
                });
                router.push('/dashboard');
            } else {
                playError();
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Failed to process payment',
                    showConfirmButton: true,
                });
            }
        } catch (error) {
            console.error('Error processing payment:', error);
            playError();
            await Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while processing your payment',
                showConfirmButton: true,
            });
        }
    }
};

onMounted(() => {
    fetchData();
    MythicalDOM.setPageTitle('Upgrade to MythicalDash Premium');
});
</script>

<style scoped>
/* Add any custom styles here */
</style>
