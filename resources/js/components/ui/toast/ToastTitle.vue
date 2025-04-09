<script setup lang="ts">
import { cn } from '@/lib/utils'

defineProps<{
  class?: string
}>()
</script>

<template>
  <div :class="cn('text-sm font-medium', $props.class)">
    <slot />
  </div>
</template>
