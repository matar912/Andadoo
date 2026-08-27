import { reactive } from 'vue';

// Singleton simple (pas de Pinia necessaire pour ce besoin) : un seul tableau
// de toasts partage par AppLayout (client) et AdminLayout (back-office).
const toasts = reactive([]);
let nextId = 1;

function push(message, type = 'success', timeout = 4000) {
    const id = nextId++;
    toasts.push({ id, message, type });
    if (timeout) {
        setTimeout(() => dismiss(id), timeout);
    }
    return id;
}

function dismiss(id) {
    const i = toasts.findIndex((t) => t.id === id);
    if (i !== -1) toasts.splice(i, 1);
}

export function useToast() {
    return {
        toasts,
        success: (msg, timeout) => push(msg, 'success', timeout),
        error: (msg, timeout) => push(msg, 'error', timeout),
        info: (msg, timeout) => push(msg, 'info', timeout),
        dismiss,
    };
}
