@props([
    'label',
    'value' => '-'
])

<div class="rounded-2xl border border-gray-100 bg-gray-50 p-4">
    <p class="text-sm text-gray-500 mb-1">
        {{ $label }}
    </p>

    <p class="font-semibold text-gray-900 break-words">
        {{ $value ?: '-' }}
    </p>
</div>