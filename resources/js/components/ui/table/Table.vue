<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <div :class="cn('w-full overflow-auto', props.class)">
        <table class="w-full caption-bottom text-sm">
            <slot />
        </table>
    </div>
</template>
