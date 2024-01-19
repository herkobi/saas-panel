@props(['bgColor', 'textColor', 'fontBold', 'role', 'message'])

<div class="{{ $bgColor }} text-sm text-{{ $textColor }} rounded-lg p-4 {{ $role }}"
    role="{{ $role }}">
    <span class="font-bold">{{ $fontBold }}</span> alert! {{ $message }}
</div>
