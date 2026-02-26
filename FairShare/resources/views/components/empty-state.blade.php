@props(['title', 'description', 'icon' => 'folder-plus'])
<div class="text-center py-16 px-4 bg-white border border-dashed border-slate-200 rounded-3xl">
    <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-300">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>
    <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
    <p class="text-slate-500 text-sm max-w-xs mx-auto mt-2">{{ $description }}</p>
    <div class="mt-6">
        {{ $slot }}
    </div>
</div>