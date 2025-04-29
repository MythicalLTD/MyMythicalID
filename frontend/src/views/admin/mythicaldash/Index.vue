<template>
    <LayoutDashboard>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-400">MythicalDash Instances</h1>
            <button
                @click="goToCreation()"
                class="bg-gradient-to-r from-pink-500 to-violet-500 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:opacity-80 flex items-center"
            >
                <PlusIcon class="w-4 h-4 mr-2" />
                Add Instance
            </button>
        </div>
        <!-- Instances Table using TableTanstack -->
        <div v-if="loading" class="flex justify-center items-center py-10">
            <LoaderCircle class="h-8 w-8 animate-spin text-pink-400" />
        </div>
        <TableTanstack v-else :data="instances" :columns="columns" tableName="MythicalDash Instances" />
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted, h } from 'vue';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import TableTanstack from '@/components/client/ui/Table/TableTanstack.vue';
import { PlusIcon, LoaderCircle, KeyIcon, InfoIcon, Trash2Icon } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import MythicalDash from '@/mymythicalid/admin/MythicalDash';
import { useToast } from '../../../composables/useToast';

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
const instances = ref<MythicalDashInstance[]>([]);
const loading = ref(true);
const { showToast } = useToast();

// Define columns for TableTanstack
const columns = [
    {
        accessorKey: 'id',
        header: 'ID',
        cell: (info: { getValue: () => number }) => info.getValue(),
    },
    {
        accessorKey: 'companyName',
        header: 'Company',
        cell: (info: { getValue: () => string }) => info.getValue(),
    },
    {
        accessorKey: 'instanceUrl',
        header: 'Instance URL',
        cell: (info: { getValue: () => string }) => {
            const url = info.getValue();
            return h(
                'a',
                {
                    href: url,
                    target: '_blank',
                    class: 'text-pink-400 hover:text-pink-300 transition-colors',
                },
                url,
            );
        },
    },
    {
        accessorKey: 'hostingType',
        header: 'Hosting',
        cell: (info: { getValue: () => string }) => {
            const type = info.getValue();
            return h(
                'span',
                {
                    class: {
                        'px-2 py-1 rounded-full text-xs font-medium': true,
                        'bg-green-500/20 text-green-400': type === 'free',
                        'bg-blue-500/20 text-blue-400': type === 'paid',
                        'bg-purple-500/20 text-purple-400': type === 'both',
                    },
                },
                type.charAt(0).toUpperCase() + type.slice(1),
            );
        },
    },
    {
        accessorKey: 'serverType',
        header: 'Server Type',
        cell: (info: { getValue: () => string }) => {
            const type = info.getValue();
            return h(
                'span',
                {
                    class: {
                        'px-2 py-1 rounded-full text-xs font-medium': true,
                        'bg-blue-500/20 text-blue-400': type === 'vps',
                        'bg-green-500/20 text-green-400': type === 'dedicated',
                        'bg-purple-500/20 text-purple-400': type === 'docker',
                        'bg-gray-500/20 text-gray-400': type === 'other',
                    },
                },
                type.charAt(0).toUpperCase() + type.slice(1),
            );
        },
    },
    {
        accessorKey: 'currentUsers',
        header: 'Current Users',
        cell: (info: { getValue: () => number }) => info.getValue(),
    },
    {
        accessorKey: 'expectedUsers',
        header: 'Expected Users',
        cell: (info: { getValue: () => number }) => info.getValue(),
    },
    {
        accessorKey: 'created_at',
        header: 'Created At',
        cell: (info: { getValue: () => string }) =>
            info.getValue() ? new Date(info.getValue()).toLocaleString() : 'N/A',
    },
    {
        id: 'actions',
        header: 'Actions',
        cell: (info: { row: { original: MythicalDashInstance } }) => {
            const instance = info.row.original;
            return h('div', { class: 'flex space-x-2' }, [
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-blue-400 transition-colors',
                        title: 'View License Key',
                        onClick: () => viewLicenseKey(instance),
                    },
                    [h(KeyIcon, { class: 'w-4 h-4' })],
                ),
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-green-400 transition-colors',
                        title: 'View Info',
                        onClick: () => viewInfo(instance),
                    },
                    [h(InfoIcon, { class: 'w-4 h-4' })],
                ),
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-red-400 transition-colors',
                        title: 'Delete Instance',
                        onClick: () => confirmDelete(instance),
                    },
                    [h(Trash2Icon, { class: 'w-4 h-4' })],
                ),
            ]);
        },
    },
];

// Fetch instances from API
const fetchInstances = async () => {
    loading.value = true;
    try {
        const response = await MythicalDash.getInstances();

        if (response.success) {
            instances.value = response.instances;
        } else {
            console.error('Failed to load instances:', response.message);
        }
    } catch (error) {
        console.error('Error fetching instances:', error);
    } finally {
        loading.value = false;
    }
};

const goToCreation = () => {
    router.push('/mc-admin/mythicaldash/create');
};

const viewLicenseKey = (instance: MythicalDashInstance) => {
    router.push(`/mc-admin/license-keys/${instance.license_key}/edit`);
};

const viewInfo = (instance: MythicalDashInstance) => {
    router.push(`/mc-admin/mythicaldash/${instance.id}/info`);
};

const confirmDelete = async (instance: MythicalDashInstance) => {
    if (
        confirm(
            `Are you sure you want to delete the instance "${instance.companyName}"? This action cannot be undone. Their license key will be deleted as well.`,
        )
    ) {
        try {
            const response = await MythicalDash.deleteInstance(instance.id);
            if (response.success) {
                showToast('Instance deleted successfully', 'success');
                await fetchInstances(); // Refresh the list
            } else {
                showToast(response.message || 'Failed to delete instance', 'error');
            }
        } catch (error) {
            console.error('Error deleting instance:', error);
            showToast('An error occurred while deleting the instance', 'error');
        }
    }
};

onMounted(() => {
    fetchInstances();
});
</script>
