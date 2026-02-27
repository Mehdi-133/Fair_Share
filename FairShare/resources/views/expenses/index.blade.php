<x-app-layout>
    @php
        $list = $expenses ?? collect();
    @endphp

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
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
        </div>
    </section>

    <x-card>
        @if(session('success'))
            <x-alert type="success">{{ session('success') }}</x-alert>
        @endif

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
                    <th>Actions</th>
                </x-slot>

                @foreach($list as $expense)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $expense->title }}</td>
                        <td><x-badge type="primary">{{ $expense->category->name ?? 'Uncategorized' }}</x-badge></td>
                        <td>{{ $expense->payer->name ?? 'Unknown' }}</td>
                        <td class="font-semibold text-slate-900">{{ number_format((float)$expense->amount, 2) }} MAD</td>
                        <td>{{ optional($expense->date)->format('M d, Y') }}</td>
                        <td>
                            @if((int) $expense->payer_id === (int) auth()->id())
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('expenses.edit', $expense) }}">
                                        <x-button type="button" variant="secondary">Edit</x-button>
                                    </a>
                                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('Delete this expense?');">
                                        @csrf
                                        @method('DELETE')
                                        <x-button type="submit" variant="danger">Delete</x-button>
                                    </form>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </x-card>
</x-app-layout>
