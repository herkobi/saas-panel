<script setup lang="ts">
import { cn } from '@/lib/utils'

defineProps<{
  class?: string
}>()
</script>

<template>
  <div :class="cn('text-sm opacity-90', $props.class)">
    <slot />
  </div>
</template>
