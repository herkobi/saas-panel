<script setup lang="ts">
import { cn } from '@/lib/utils'
import { computed } from 'vue'

interface Props {
  value?: number
  max?: number
}

const props = withDefaults(defineProps<Props>(), {
  value: 0,
  max: 100
})

const percentage = computed(() => {
  return Math.min(Math.max(0, props.value), props.max) / props.max * 100
})
</script>

<template>
  <div
    class="relative h-2 w-full overflow-hidden rounded-full bg-primary/20"
    role="progressbar"
    :aria-valuemin="0"
    :aria-valuemax="max"
    :aria-valuenow="value"
  >
    <div
      class="h-full bg-primary transition-all"
      :style="{ width: `${percentage}%` }"
    ></div>
  </div>
</template>
