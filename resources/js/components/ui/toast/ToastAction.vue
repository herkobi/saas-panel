<script setup lang="ts">
import { cn } from '@/lib/utils'

defineProps<{
  class?: string
  altText: string
}>()
</script>

<template>
  <div :class="cn('flex shrink-0 items-center', $props.class)">
    <slot />
  </div>
</template>
