@props(['title' => null, 'subtitle' => null])
<div {{ $attributes->merge(['class' => 'group overflow-hidden rounded-3xl border border-slate-200/80 bg-white/95 shadow-sm backdrop-blur transition-all hover:-translate-y-0.5 hover:shadow-md']) }}>
    @if($title)
    <div class="flex items-center justify-between border-b border-slate-100 bg-gradient-to-r from-slate-50/70 to-white px-6 py-4">
        <div>
            <h3 class="font-semibold tracking-tight text-slate-900">{{ $title }}</h3>
            @if($subtitle)<p class="text-xs text-slate-500">{{ $subtitle }}</p>@endif
        </div>
        {{ $action ?? '' }}
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
