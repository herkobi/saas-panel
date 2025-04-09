<script setup lang="ts">
import { X } from 'lucide-vue-next'
import { cn } from '@/lib/utils'
import { Button } from '../button'

defineProps<{
  class?: string
}>()
</script>

<template>
  <div :class="cn('absolute right-2 top-2 rounded-md p-1', $props.class)">
    <Button variant="ghost" class="h-4 w-4 p-0" @click="$emit('click')">
      <X class="h-4 w-4" />
      <span class="sr-only">Close</span>
    </Button>
  </div>
</template>
