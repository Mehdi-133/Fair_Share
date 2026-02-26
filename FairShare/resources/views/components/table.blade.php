<div class="-mx-6 overflow-x-auto">
    <table class="w-full border-collapse text-left">
        <thead class="border-y border-slate-100 bg-slate-50/60">
            <tr>
                {{ $head ?? '' }}
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            {{ $slot }}
        </tbody>
    </table>
</div>

<style>
    thead th {
        padding: 0.75rem 1.5rem;
        font-size: 0.6875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgb(148 163 184);
    }

    tbody td {
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        color: rgb(71 85 105);
        white-space: nowrap;
    }
</style>
