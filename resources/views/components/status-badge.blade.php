@php
    $classes = match ($status) {
        'pago' => 'bg-emerald-100 text-emerald-700',
        'aberto' => 'bg-amber-100 text-amber-700',
        'vencido' => 'bg-red-100 text-red-700',
        default => 'bg-gray-100 text-gray-700',
    };
    $label = match ($status) {
        'pago' => 'Pago',
        'aberto' => 'Em aberto',
        'vencido' => 'Vencido',
        default => ucfirst($status),
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {$classes}"]) }}>
    {{ $label }}
</span>
