<x-app-layout>
    @php
        $list = $expenses ?? collect();
    @endphp

    <section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Expense History</h2>
            <p class="text-slate-500">Track all shared expenses by category and month.</p>
        </div>

        <div class="flex flex-wrap gap-2">
            <select class="rounded-xl border-slate-200 text-sm">
                <option>Monthly Filter (UI)</option>
                <option>January</option>
                <option>February</option>
                <option>March</option>
            </select>
            <a href="{{ route('expenses.create') }}"><x-button>Add Expense</x-button></a>
        </div>
    </section>

    <x-card>
        @if($list->isEmpty())
            <x-empty-state title="No expenses found" description="No expenses match this filter yet.">
                <a href="{{ route('expenses.create') }}"><x-button>Add First Expense</x-button></a>
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

                @foreach($list as $expense)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $expense->title }}</td>
                        <td><x-badge type="primary">{{ $expense->category->name ?? 'Uncategorized' }}</x-badge></td>
                        <td>{{ $expense->payer->name ?? 'Unknown' }}</td>
                        <td class="font-semibold text-slate-900">{{ number_format((float)$expense->amount, 2) }} MAD</td>
                        <td>{{ optional($expense->date)->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </x-card>
</x-app-layout>
