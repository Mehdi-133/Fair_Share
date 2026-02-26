@props(['type' => 'neutral'])
@php
    $classes = [
        'neutral' => 'bg-slate-100 text-slate-700 border-slate-200',
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-100',
        'danger' => 'bg-rose-50 text-rose-700 border-rose-100',
        'primary' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
    ][$type] ?? $classes['neutral'];
@endphp
<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold $classes"]) }}>
    {{ $slot }}
</span>