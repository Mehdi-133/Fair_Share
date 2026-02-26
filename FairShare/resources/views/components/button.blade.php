@props([
    'variant' => 'primary',
    'loading' => false,
])

@php
    $styles = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-sm shadow-indigo-200 disabled:bg-indigo-400',
        'secondary' => 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50 disabled:text-slate-400',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 shadow-sm shadow-rose-200 disabled:bg-rose-400',
        'outline' => 'bg-transparent border border-indigo-300 text-indigo-700 hover:bg-indigo-50 disabled:text-indigo-300',
    ];
@endphp

<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold transition-all active:scale-[0.98] disabled:cursor-not-allowed '.($styles[$variant] ?? $styles['primary'])]) }}>
    @if($loading)
        <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" class="opacity-30" />
            <path d="M22 12a10 10 0 00-10-10" stroke="currentColor" stroke-width="3" class="opacity-90" />
        </svg>
    @endif
    {{ $slot }}
</button>
