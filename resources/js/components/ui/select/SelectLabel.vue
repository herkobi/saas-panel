<script setup lang="ts">
import { cn } from '@/lib/utils';
import { SelectLabel, useForwardProps, type SelectLabelProps } from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';

interface Props extends SelectLabelProps {
  class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <SelectLabel v-bind="forwardedProps" :class="cn('py-1.5 pl-8 pr-2 text-sm font-semibold', props.class)">
    <slot />
  </SelectLabel>
</template>
