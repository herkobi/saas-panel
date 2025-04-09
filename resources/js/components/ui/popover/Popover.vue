<script setup lang="ts">
import { PopoverRoot, useForwardPropsEmits, type PopoverRootEmits, type PopoverRootProps } from 'radix-vue';

const props = defineProps<PopoverRootProps>();
const emits = defineEmits<PopoverRootEmits>();

const forwarded = useForwardPropsEmits(props, emits);
</script>

<template>
    <PopoverRoot v-bind="forwarded">
        <slot />
    </PopoverRoot>
</template>
