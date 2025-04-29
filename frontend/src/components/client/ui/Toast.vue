<template>
    <TransitionGroup
        tag="div"
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-2 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform translate-y-2 opacity-0"
        class="fixed bottom-4 right-4 z-50 flex flex-col gap-2"
    >
        <div
            v-for="toast in toasts"
            :key="toast.id"
            :class="[
                'px-4 py-2 rounded-lg shadow-lg text-white',
                {
                    'bg-green-500': toast.type === 'success',
                    'bg-red-500': toast.type === 'error',
                    'bg-blue-500': toast.type === 'info',
                    'bg-yellow-500': toast.type === 'warning',
                },
            ]"
        >
            {{ toast.message }}
        </div>
    </TransitionGroup>
</template>

<script setup lang="ts">
import { useToast } from '@/composables/useToast';

const { toasts } = useToast();
</script>
