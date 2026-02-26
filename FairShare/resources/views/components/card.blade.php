@props(['title' => null, 'subtitle' => null])
<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden']) }}>
    @if($title)
    <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-slate-800 tracking-tight">{{ $title }}</h3>
            @if($subtitle)<p class="text-xs text-slate-500">{{ $subtitle }}</p>@endif
        </div>
        {{ $action ?? '' }}
    </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>