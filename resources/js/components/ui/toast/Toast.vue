<script setup lang="ts">
import { cva, type VariantProps } from 'class-variance-authority'
import { Transition } from 'vue'
import { cn } from '@/lib/utils'
import { ref, onMounted, onUnmounted, computed } from 'vue'

interface ToastProps extends /* @vue-ignore */ VariantProps<typeof toastVariants> {
  class?: string
  duration?: number
  onOpenChange?: (open: boolean) => void
}

const props = withDefaults(defineProps<ToastProps>(), {
  duration: 5000,
  variant: 'default',
})

const emit = defineEmits<{
  'update:open': [open: boolean]
}>()

const visible = ref(true)

defineExpose({
  visible,
})

onMounted(() => {
  if (props.duration) {
    const timeout = setTimeout(() => close(), props.duration)
    onUnmounted(() => clearTimeout(timeout))
  }
})

function close() {
  visible.value = false
  emit('update:open', false)
  props.onOpenChange?.(false)
}

const toastVariants = cva(
  'group pointer-events-auto relative flex w-full items-center justify-between space-x-4 overflow-hidden rounded-md border p-6 pr-8 shadow-lg transition-all',
  {
    variants: {
      variant: {
        default: 'border bg-background text-foreground',
        destructive: 'border-red-500 bg-red-600 text-white',
        success: 'border-green-500 bg-green-500 text-white'
      },
    },
    defaultVariants: {
      variant: 'default',
    },
  }
)

const classes = computed(() =>
  cn(toastVariants({ variant: props.variant }), props.class)
)
</script>

<template>
  <Transition
    enter-active-class="transition ease-out duration-300 transform"
    enter-from-class="opacity-0 translate-y-2"
    enter-to-class="opacity-100 translate-y-0"
    leave-active-class="transition ease-in duration-100"
    leave-from-class="opacity-100"
    leave-to-class="opacity-0"
    appear
  >
    <div v-if="visible" :class="classes">
      <slot />
    </div>
  </Transition>
</template>
