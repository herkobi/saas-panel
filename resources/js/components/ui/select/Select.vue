<script setup lang="ts">
import { ref, watch, type HTMLAttributes } from 'vue';
import { cn } from '@/lib/utils';
import { SelectRoot, useForwardPropsEmits, type SelectRootEmits, type SelectRootProps } from 'radix-vue';
import { provideSelectContext } from './SelectContext';

interface Props extends SelectRootProps {
  modelValue?: string | null;
  class?: HTMLAttributes['class'];
}

const props = withDefaults(defineProps<Props>(), {
  modelValue: null
});

const emits = defineEmits<{
  (e: 'update:modelValue', value: string | null): void;
  (e: SelectRootEmits): void;
}>();

// Local state
const selectedValue = ref(props.modelValue);
const open = ref(false);

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  selectedValue.value = newValue;
});

// Update function
const updateSelectedValue = (value: string | null) => {
  selectedValue.value = value;
  emits('update:modelValue', value);
};

const setOpen = (value: boolean) => {
  open.value = value;
};

// Forward props to Radix
const forwarded = useForwardPropsEmits(props, emits);

// Provide context to child components
provideSelectContext({
  selectedValue,
  updateSelectedValue,
  open,
  setOpen
});
</script>

<template>
  <SelectRoot v-bind="forwarded" :open="open" @update:open="setOpen">
    <slot />
  </SelectRoot>
</template>
