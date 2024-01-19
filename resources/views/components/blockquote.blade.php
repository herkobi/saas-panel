@props([
    'borderColor' => 'border-s-4',
    'avatarUrl' =>
        'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=facearea&facepad=2&w=300&h=300&q=80',
    'name' => 'Josh Grazioso',
    'sourceTitle' => 'Source title',
])

<blockquote class="relative {{ $borderColor }} ps-4 sm:ps-6 dark:border-gray-700">
    <p class="text-gray-800 sm:text-xl dark:text-white"><em>
            {{ $slot }}
        </em></p>

    <footer class="mt-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full" src="{{ $avatarUrl }}" alt="Image Description">
            </div>
            <div class="ms-4">
                <div class="text-base font-semibold text-gray-800 dark:text-gray-400">{{ $name }}</div>
                <div class="text-xs text-gray-500">{{ $sourceTitle }}</div>
            </div>
        </div>
    </footer>
</blockquote>
