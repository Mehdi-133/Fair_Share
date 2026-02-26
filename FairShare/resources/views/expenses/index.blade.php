<x-app-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Expense History</h1>
            <p class="text-slate-500">Track every penny spent in the household.</p>
        </div>
        <div class="flex gap-2">
            <select class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500">
                <option>All Categories</option>
                <option>Rent</option>
                <option>Utilities</option>
            </select>
            <x-button>+ Add Expense</x-button>
        </div>
    </div>

    <x-card>
        <x-table>
            <x-slot name="head">
                <th>Status</th>
                <th>Expense</th>
                <th>Paid By</th>
                <th>Splitting</th>
                <th>Amount</th>
                <th>Date</th>
                <th></th>
            </x-slot>
            
            @foreach([
                ['title' => 'Carrefour Weekly Shop', 'user' => 'Sarah', 'amount' => '840.00', 'status' => 'success', 'split' => 'Equal'],
                ['title' => 'Electricity Bill', 'user' => 'Alex', 'amount' => '1,200.00', 'status' => 'warning', 'split' => 'Percentage'],
                ['title' => 'Internet Subscription', 'user' => 'Marc', 'amount' => '350.00', 'status' => 'success', 'split' => 'Equal']
            ] as $exp)
            <tr class="group hover:bg-slate-50/80 transition-colors">
                <td>
                    <div class="h-2 w-2 rounded-full {{ $exp['status'] === 'success' ? 'bg-emerald-500' : 'bg-amber-400 animate-pulse' }}"></div>
                </td>
                <td class="font-semibold text-slate-900">{{ $exp['title'] }}</td>
                <td>
                    <div class="flex items-center gap-2">
                        <img src="https://ui-avatars.com/api/?name={{$exp['user']}}" class="w-6 h-6 rounded-full">
                        <span>{{ $exp['user'] }}</span>
                    </div>
                </td>
                <td><x-badge type="primary">{{ $exp['split'] }}</x-badge></td>
                <td class="font-bold text-slate-900">{{ $exp['amount'] }} MAD</td>
                <td class="text-slate-400 text-xs">Today, 14:20</td>
                <td class="text-right">
                    <button class="text-slate-300 hover:text-indigo-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/></svg>
                    </button>
                </td>
            </tr>
            @endforeach
        </x-table>
    </x-card>
</x-app-layout>