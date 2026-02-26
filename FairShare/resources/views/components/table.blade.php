<div class="overflow-x-auto -mx-6">
    <table class="w-full text-left border-collapse">
        <thead class="bg-slate-50/50 border-y border-slate-100">
            <tr>
                @if(isset($head))
                    {{ $head }}
                @endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            {{ $slot }}
        </tbody>
    </table>
</div>

<style>
    /* Scoping table header styles to match the SaaS look */
    thead th {
        @apply px-6 py-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider;
    }
    tbody td {
        @apply px-6 py-4 text-sm text-slate-600 whitespace-nowrap;
    }
</style>