<script setup lang="ts">
import { PopoverTrigger, useForwardProps, type PopoverTriggerProps } from 'radix-vue';

const props = defineProps<PopoverTriggerProps>();

const forwardedProps = useForwardProps(props);
</script>

<template>
    <PopoverTrigger v-bind="forwardedProps">
        <slot />
    </PopoverTrigger>
</template>
