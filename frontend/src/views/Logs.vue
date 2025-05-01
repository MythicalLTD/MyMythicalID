<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <!-- Navbar -->
        <nav class="bg-gray-800/50 backdrop-blur-lg border-b border-gray-700/50 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <!-- Left side -->
                    <div class="flex items-center">
                        <router-link to="/" class="flex items-center space-x-2">
                            <img src="https://github.com/mythicalltd.png" alt="Logo" class="h-8 w-8 rounded-lg" />
                            <span
                                class="text-xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent"
                            >
                                MyMythicalID
                            </span>
                        </router-link>
                    </div>

                    <!-- Right side -->
                    <div class="flex items-center space-x-4">
                        <a
                            href="https://discord.mythical.systems"
                            target="_blank"
                            class="text-gray-300 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-700/50"
                        >
                            <Discord class="w-5 h-5" />
                        </a>
                        <a
                            href="https://github.com/mythicalltd/mythicaldash"
                            target="_blank"
                            class="text-gray-300 hover:text-white transition-colors p-2 rounded-lg hover:bg-gray-700/50"
                        >
                            <Github class="w-5 h-5" />
                        </a>
                        <button
                            @click="openGithubIssue"
                            class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm transition-colors flex items-center gap-2"
                        >
                            <Bug class="w-4 h-4" />
                            <span class="hidden sm:inline">Report Issue</span>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="space-y-6">
                <!-- Header -->
                <div class="bg-gray-800/50 backdrop-blur-lg rounded-xl p-6 shadow-xl border border-gray-700/50">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div>
                            <h1
                                class="text-2xl font-bold bg-gradient-to-r from-blue-400 to-purple-500 bg-clip-text text-transparent"
                            >
                                Log Analysis
                            </h1>
                            <p class="text-gray-400 text-sm mt-1">ID: {{ route.params.id }}</p>
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button
                                @click="copyLogs"
                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 text-sm bg-gray-700/50 hover:bg-gray-700 rounded-lg transition-colors"
                            >
                                <Copy class="w-4 h-4" />
                                <span>Copy Logs</span>
                            </button>
                            <button
                                @click="downloadLogs"
                                class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-4 py-2 text-sm bg-gray-700/50 hover:bg-gray-700 rounded-lg transition-colors"
                            >
                                <Download class="w-4 h-4" />
                                <span>Download</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Logs Display -->
                <div class="bg-gray-800/50 backdrop-blur-lg rounded-xl p-6 shadow-xl border border-gray-700/50">
                    <pre
                        v-if="rawLogs"
                        class="font-mono text-sm text-gray-300 whitespace-pre-wrap break-all bg-gray-900/50 p-4 rounded-lg overflow-x-auto"
                        >{{ rawLogs }}</pre
                    >
                    <div v-else class="text-center text-gray-400 py-8">No logs available</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import { MessageCircle as Discord, Github, Bug, Copy, Download } from 'lucide-vue-next';

const route = useRoute();
const rawLogs = ref<string>('');

const fetchLogs = async () => {
    try {
        const response = await axios.get(`/api/system/logs/${route.params.id}`);
        rawLogs.value = response.data.log.logs.logs.join('\n');
    } catch (error) {
        console.error('Error fetching logs:', error);
    }
};

const copyLogs = async () => {
    if (!rawLogs.value) return;
    try {
        await navigator.clipboard.writeText(rawLogs.value);
    } catch (error) {
        console.error('Error copying logs:', error);
    }
};

const downloadLogs = () => {
    if (!rawLogs.value) return;
    const blob = new Blob([rawLogs.value], { type: 'text/plain' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = `logs-${route.params.id}.txt`;
    a.click();
    window.URL.revokeObjectURL(url);
};

const openGithubIssue = () => {
    const issueUrl = 'https://github.com/mythicalltd/mythicaldash/issues/new';
    window.open(issueUrl, '_blank');
};

onMounted(() => {
    fetchLogs();
});
</script>

<style scoped>
pre {
    max-height: 70vh;
    overflow-y: auto;
}
</style>
