@props(['size', 'rounded', 'imageUrl', 'alt'])

@php
    // Define default size if not provided
    $size = $size ?? '2.375rem';
@endphp

<img class="inline-block h-{{ $size }} w-{{ $size }} {{ $rounded }}" src="{{ $imageUrl }}"
    alt="{{ $alt }}">
