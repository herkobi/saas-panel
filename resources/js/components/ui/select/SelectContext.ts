import { InjectionKey, inject, provide, Ref } from 'vue';

export interface SelectContext {
    selectedValue: Ref<string | null>;
    updateSelectedValue: (value: string | null) => void;
    open: Ref<boolean>;
    setOpen: (value: boolean) => void;
}

export const SelectContextKey = Symbol('SelectContext') as InjectionKey<SelectContext>;

export function provideSelectContext(context: SelectContext) {
    provide(SelectContextKey, context);
}

export function useSelectContext() {
    const context = inject(SelectContextKey);

    if (!context) {
        throw new Error('useSelectContext must be used within a Select component');
    }

    return context;
}
