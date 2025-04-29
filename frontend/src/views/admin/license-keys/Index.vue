<template>
    <LayoutDashboard>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-400">License Keys</h1>
            <button
                @click="goToCreation()"
                class="bg-gradient-to-r from-pink-500 to-violet-500 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:opacity-80 flex items-center"
            >
                <PlusIcon class="w-4 h-4 mr-2" />
                Add License Key
            </button>
        </div>
        <!-- License Keys Table using TableTanstack -->
        <div v-if="loading" class="flex justify-center items-center py-10">
            <LoaderCircle class="h-8 w-8 animate-spin text-pink-400" />
        </div>
        <TableTanstack v-else :data="licenseKeys" :columns="columns" tableName="License Keys" />
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted, h } from 'vue';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import TableTanstack from '@/components/client/ui/Table/TableTanstack.vue';
import { PlusIcon, EditIcon, TrashIcon, LoaderCircle } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import LicenseKeys from '@/mymythicalid/admin/LicenseKeys';

// License Key interface with the fields from the API
interface LicenseKey {
    id: number;
    project: number;
    uuid: string;
    license_key_uuid: string;
    context: string;
    status: 'active' | 'inactive' | 'expired';
    expires_at: string;
    date: string;
}

const router = useRouter();
const licenseKeys = ref<LicenseKey[]>([]);
const loading = ref(true);

// Define columns for TableTanstack
const columns = [
    {
        accessorKey: 'id',
        header: 'ID',
        cell: (info: { getValue: () => number }) => info.getValue(),
    },
    {
        accessorKey: 'project',
        header: 'Project ID',
        cell: (info: { getValue: () => number }) => info.getValue(),
    },
    {
        accessorKey: 'uuid',
        header: 'User UUID',
        cell: (info: { getValue: () => string }) => info.getValue(),
    },
    {
        accessorKey: 'license_key_uuid',
        header: 'License Key',
        cell: (info: { getValue: () => string }) => info.getValue(),
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: (info: { getValue: () => string }) => {
            const status = info.getValue();
            return h(
                'span',
                {
                    class: {
                        'px-2 py-1 rounded-full text-xs font-medium': true,
                        'bg-green-500/20 text-green-400': status === 'active',
                        'bg-yellow-500/20 text-yellow-400': status === 'inactive',
                        'bg-red-500/20 text-red-400': status === 'expired',
                    },
                },
                status.charAt(0).toUpperCase() + status.slice(1),
            );
        },
    },
    {
        accessorKey: 'expires_at',
        header: 'Expires At',
        cell: (info: { getValue: () => string }) =>
            info.getValue() ? new Date(info.getValue()).toLocaleString() : 'N/A',
    },
    {
        id: 'actions',
        header: 'Actions',
        cell: (info: { row: { original: LicenseKey } }) => {
            const licenseKey = info.row.original;
            return h('div', { class: 'flex space-x-2' }, [
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-pink-400 transition-colors',
                        title: 'Edit',
                        onClick: () => editLicenseKey(licenseKey),
                    },
                    [h(EditIcon, { class: 'w-4 h-4' })],
                ),
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-red-400 transition-colors',
                        title: 'Delete',
                        onClick: () => confirmDelete(licenseKey),
                    },
                    [h(TrashIcon, { class: 'w-4 h-4' })],
                ),
            ]);
        },
    },
];

// Fetch license keys from API
const fetchLicenseKeys = async () => {
    loading.value = true;
    try {
        const response = await LicenseKeys.getLicenseKeys();

        if (response.success) {
            licenseKeys.value = response.licenses;
        } else {
            console.error('Failed to load license keys:', response.message);
        }
    } catch (error) {
        console.error('Error fetching license keys:', error);
    } finally {
        loading.value = false;
    }
};

const goToCreation = () => {
    router.push('/mc-admin/license-keys/create');
};

const editLicenseKey = (licenseKey: LicenseKey) => {
    router.push(`/mc-admin/license-keys/${licenseKey.id}/edit`);
};

const confirmDelete = (licenseKey: LicenseKey) => {
    router.push(`/mc-admin/license-keys/${licenseKey.id}/delete`);
};

onMounted(() => {
    fetchLicenseKeys();
});
</script>
