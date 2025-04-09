import { ref, type Ref } from 'vue'

export type ToastProps = {
    id?: string
    title?: string
    description?: string
    action?: any
    variant?: 'default' | 'destructive' | 'success'
    duration?: number
}

const toasts = ref<ToastProps[]>([])

export function useToast() {
    const addToast = (props: ToastProps) => {
        const id = props.id || `toast-${Date.now()}-${Math.random().toString(36).substring(2, 9)}`

        toasts.value.push({
            ...props,
            id,
        })

        return id
    }

    const removeToast = (id: string) => {
        toasts.value = toasts.value.filter((toast) => toast.id !== id)
    }

    const updateToast = (id: string, props: Partial<ToastProps>) => {
        toasts.value = toasts.value.map((toast) => (toast.id === id ? { ...toast, ...props } : toast))
    }

    const toast = (props: ToastProps) => addToast(props)

    return {
        toasts,
        addToast,
        removeToast,
        updateToast,
        toast,
    }
}
