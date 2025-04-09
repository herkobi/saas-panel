<script setup lang="ts">
import { cn } from '@/lib/utils';
import { SelectValue, useForwardProps, type SelectValueProps } from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';

interface Props extends SelectValueProps {
  class?: HTMLAttributes['class'];
  placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
  placeholder: 'Select an option'
});

const delegatedProps = computed(() => {
  const { class: _, placeholder: __, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <SelectValue v-bind="forwardedProps" :placeholder="props.placeholder" :class="cn('', props.class)">
    <slot />
  </SelectValue>
</template>
