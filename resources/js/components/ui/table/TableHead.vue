<script setup lang="ts">
import { cn } from '@/lib/utils';
import type { HTMLAttributes } from 'vue';

const props = defineProps<{
    class?: HTMLAttributes['class'];
}>();
</script>

<template>
    <th
        :class="cn(
            'px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0',
            props.class
        )"
    >
        <slot />
    </th>
</template>
