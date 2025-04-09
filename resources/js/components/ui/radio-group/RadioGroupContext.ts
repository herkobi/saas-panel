import { InjectionKey, inject, provide, Ref } from 'vue';

export interface RadioGroupContext {
    value: Ref<string>;
    updateValue: (value: string) => void;
    disabled?: boolean;
}

export const RadioGroupContextKey = Symbol('RadioGroupContext') as InjectionKey<RadioGroupContext>;

export function provideRadioGroupContext(context: RadioGroupContext) {
    provide(RadioGroupContextKey, context);
}

export function useRadioGroupContext() {
    const context = inject(RadioGroupContextKey);

    if (!context) {
        throw new Error('useRadioGroupContext must be used within a RadioGroup component');
    }

    return context;
}
