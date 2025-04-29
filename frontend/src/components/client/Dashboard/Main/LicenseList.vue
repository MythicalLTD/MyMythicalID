<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useI18n } from 'vue-i18n';
import CardComponent from '@/components/client/ui/Card/CardComponent.vue';
import Licenses from '@/mymythicalid/Liceses';
import { format } from 'date-fns';
import { Key, AlertCircle } from 'lucide-vue-next';

interface Project {
    id: number;
    name: string;
    description: string;
    uuid: string;
    type: string;
    deleted: string;
    locked: string;
    date: string;
}

interface License {
    id: number;
    project: Project;
    context: string;
    key: string;
    status: string;
    expires_at: string;
}

const { t } = useI18n();
const licenses = ref<License[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

const fetchLicenses = async () => {
    try {
        const response = await Licenses.getLicenses();
        if (response.success) {
            licenses.value = response.licenses;
        } else {
            throw new Error(response.error || 'Failed to fetch licenses');
        }
    } catch (err) {
        error.value = err instanceof Error ? err.message : 'An unknown error occurred';
    } finally {
        loading.value = false;
    }
};

onMounted(fetchLicenses);
</script>

<template>
    <CardComponent :cardTitle="t('Components.Licenses.title')" :cardDescription="t('Components.Licenses.description')">
        <!-- Error State -->
        <div v-if="error" class="bg-red-500/10 border border-red-500/20 rounded-lg p-4 flex items-start gap-3">
            <AlertCircle class="h-5 w-5 text-red-400 mt-0.5 flex-shrink-0" />
            <div>
                <h3 class="text-sm font-medium text-red-400">
                    {{ t('Components.Licenses.errors.title') }}
                </h3>
                <p class="text-xs text-gray-400 mt-1">{{ error }}</p>
            </div>
        </div>

        <!-- Loading State -->
        <div v-else-if="loading" class="space-y-4">
            <div v-for="i in 3" :key="i" class="bg-[#1a1a2e]/30 rounded-lg p-6 animate-pulse">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-lg bg-[#1a1a2e]/50"></div>
                    <div class="flex-1">
                        <div class="h-5 w-24 bg-[#1a1a2e]/50 rounded mb-2"></div>
                        <div class="h-4 w-40 bg-[#1a1a2e]/50 rounded"></div>
                    </div>
                    <div class="w-24 h-8 bg-[#1a1a2e]/50 rounded-lg"></div>
                </div>
            </div>
        </div>

        <!-- License List -->
        <div v-else class="space-y-4">
            <div
                v-for="license in licenses"
                :key="license.id"
                class="bg-[#1a1a2e]/30 rounded-lg p-6 border border-[#2a2a3f]/30 hover:border-indigo-500/30 transition-all duration-200"
            >
                <div class="flex items-start gap-4">
                    <!-- License Icon -->
                    <div class="p-3 rounded-lg bg-indigo-500/20">
                        <Key class="h-6 w-6 text-indigo-400" />
                    </div>

                    <!-- License Info -->
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base font-medium text-gray-200">
                            {{ license.project.name }}
                        </h3>
                        <p class="text-sm text-gray-400 mt-1">{{ license.project.description }}</p>

                        <!-- License Key -->
                        <div class="mt-3">
                            <p class="text-xs text-gray-500 mb-1">{{ t('Components.Licenses.key') }}</p>
                            <code class="text-sm font-mono bg-[#0a0a0f]/70 px-3 py-1.5 rounded text-indigo-400">
                                {{ license.key }}
                            </code>
                        </div>

                        <!-- Status and Expiry -->
                        <div class="flex items-center gap-4 mt-4">
                            <span
                                :class="[
                                    'px-2 py-1 rounded-sm text-xs font-medium',
                                    license.status === 'active'
                                        ? 'bg-green-500/20 text-green-400'
                                        : 'bg-red-500/20 text-red-400',
                                ]"
                            >
                                {{ license.status }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ t('Components.Licenses.expires') }}:
                                {{ format(new Date(license.expires_at), 'PPP') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="licenses.length === 0" class="text-center py-8">
                <Key class="h-12 w-12 text-gray-600 mx-auto mb-3" />
                <h3 class="text-lg font-medium text-gray-300">{{ t('Components.Licenses.empty.title') }}</h3>
                <p class="text-sm text-gray-400 mt-1">{{ t('Components.Licenses.empty.description') }}</p>
            </div>
        </div>
    </CardComponent>
</template>

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
