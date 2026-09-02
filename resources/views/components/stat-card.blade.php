@props(['label', 'value', 'accent' => 'indigo'])

@php
    $accents = [
        'indigo' => 'bg-indigo-50 text-indigo-600',
        'amber' => 'bg-amber-50 text-amber-600',
        'red' => 'bg-red-50 text-red-600',
        'emerald' => 'bg-emerald-50 text-emerald-600',
    ];
@endphp

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
    <p class="text-sm font-medium text-gray-500">{{ $label }}</p>
    <p class="mt-2 text-2xl font-semibold text-gray-900">{{ $value }}</p>
    @isset($icon)
        <div class="mt-3 inline-flex h-9 w-9 items-center justify-center rounded-lg {{ $accents[$accent] ?? $accents['indigo'] }}">
            {{ $icon }}
        </div>
    @endisset
</div>
