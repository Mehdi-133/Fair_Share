<x-app-layout>
    @php
        $col = $colocation ?? null;
        $colocationName = $col?->name ?? 'No Active Colocation';
        $recentExpenses = $recentExpenses ?? collect();
    @endphp

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Member Dashboard</h2>
            <p class="text-slate-500">Overview of your current shared expenses and balances.</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('balances.index') }}"><x-button variant="secondary">View Settlements</x-button></a>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-card title="Current Colocation"><p class="text-lg font-semibold text-slate-900">{{ $colocationName }}</p></x-card>
        <x-card title="Current Balance"><p class="text-2xl font-bold {{ ($currentBalance ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format((float) ($currentBalance ?? 0), 2) }} MAD</p></x-card>
        <x-card title="Total Paid"><p class="text-2xl font-bold text-slate-900">{{ number_format((float) ($paidByUser ?? 0), 2) }} MAD</p></x-card>
        <x-card title="Individual Share"><p class="text-2xl font-bold text-slate-900">{{ number_format((float) ($individualShare ?? 0), 2) }} MAD</p></x-card>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <x-card title="Recent Expenses" subtitle="Latest shared payments">
                @if($recentExpenses->isEmpty())
                    <x-empty-state title="No expenses yet" description="Add your first expense to start tracking shared costs.">
                        <a href="{{ route('colocations.index') }}"><x-button>Go to Colocations</x-button></a>
                    </x-empty-state>
                @else
                    <x-table>
                        <x-slot name="head">
                            <th>Title</th>
                            <th>Category</th>
                            <th>Payer</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </x-slot>
                        @foreach($recentExpenses as $expense)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $expense->title }}</td>
                                <td>
                                    <x-badge type="primary">{{ $expense->category->name ?? 'Uncategorized' }}</x-badge>
                                </td>
                                <td>{{ $expense->payer->name ?? 'Unknown' }}</td>
                                <td class="font-semibold text-slate-900">{{ number_format((float) $expense->amount, 2) }} MAD</td>
                                <td>{{ optional($expense->date)->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>
        </div>

        <x-card title="Quick Summary" subtitle="Dynamic overview">
            <ul class="space-y-3 text-sm">
                <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-3 py-2"><span>Total Colocation Expenses</span><strong>{{ number_format((float) ($totalExpenses ?? 0), 2) }} MAD</strong></li>
                <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-3 py-2"><span>Paid By You</span><strong>{{ number_format((float) ($paidByUser ?? 0), 2) }} MAD</strong></li>
                <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-3 py-2"><span>Your Share</span><strong>{{ number_format((float) ($individualShare ?? 0), 2) }} MAD</strong></li>
            </ul>
        </x-card>
    </div>
</x-app-layout>
