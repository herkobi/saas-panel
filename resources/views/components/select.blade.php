@props(['options', 'name', 'id', 'selectedValue'])

<select name="{{ $name }}" id="{{ $id }}"
    {{ $attributes->merge(['class' => 'py-3 px-4 pe-9 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600']) }}>
    <option {{ is_null($selectedValue) ? 'selected' : '' }}>{{ __('global.selected') }}</option>
    @foreach ($options as $value => $label)
        <option {{ !empty($selectedValue) ? ($selectedValue == $value ? 'selected' : '') : '' }}
            value="{{ $value }}">
            {{ $label }}
        </option>
    @endforeach

</select>
