<x-app-layout>
    @php
        $users = $colocation->users ?? collect();
        $expenses = $colocation->expenses ?? collect();
        $summary = $settlementSummary ?? ['total' => 0, 'share' => 0, 'balances' => collect(), 'settlements' => []];
        $isCancelled = ($colocation->status ?? null) === 'cancelled';
    @endphp

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $colocation->name ?? 'Colocation Details' }}</h2>
            <p class="text-slate-500">{{ $colocation->description ?? 'Manage members and expenses.' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if(!$isCancelled)
                    <a href="{{ route('expenses.create') }}"><x-button>Add Expense</x-button></a>
                @else
                    <x-badge type="warning">Read-only (Cancelled)</x-badge>
                @endif
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-cyan-100 bg-cyan-50/80 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-cyan-700">Total Shared Spend</p>
            <p class="mt-2 text-2xl font-black text-cyan-900">{{ number_format((float) ($summary['total'] ?? 0), 2) }} MAD</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50/80 p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Individual Share</p>
            <p class="mt-2 text-2xl font-black text-amber-900">{{ number_format((float) ($summary['share'] ?? 0), 2) }} MAD</p>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-600">Settlements Needed</p>
            <p class="mt-2 text-2xl font-black text-slate-900">{{ count($summary['settlements'] ?? []) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <x-card title="Expense History">
                @if($expenses->isEmpty())
                    <x-empty-state title="No expenses" description="No expenses were added for this colocation yet." />
                @else
                    <x-table>
                        <x-slot name="head">
                            <th>Title</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </x-slot>
                        @foreach($expenses as $expense)
                            <tr>
                                <td class="font-semibold text-slate-900">{{ $expense->title }}</td>
                                <td><x-badge type="primary">{{ $expense->category->name ?? 'Uncategorized' }}</x-badge></td>
                                <td class="font-semibold text-slate-900">{{ number_format((float)$expense->amount, 2) }} MAD</td>
                                <td>{{ optional($expense->date)->format('M d, Y') }}</td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>

            <x-card title="Who Owes Who" subtitle="Auto-calculated from all expenses">
                @if(empty($summary['settlements']))
                    <x-empty-state title="No settlements needed" description="Everyone is balanced for now." />
                @else
                    <ul class="space-y-3">
                        @foreach($summary['settlements'] as $item)
                            <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 p-3 transition hover:border-slate-200 hover:bg-white">
                                <span class="text-sm text-slate-700">
                                    <strong class="text-slate-900">{{ $item['from']->name }}</strong>
                                    owes
                                    <strong class="text-slate-900">{{ $item['to']->name }}</strong>
                                </span>
                                <strong class="text-slate-900">{{ number_format((float) $item['amount'], 2) }} MAD</strong>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>
        </div>

        <x-card title="Members List" :subtitle="$isCancelled ? 'Roles overview' : 'Roles and actions'">
            <ul class="space-y-3">
                @foreach($users as $member)
                    <li class="flex items-center justify-between rounded-xl border border-slate-100 p-3">
                        <div>
                            <p class="font-medium text-slate-900">{{ $member->name }}</p>
                            <p class="text-xs text-slate-500">{{ $member->email }}</p>
                        </div>
                        <x-badge :type="$member->pivot->role === 'owner' ? 'primary' : 'neutral'">{{ ucfirst($member->pivot->role) }}</x-badge>
                    </li>
                @endforeach
            </ul>

            @if(!$isCancelled)
                <div class="mt-4 space-y-2 border-t border-slate-100 pt-4">
                    <form method="POST" action="{{ route('colocations.leave', $colocation) }}">
                        @csrf
                        <x-button type="submit" variant="secondary" class="w-full">Leave Colocation</x-button>
                    </form>
                    <x-button variant="danger" class="w-full" disabled>Remove Member (Owner only)</x-button>
                </div>
            @endif

            <details class="mt-4 rounded-xl border border-slate-100 bg-slate-50 p-3">
                <summary class="cursor-pointer text-sm font-semibold text-slate-700">Member Balance Details</summary>
                <ul class="mt-3 space-y-2">
                    @foreach(($summary['balances'] ?? []) as $item)
                        <li class="flex items-center justify-between rounded-lg border border-slate-100 bg-white px-3 py-2 text-sm">
                            <span>{{ $item['user']->name }}</span>
                            <strong class="{{ ((float) $item['balance']) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                {{ number_format((float) $item['balance'], 2) }} MAD
                            </strong>
                        </li>
                    @endforeach
                </ul>
            </details>
        </x-card>
    </div>
</x-app-layout>
