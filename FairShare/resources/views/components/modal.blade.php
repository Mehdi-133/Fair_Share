@props(['name', 'title'])
<div 
    x-data="{ show: false }" 
    x-show="show" 
    @open-modal.window="if ($event.detail === '{{ $name }}') show = true"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-50 overflow-y-auto" 
    style="display: none;"
>
    <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

    <div class="relative min-h-screen flex items-center justify-center p-4">
        <div 
            @click.away="show = false"
            class="relative bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden transform transition-all"
        >
            <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-900">{{ $title }}</h3>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>