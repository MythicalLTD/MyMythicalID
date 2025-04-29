<template>
    <LayoutDashboard>
        <div class="space-y-6">
            <!-- Header with Balance -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-100">{{ t('store.pages.index.title') }}</h1>
                    <p class="text-gray-400 mt-1">{{ t('store.pages.index.subTitle') }}</p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="bg-[#1a1a2e]/30 rounded-lg px-4 py-2 flex items-center gap-2">
                        <Coins class="h-5 w-5 text-indigo-400" />
                        <span class="text-gray-200"
                            >{{ userBalance }} {{ t('store.pages.index.cardYourBalance') }}</span
                        >
                    </div>
                    <button
                        @click="goToAddCredits"
                        class="bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-400 px-4 py-2 rounded-lg flex items-center gap-2 transition-colors"
                    >
                        <Plus class="h-5 w-5" />
                        {{ t('store.pages.index.addCredits') }}
                    </button>
                </div>
            </div>

            <!-- Error State -->
            <div v-if="error" class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 flex items-start gap-3">
                <AlertCircle class="h-5 w-5 text-red-400 mt-0.5 flex-shrink-0" />
                <div>
                    <h3 class="text-sm font-medium text-red-400">
                        {{ t('store.pages.alerts.error.title') }}
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">{{ error }}</p>
                </div>
            </div>

            <!-- Loading State -->
            <div v-else-if="loading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="i in 6" :key="i" class="bg-[#1a1a2e]/30 rounded-lg p-6 animate-pulse">
                    <div class="flex flex-col gap-4">
                        <div class="w-12 h-12 rounded-lg bg-[#1a1a2e]/50"></div>
                        <div class="space-y-2">
                            <div class="h-5 w-24 bg-[#1a1a2e]/50 rounded"></div>
                            <div class="h-4 w-40 bg-[#1a1a2e]/50 rounded"></div>
                        </div>
                        <div class="h-8 w-full bg-[#1a1a2e]/50 rounded-lg"></div>
                    </div>
                </div>
            </div>

            <!-- Projects Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="project in projects"
                    :key="project.id"
                    class="bg-[#1a1a2e]/30 rounded-lg p-6 border border-[#2a2a3f]/30 hover:border-indigo-500/30 transition-all duration-200"
                >
                    <div class="flex flex-col h-full">
                        <!-- Project Icon/Image -->
                        <div class="p-3 rounded-lg bg-indigo-500/20 w-fit">
                            <Package class="h-6 w-6 text-indigo-400" />
                        </div>

                        <!-- Project Info -->
                        <div class="mt-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-200">{{ project.name }}</h3>
                            <p class="text-sm text-gray-400 mt-1">{{ project.description }}</p>

                            <!-- Features List -->
                            <ul class="mt-4 space-y-2">
                                <li
                                    v-for="(feature, index) in project.features"
                                    :key="index"
                                    class="flex items-center gap-2 text-sm text-gray-400"
                                >
                                    <div class="w-1.5 h-1.5 rounded-full bg-indigo-400"></div>
                                    {{ feature }}
                                </li>
                            </ul>
                        </div>

                        <!-- Purchase Button -->
                        <div class="mt-6 space-y-2">
                            <button
                                @click="handlePurchase(project)"
                                :class="[
                                    'w-full px-4 py-2 rounded-lg flex items-center justify-center gap-2 transition-colors',
                                    userBalance >= project.price
                                        ? 'bg-indigo-500/20 hover:bg-indigo-500/30 text-indigo-400'
                                        : 'bg-gray-500/20 text-gray-400 cursor-not-allowed opacity-50 blur-[0.5px]',
                                ]"
                                :disabled="userBalance < project.price"
                            >
                                <Coins class="h-5 w-5" />
                                {{
                                    project.price === 0
                                        ? t('store.pages.index.free')
                                        : `${project.price} ${t('store.pages.index.cardYourBalance')}`
                                }}
                            </button>
                            <a
                                v-if="project.link"
                                :href="project.link"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="w-full px-4 py-2 rounded-lg flex items-center justify-center gap-2 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 transition-colors"
                            >
                                <ExternalLink class="h-5 w-5" />
                                {{ t('store.pages.index.viewProject') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!loading && projects.length === 0" class="text-center py-12">
                <Package class="h-12 w-12 text-gray-600 mx-auto mb-3" />
                <h3 class="text-lg font-medium text-gray-300">{{ t('store.pages.index.empty') }}</h3>
                <p class="text-sm text-gray-400 mt-1">{{ t('store.pages.index.emptyDescription') }}</p>
            </div>
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import { useRouter } from 'vue-router';
import LayoutDashboard from '@/components/client/LayoutDashboard.vue';
import { Package, AlertCircle, Coins, Plus, ExternalLink } from 'lucide-vue-next';
import { MythicalDOM } from '@/mymythicalid/MythicalDOM';

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

const { t } = useI18n();
const router = useRouter();
const projects = ref<Project[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);
const userBalance = ref(0);

MythicalDOM.setPageTitle(t('store.pages.index.title'));

const fetchProjects = async () => {
    try {
        // TODO: Replace with actual API call
        const response = await fetch('/api/user/projects');
        const data = await response.json();
        if (data.success) {
            projects.value = data.projects;
            userBalance.value = data.user_balance;
        } else {
            throw new Error(data.error || 'Failed to fetch projects');
        }
    } catch (err) {
        error.value = err instanceof Error ? err.message : 'An unknown error occurred';
    } finally {
        loading.value = false;
    }
};

const handlePurchase = async (project: Project) => {
    if (userBalance.value < project.price) {
        router.push('/store/add-credits');
        return;
    }

    try {
        if (project.id === 1) {
            router.push('/store/mythicaldash');
            return;
        }

        if (project.id === 5) {
            router.push('/store/buy/premium/mythicaldash');
            return;
        }

        // TODO: Replace with actual purchase API call
        const response = await fetch(`/api/projects/${project.id}/purchase`, {
            method: 'POST',
        });
        const data = await response.json();
        if (data.success) {
            // Handle successful purchase
            userBalance.value -= project.price;
        } else {
            throw new Error(data.error || 'Failed to purchase project');
        }
    } catch (err) {
        error.value = err instanceof Error ? err.message : 'An unknown error occurred';
    }
};
const goToAddCredits = () => {
    router.push('/store/add-credits');
};

onMounted(fetchProjects);
</script>

<style scoped>
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
