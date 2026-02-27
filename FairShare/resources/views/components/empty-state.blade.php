@props(['title', 'description', 'icon' => 'folder-plus'])
<div class="rounded-3xl border border-dashed border-slate-200 bg-gradient-to-b from-white to-slate-50 px-4 py-16 text-center">
    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-500">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
    <p class="mx-auto mt-2 max-w-xs text-sm text-slate-500">{{ $description }}</p>
    <div class="mt-6">
        {{ $slot }}
    </div>
</div>
