@props(['type' => 'info'])
@php
    $styles = [
        'info'    => 'bg-gradient-to-r from-blue-50 to-cyan-50 border-blue-100 text-blue-800',
        'success' => 'bg-gradient-to-r from-emerald-50 to-lime-50 border-emerald-100 text-emerald-800',
        'warning' => 'bg-gradient-to-r from-amber-50 to-yellow-50 border-amber-100 text-amber-800',
        'danger'  => 'bg-gradient-to-r from-rose-50 to-orange-50 border-rose-100 text-rose-800',
    ][$type];
@endphp

<div {{ $attributes->merge(['class' => "flex items-start gap-3 rounded-2xl border p-4 shadow-sm $styles"]) }}>
    <div class="shrink-0 mt-0.5">
        @if($type === 'success') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @elseif($type === 'danger') <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @else <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        @endif
    </div>
    <div class="text-sm font-medium">
        {{ $slot }}
    </div>
</div>
