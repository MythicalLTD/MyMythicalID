<!-- eslint-disable vue/valid-attribute-name -->
<!-- eslint-disable vue/no-parsing-error -->
<!-- eslint-disable prettier/prettier -->
<template>
    <LayoutDashboard>
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-pink-400">Create Project</h1>
            <button
                @click="router.push('/mc-admin/projects')"
                class="bg-gray-700 text-white px-4 py-2 rounded-lg transition-all duration-200 hover:bg-gray-600 flex items-center"
            >
                <ArrowLeftIcon class="w-4 h-4 mr-2" />
                Back to Projects
            </button>
        </div>

        <div class="bg-gray-800/50 backdrop-blur-md rounded-lg p-6">
            <form @submit.prevent="saveProject" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                        <input
                            id="name"
                            v-model="projectForm.name"
                            type="text"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="e.g. My Awesome Project"
                        />
                        <p class="text-xs text-gray-400 mt-1">The project name shown to users</p>
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-400 mb-1">Type</label>
                        <select
                            id="type"
                            v-model="projectForm.type"
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        >
                            <option value="web">Web</option>
                            <option value="app">App</option>
                            <option value="plugin">Plugin</option>
                            <option value="other">Other</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">The type of project</p>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-400 mb-1">Price</label>
                        <input
                            id="price"
                            v-model="projectForm.price"
                            type="number"
                            required
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="e.g. 100"
                        />
                        <p class="text-xs text-gray-400 mt-1">The price of the project</p>
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-400 mb-1"
                            >Description</label
                        >
                        <textarea
                            id="description"
                            v-model="projectForm.description"
                            required
                            rows="3"
                            class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                            placeholder="e.g. A detailed description of the project"
                        ></textarea>
                        <p class="text-xs text-gray-400 mt-1">A description of the project's purpose and features</p>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <label for="link" class="block text-sm font-medium text-gray-400 mb-1">Project Link</label>
                    <input
                        id="link"
                        v-model="projectForm.link"
                        type="url"
                        required
                        class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                        placeholder="e.g. https://github.com/username/project"
                    />
                    <p class="text-xs text-gray-400 mt-1">
                        The URL where users can find more information about the project
                    </p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Features</label>
                    <div class="space-y-2">
                        <div v-for="(feature, index) in projectForm.features" :key="index" class="flex gap-2">
                            <input
                                v-model="projectForm.features[index]"
                                type="text"
                                class="bg-gray-800/30 border border-gray-700 rounded-lg px-4 py-2 w-full focus:outline-none focus:ring-2 focus:ring-pink-500"
                                placeholder="e.g. Feature description"
                            />
                            <button
                                type="button"
                                @click="projectForm.features.splice(index, 1)"
                                class="px-3 py-2 text-red-400 hover:text-red-300 transition-colors"
                            >
                                Remove
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="projectForm.features.push('')"
                            class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                        >
                            + Add Feature
                        </button>
                    </div>
                    <p class="text-xs text-gray-400 mt-1">List of features included in this project</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t border-gray-700">
                    <button
                        type="button"
                        @click="router.push('/mc-admin/projects')"
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
                        Create Project
                    </button>
                </div>
            </form>
        </div>
    </LayoutDashboard>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import LayoutDashboard from '@/components/admin/LayoutDashboard.vue';
import { ArrowLeftIcon, SaveIcon, LoaderIcon } from 'lucide-vue-next';
import Swal from 'sweetalert2';
import { useSound } from '@vueuse/sound';
import failedAlertSfx from '@/assets/sounds/error.mp3';
import successAlertSfx from '@/assets/sounds/success.mp3';
import Projects from '@/mymythicalid/admin/Projects';

const router = useRouter();
const loading = ref(false);
const { play: playError } = useSound(failedAlertSfx);
const { play: playSuccess } = useSound(successAlertSfx);

// Form state
const projectForm = ref({
    name: '',
    description: '',
    type: 'other',
    price: 0,
    features: [] as string[],
    link: '',
});

const saveProject = async () => {
    loading.value = true;

    try {
        const response = await Projects.createProject(
            projectForm.value.name,
            projectForm.value.description,
            projectForm.value.type,
            projectForm.value.price,
            projectForm.value.features,
            projectForm.value.link,
        );

        if (response.success) {
            playSuccess();
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Project created successfully',
                showConfirmButton: true,
            });

            // Navigate back to projects list after a short delay
            setTimeout(() => {
                router.push('/mc-admin/projects');
            }, 1500);
        } else {
            const errorMessages = {
                MISSING_REQUIRED_FIELDS: 'Please fill in all required fields',
                INVALID_PROJECT_TYPE: 'Invalid project type selected',
                FAILED_TO_CREATE_PROJECT: 'Server failed to create the project',
                INVALID_SESSION: 'Your session has expired. Please log in again.',
            };

            const error_code = response.error_code as keyof typeof errorMessages;
            const errorMessage = errorMessages[error_code] || 'Failed to create project';

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
        console.error('Error creating project:', error);
        playError();
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred while creating the project',
            footer: 'Please try again or contact support if the issue persists.',
            showConfirmButton: true,
        });
    } finally {
        loading.value = false;
    }
};
</script>
