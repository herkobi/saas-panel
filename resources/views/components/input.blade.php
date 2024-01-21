@props(['type' => 'text', 'placeholder' => null, 'value' => null, 'disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'type' => $type,
    'class' =>
        'shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 w-full focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800',
]) !!} placeholder="{{ $placeholder }}"
    value="{{ $value }} ">
