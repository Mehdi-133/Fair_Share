@props(['variant' => 'primary'])
@php
    $styles = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-md shadow-indigo-100',
        'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50',
        'danger' => 'bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-100',
        'outline' => 'bg-transparent border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50'
    ];
@endphp
<button {{ $attributes->merge(['class' => "px-5 py-2.5 rounded-xl font-semibold transition-all duration-200 active:scale-95 text-sm flex items-center justify-center gap-2 " . $styles[$variant]]) }}>
    {{ $slot }}
</button>