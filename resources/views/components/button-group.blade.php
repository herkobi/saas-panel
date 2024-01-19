@props(['buttons' => []])

<div class="inline-flex rounded-lg shadow-sm">
    @foreach ($buttons as $index => $button)
        <button type="button" class="{{ $button['classes'] }}">
            {{ $button['label'] }}
        </button>
    @endforeach
</div>
