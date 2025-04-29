<template>
    <LayoutDashboard>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-400">Projects</h1>
            <button
                @click="goToCreation()"
                class="bg-gradient-to-r from-pink-500 to-violet-500 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:opacity-80 flex items-center"
            >
                <PlusIcon class="w-4 h-4 mr-2" />
                Add Project
            </button>
        </div>
        <!-- Projects Table using TableTanstack -->
        <div v-if="loading" class="flex justify-center items-center py-10">
            <LoaderCircle class="h-8 w-8 animate-spin text-pink-400" />
        </div>
        <TableTanstack v-else :data="projects" :columns="columns" tableName="Projects" />
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref, onMounted, h } from 'vue';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import TableTanstack from '@/components/client/ui/Table/TableTanstack.vue';
import { PlusIcon, EditIcon, TrashIcon, LoaderCircle } from 'lucide-vue-next';
import { useRouter } from 'vue-router';
import Projects from '@/mymythicalid/admin/Projects';

// Project interface with the fields from the API
interface Project {
    id: number;
    name: string;
    description: string;
    type: string;
    uuid: string;
    created_at: string;
}

const router = useRouter();
const projects = ref<Project[]>([]);
const loading = ref(true);

// Define columns for TableTanstack
const columns = [
    {
        accessorKey: 'id',
        header: 'ID',
        cell: (info: { getValue: () => number }) => info.getValue(),
    },
    {
        accessorKey: 'name',
        header: 'Name',
        cell: (info: { getValue: () => string }) => info.getValue(),
    },
    {
        accessorKey: 'description',
        header: 'Description',
        cell: (info: { getValue: () => string }) => info.getValue(),
    },
    {
        accessorKey: 'type',
        header: 'Type',
        cell: (info: { getValue: () => string }) => {
            const type = info.getValue();
            return h(
                'span',
                {
                    class: {
                        'px-2 py-1 rounded-full text-xs font-medium': true,
                        'bg-blue-500/20 text-blue-400': type === 'web',
                        'bg-green-500/20 text-green-400': type === 'app',
                        'bg-purple-500/20 text-purple-400': type === 'plugin',
                        'bg-gray-500/20 text-gray-400': type === 'other',
                    },
                },
                type.charAt(0).toUpperCase() + type.slice(1),
            );
        },
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
        cell: (info: { row: { original: Project } }) => {
            const project = info.row.original;
            return h('div', { class: 'flex space-x-2' }, [
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-pink-400 transition-colors',
                        title: 'Edit',
                        onClick: () => editProject(project),
                    },
                    [h(EditIcon, { class: 'w-4 h-4' })],
                ),
                h(
                    'button',
                    {
                        class: 'p-1 text-gray-400 hover:text-red-400 transition-colors',
                        title: 'Delete',
                        onClick: () => confirmDelete(project),
                    },
                    [h(TrashIcon, { class: 'w-4 h-4' })],
                ),
            ]);
        },
    },
];

// Fetch projects from API
const fetchProjects = async () => {
    loading.value = true;
    try {
        const response = await Projects.getProjects();

        if (response.success) {
            projects.value = response.projects;
        } else {
            console.error('Failed to load projects:', response.message);
        }
    } catch (error) {
        console.error('Error fetching projects:', error);
    } finally {
        loading.value = false;
    }
};

const goToCreation = () => {
    router.push('/mc-admin/projects/create');
};

const editProject = (project: Project) => {
    router.push(`/mc-admin/projects/${project.id}/edit`);
};

const confirmDelete = (project: Project) => {
    router.push(`/mc-admin/projects/${project.id}/delete`);
};

onMounted(() => {
    fetchProjects();
});
</script>
