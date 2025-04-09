<script setup lang="ts">
import Toast from './Toast.vue'
import ToastClose from './ToastClose.vue'
import ToastTitle from './ToastTitle.vue'
import ToastDescription from './ToastDescription.vue'
import { h, ref, watch } from 'vue'
import { useToast } from './use-toast'

const { toasts, removeToast } = useToast()

const localToasts = ref([...toasts.value])

watch(toasts, (newToasts) => {
  localToasts.value = [...newToasts]
}, { deep: true })

const handleClose = (id: string) => {
  removeToast(id)
}

const renderToastContent = (toast) => {
  const { id, title, description, action, ...restProps } = toast

  return h(Toast, {
    key: id,
    ...restProps,
    onOpenChange: (open) => {
      if (!open) handleClose(id)
    }
  }, {
    default: () => [
      h('div', { class: 'grid gap-1' }, [
        title && h(ToastTitle, {}, { default: () => title }),
        description && h(ToastDescription, {}, { default: () => description })
      ]),
      action,
      h(ToastClose, { onClick: () => handleClose(id) })
    ]
  })
}
</script>

<template>
  <div
    class="fixed top-0 right-0 z-[100] flex max-h-screen w-full flex-col gap-2 p-4 md:max-w-[420px]"
  >
    <transition-group
      tag="div"
      enter-active-class="transition ease-out duration-300 transform"
      enter-from-class="opacity-0 translate-y-2"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition ease-in duration-200"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 translate-y-2"
    >
      <template v-for="toast in localToasts" :key="toast.id">
        <component :is="renderToastContent(toast)" />
      </template>
    </transition-group>
  </div>
</template>
