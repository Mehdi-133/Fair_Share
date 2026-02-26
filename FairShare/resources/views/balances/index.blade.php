<x-app-layout>
    <section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Balances & Settlements</h2>
            <p class="text-slate-500">Simplified summary of what each member owes or receives.</p>
        </div>
        <x-button loading>Refreshing</x-button>
    </section>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <x-card title="Total Paid"><p class="text-2xl font-bold text-slate-900">6,580.00 MAD</p></x-card>
        <x-card title="Individual Share"><p class="text-2xl font-bold text-slate-900">1,645.00 MAD</p></x-card>
        <x-card title="Current Balance"><p class="text-2xl font-bold text-rose-600">-320.00 MAD</p></x-card>
    </div>

    <x-card title="Who Owes Who" subtitle="Clean settlement list">
        <ul class="space-y-3">
            <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 p-3">
                <span class="text-sm text-slate-700">Alex owes Sarah</span>
                <div class="flex items-center gap-2">
                    <strong>120.00 MAD</strong>
                    <x-button variant="secondary" class="!px-3 !py-1.5">Mark as Paid</x-button>
                </div>
            </li>
            <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 p-3">
                <span class="text-sm text-slate-700">You owe Marc</span>
                <div class="flex items-center gap-2">
                    <strong>30.00 MAD</strong>
                    <x-button variant="secondary" class="!px-3 !py-1.5">Mark as Paid</x-button>
                </div>
            </li>
        </ul>

        <div class="mt-5 space-y-2">
            <x-alert type="success">Settlement marked as paid successfully.</x-alert>
            <x-alert type="warning">Some settlements are still pending.</x-alert>
        </div>
    </x-card>
</x-app-layout>
