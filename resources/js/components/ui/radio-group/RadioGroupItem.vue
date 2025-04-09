<script setup lang="ts">
import { cn } from '@/lib/utils';
import { Circle } from 'lucide-vue-next';
import {
  RadioGroupItem,
  RadioGroupIndicator,
  useForwardProps,
  type RadioGroupItemProps,
} from 'radix-vue';
import { computed, type HTMLAttributes } from 'vue';
import { useRadioGroupContext } from './RadioGroupContext';

interface Props extends RadioGroupItemProps {
  class?: HTMLAttributes['class'];
}

const props = defineProps<Props>();

// Use context
const context = useRadioGroupContext();

// Check if this item is selected
const isSelected = computed(() => context.value.value === props.value);

// Forward props to Radix
const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props;
  return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
  <div :class="cn('flex items-center space-x-2', props.class)">
    <RadioGroupItem
      v-bind="forwardedProps"
      :disabled="context.disabled"
      :class="cn(
        'aspect-square h-4 w-4 rounded-full border border-primary text-primary shadow focus:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50',
        props.class
      )"
    >
      <RadioGroupIndicator class="flex items-center justify-center">
        <Circle class="h-2.5 w-2.5 fill-current text-current" />
      </RadioGroupIndicator>
    </RadioGroupItem>
    <slot />
  </div>
</template>
