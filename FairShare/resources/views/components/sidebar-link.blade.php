@props(['icon' => 'home', 'active' => false])

@php
    $base = 'group flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all';
    $state = $active
        ? 'bg-indigo-50 text-indigo-700'
        : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900';

    $icons = [
        'home' => 'M3.75 9.776l8.25-6.19 8.25 6.19v9.474a.75.75 0 01-.75.75h-4.5v-6a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75v6H4.5a.75.75 0 01-.75-.75V9.776z',
        'users' => 'M18 18.72a8.96 8.96 0 00-6-2.22 8.96 8.96 0 00-6 2.22M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        'receipt' => 'M9 14.25h6m-6-3h6m-6-3h6M5.25 3h13.5A2.25 2.25 0 0121 5.25v13.5l-3-1.5-3 1.5-3-1.5-3 1.5-3-1.5-3 1.5V5.25A2.25 2.25 0 015.25 3z',
        'chart' => 'M7.5 13.5v3m4.5-6v6m4.5-9v9M3 19.5h18M4.5 4.5h15a1.5 1.5 0 011.5 1.5v12a1.5 1.5 0 01-1.5 1.5h-15A1.5 1.5 0 013 18V6a1.5 1.5 0 011.5-1.5z',
        'shield' => 'M9 12l2 2 4-4m5.25-3.375c0 7.125-5.25 11.25-8.25 12.375C9 17.875 3.75 13.75 3.75 6.625V5.25L12 2.25l8.25 3v1.375z',
    ];

    $path = $icons[$icon] ?? $icons['home'];
@endphp

<a {{ $attributes->merge(['class' => "$base $state"]) }}>
    <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
    </svg>
    <span>{{ $slot }}</span>
</a>
