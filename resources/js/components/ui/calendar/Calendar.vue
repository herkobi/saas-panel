<script setup lang="ts">
import { ref, computed, type HTMLAttributes } from 'vue';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { cn } from '@/lib/utils';
import {
  CalendarDate,
  createCalendar,
  getLocalTimeZone,
  getWeeksInMonth,
  endOfMonth,
  startOfMonth
} from '@internationalized/date';

const props = defineProps<{
  modelValue?: CalendarDate | null;
  placeholder?: CalendarDate | null;
  class?: HTMLAttributes['class'];
  initialFocus?: boolean;
  numOfMonths?: number;
  calendarLabel?: string;
  fixedWeeks?: boolean;
  minValue?: CalendarDate;
  maxValue?: CalendarDate;
  disabled?: boolean;
  weekStartsOn?: number;
}>();

const emit = defineEmits([
  'update:modelValue',
  'update:placeholder'
]);

const localValue = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
});

const localPlaceholder = computed({
  get: () => props.placeholder,
  set: (value) => emit('update:placeholder', value)
});

// Create calendar with explicit locale to avoid errors
const calendar = createCalendar({ locale: 'tr' }); // Türkçe locale kullanılıyor, sisteminize göre değiştirebilirsiniz
const timezone = getLocalTimeZone();

// Calendar state
const currentMonth = ref(props.modelValue ?
  new CalendarDate(props.modelValue.year, props.modelValue.month, 1) :
  new CalendarDate(new Date().getFullYear(), new Date().getMonth() + 1, 1));

// Days of the week, starting from Sunday (0) or the specified weekStartsOn
const daysOfWeek = computed(() => {
  const days = ['Paz', 'Pzt', 'Sal', 'Çar', 'Per', 'Cum', 'Cmt']; // Türkçe gün kısaltmaları
  return [...days.slice(props.weekStartsOn || 0), ...days.slice(0, props.weekStartsOn || 0)];
});

// Get calendar weeks for the current month
const calendarWeeks = computed(() => {
  const weeksInMonth = getWeeksInMonth(currentMonth.value, timezone);
  const monthStart = startOfMonth(currentMonth.value);
  const monthEnd = endOfMonth(currentMonth.value);

  const weeks = [];
  const firstDay = calendar.getWeekday(monthStart);
  const offset = (firstDay - (props.weekStartsOn || 0) + 7) % 7;

  let date = monthStart.subtract({ days: offset });

  for (let week = 0; week < ((props.fixedWeeks ? 6 : weeksInMonth) || 6); week++) {
    const days = [];
    for (let day = 0; day < 7; day++) {
      const isCurrentMonth = date.month === currentMonth.value.month;
      days.push({
        date,
        isCurrentMonth,
        isDisabled: isDateDisabled(date)
      });
      date = date.add({ days: 1 });
    }
    weeks.push(days);
  }

  return weeks;
});

// Format month and year for display
const formattedMonthYear = computed(() => {
  try {
    const formatter = new Intl.DateTimeFormat('tr', { month: 'long', year: 'numeric' }); // Türkçe locale
    const date = new Date(currentMonth.value.year, currentMonth.value.month - 1, 1);
    return formatter.format(date);
  } catch (e) {
    // Fallback eğer hata oluşursa
    const months = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    return `${months[currentMonth.value.month - 1]} ${currentMonth.value.year}`;
  }
});

// Check if a date is disabled
function isDateDisabled(date: CalendarDate) {
  if (props.minValue && date.compare(props.minValue) < 0) {
    return true;
  }
  if (props.maxValue && date.compare(props.maxValue) > 0) {
    return true;
  }
  return props.disabled || false;
}

// Navigate to previous month
function previousMonth() {
  currentMonth.value = currentMonth.value.subtract({ months: 1 });
}

// Navigate to next month
function nextMonth() {
  currentMonth.value = currentMonth.value.add({ months: 1 });
}

// Select a date
function selectDate(date: CalendarDate) {
  if (!isDateDisabled(date)) {
    localValue.value = date;
  }
}

// Check if date is selected
function isSelected(date: CalendarDate) {
  return localValue.value && date.compare(localValue.value) === 0;
}

// Check if date is today
function isToday(date: CalendarDate) {
  try {
    const today = new CalendarDate(new Date().getFullYear(), new Date().getMonth() + 1, new Date().getDate());
    return date.compare(today) === 0;
  } catch (e) {
    // Fallback eğer hata oluşursa
    return false;
  }
}
</script>

<template>
  <div :class="cn('calendar', props.class)">
    <div class="flex items-center justify-between p-2">
      <Button
        type="button"
        variant="outline"
        class="h-7 w-7 bg-transparent p-0"
        @click="previousMonth"
      >
        <ChevronLeft class="h-4 w-4" />
        <span class="sr-only">Önceki ay</span>
      </Button>
      <div class="font-medium">{{ formattedMonthYear }}</div>
      <Button
        type="button"
        variant="outline"
        class="h-7 w-7 bg-transparent p-0"
        @click="nextMonth"
      >
        <ChevronRight class="h-4 w-4" />
        <span class="sr-only">Sonraki ay</span>
      </Button>
    </div>
    <div class="grid grid-cols-7 gap-1 p-2">
      <div
        v-for="(day, index) in daysOfWeek"
        :key="index"
        class="text-center text-xs font-medium text-muted-foreground"
      >
        {{ day }}
      </div>
    </div>
    <div class="p-2">
      <div
        v-for="(week, weekIndex) in calendarWeeks"
        :key="weekIndex"
        class="grid grid-cols-7 gap-1"
      >
        <div
          v-for="(day, dayIndex) in week"
          :key="dayIndex"
          class="flex justify-center"
        >
          <Button
            type="button"
            variant="ghost"
            :class="cn(
              'h-8 w-8 rounded-full p-0 font-normal',
              !day.isCurrentMonth && 'text-muted-foreground opacity-50',
              isSelected(day.date) && 'bg-primary text-primary-foreground hover:bg-primary hover:text-primary-foreground focus:bg-primary focus:text-primary-foreground',
              isToday(day.date) && !isSelected(day.date) && 'border border-primary',
              day.isDisabled && 'pointer-events-none opacity-30'
            )"
            :disabled="day.isDisabled"
            @click="selectDate(day.date)"
          >
            {{ day.date.day }}
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>
