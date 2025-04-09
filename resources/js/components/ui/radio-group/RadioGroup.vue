<script setup lang="ts">
import { cn } from '@/lib/utils';
import { RadioGroupRoot, useForwardPropsEmits, type RadioGroupRootEmits, type RadioGroupRootProps } from 'radix-vue';
import { ref, watch, type HTMLAttributes } from 'vue';
import { provideRadioGroupContext } from './RadioGroupContext';

interface Props extends RadioGroupRootProps {
  modelValue?: string;
  defaultValue?: string;
  disabled?: boolean;
  class?: HTMLAttributes['class'];
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: undefined,
  defaultValue: '',
  disabled: false
});

const emits = defineEmits<{
  (e: 'update:modelValue', value: string): void;
  (e: RadioGroupRootEmits): void;
}>();

// Local state with defaultValue and modelValue handling
const value = ref(props.modelValue !== undefined ? props.modelValue : props.defaultValue);

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== undefined) {
    value.value = newValue;
  }
});

// Update function
const updateValue = (newValue: string) => {
  value.value = newValue;
  emits('update:modelValue', newValue);
};

// Provide context to child components
provideRadioGroupContext({
  value,
  updateValue,
  disabled: props.disabled
});

// Forward props to Radix
const forwarded = useForwardPropsEmits(props, emits);
</script>

<template>
  <RadioGroupRoot v-bind="forwarded" :class="cn('grid gap-2', props.class)">
    <slot />
  </RadioGroupRoot>
</template>
