<x-app-layout>
    @php
        $users = $colocation->users ?? collect();
        $expenses = $colocation->expenses ?? collect();
        $isOwner = isset($colocation) ? $colocation->isOwner(auth()->id()) : false;
        $isCancelled = ($colocation->status ?? null) === 'cancelled';
    @endphp

    <section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">{{ $colocation->name ?? 'Colocation Details' }}</h2>
            <p class="text-slate-500">{{ $colocation->description ?? 'Manage members and expenses.' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            @if(!$isCancelled)
                <select class="rounded-xl border-slate-200 text-sm">
                    <option>Monthly Filter (UI)</option>
                    <option>January</option>
                    <option>February</option>
                    <option>March</option>
                </select>
                <a href="{{ route('expenses.create') }}"><x-button>Add Expense</x-button></a>
                @if($isOwner)
                    <a href="{{ route('colocations.manage', $colocation) }}#invite-member"><x-button variant="outline">Invite Member</x-button></a>
                @endif
            @else
                <x-badge type="warning">Read-only (Cancelled)</x-badge>
            @endif
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
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
        </x-card>
    </div>
</x-app-layout>
