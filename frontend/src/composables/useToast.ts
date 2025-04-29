import { ref } from 'vue';

type ToastType = 'success' | 'error' | 'info' | 'warning';

interface Toast {
    message: string;
    type: ToastType;
    id: number;
}

const toasts = ref<Toast[]>([]);
let nextId = 1;

export function useToast() {
    const showToast = (message: string, type: ToastType = 'info') => {
        const id = nextId++;
        toasts.value.push({ message, type, id });

        // Auto remove after 3 seconds
        setTimeout(() => {
            toasts.value = toasts.value.filter((toast) => toast.id !== id);
        }, 3000);
    };

    return {
        toasts,
        showToast,
    };
}
