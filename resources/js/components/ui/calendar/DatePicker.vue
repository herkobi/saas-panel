<script setup lang="ts">
import { ref, computed, type HTMLAttributes } from 'vue';
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { cn } from '@/lib/utils';
import { CalendarIcon } from 'lucide-vue-next';
import { CalendarDate, getLocalTimeZone, today } from '@internationalized/date';

const props = defineProps<{
  modelValue?: string;
  placeholder?: string;
  class?: HTMLAttributes['class'];
  disabled?: boolean;
  format?: string;
  minValue?: CalendarDate;
  maxValue?: CalendarDate;
}>();

const emit = defineEmits(['update:modelValue']);

// ISO string to/from CalendarDate conversions
const calendarValue = computed({
  get: () => {
    if (!props.modelValue) return undefined;
    try {
      // Tarih formatını YYYY-MM-DD olarak bekliyoruz
      const [year, month, day] = props.modelValue.split('-').map(Number);
      return new CalendarDate(year, month, day);
    } catch (e) {
      console.error('Tarih çözümlenirken hata oluştu:', e);
      return undefined;
    }
  },
  set: (value) => {
    emit('update:modelValue', value ? value.toString() : '');
  }
});

// Format date for display
const formattedDate = computed(() => {
  if (!calendarValue.value) return '';

  try {
    // Convert CalendarDate to JS Date for formatting
    const jsDate = new Date(
      calendarValue.value.year,
      calendarValue.value.month - 1,
      calendarValue.value.day
    );

    // Türkçe tarih formatı
    return new Intl.DateTimeFormat('tr', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    }).format(jsDate);
  } catch (e) {
    console.error('Tarih formatlanırken hata oluştu:', e);
    return props.modelValue || '';
  }
});

// Helper for default date constraints
const defaultMinValue = computed(() =>
  props.minValue || new CalendarDate(1900, 1, 1)
);

const defaultMaxValue = computed(() =>
  props.maxValue || new CalendarDate(2100, 12, 31)
);
</script>

<template>
  <Popover>
    <PopoverTrigger as-child>
      <Button
        variant="outline"
        :disabled="disabled"
        :class="cn(
          'w-full justify-start text-left font-normal',
          !props.modelValue && 'text-muted-foreground',
          props.class
        )"
      >
        <CalendarIcon class="mr-2 h-4 w-4" />
        <span>{{ formattedDate || props.placeholder || 'Tarih seçin' }}</span>
      </Button>
    </PopoverTrigger>
    <PopoverContent class="w-auto p-0">
      <Calendar
        v-model="calendarValue"
        :min-value="defaultMinValue"
        :max-value="defaultMaxValue"
        :disabled="disabled"
        initial-focus
      />
    </PopoverContent>
  </Popover>
</template>
