<x-app-layout>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Welcome back, Alex 👋</h1>
            <p class="text-slate-500">Here is what's happening in <span class="font-semibold">Casa Blanca Loft</span>.</p>
        </div>
        <div class="flex gap-3">
            <x-button variant="secondary">Export PDF</x-button>
            <x-button x-data @click="$dispatch('open-modal', 'add-expense')">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Expense
            </x-button>
        </div>
    </div>

    @include('components.partials.balance-summary')

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <x-card title="Who owes who" subtitle="Current settlement flow">
                @include('components.partials.debt-graph')
            </x-card>
        </div>

        <div>
            <x-card title="Roommates" subtitle="4 Active members">
                @include('components.partials.member-list')
            </x-card>
        </div>
    </div>

    <x-card title="Recent Expenses">
        <x-table>
            <x-slot name="head">
                <th>Description</th>
                <th>Category</th>
                <th>Payer</th>
                <th>Amount</th>
                <th>Date</th>
            </x-slot>
            <tr>
                <td class="font-medium">Fiber Internet Optic</td>
                <td><x-badge>Utilities</x-badge></td>
                <td>Me</td>
                <td class="font-bold text-slate-900">450.00 MAD</td>
                <td class="text-slate-500 text-sm">Oct 12, 2026</td>
            </tr>
            <tr>
                <td class="font-medium">Cleaning Supplies</td>
                <td><x-badge>House</x-badge></td>
                <td>Sarah</td>
                <td class="font-bold text-slate-900">120.00 MAD</td>
                <td class="text-slate-500 text-sm">Oct 10, 2026</td>
            </tr>
        </x-table>
    </x-card>

    <x-modal name="add-expense" title="Add New Expense">
        <form class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700">Description</label>
                <input type="text" placeholder="e.g. Weekly Groceries" class="mt-1 block w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Amount (MAD)</label>
                    <input type="number" value="0.00" class="mt-1 block w-full rounded-xl border-slate-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Category</label>
                    <select class="mt-1 block w-full rounded-xl border-slate-200">
                        <option>Food</option>
                        <option>Rent</option>
                        <option>Utilities</option>
                    </select>
                </div>
            </div>
            <div class="pt-4 flex justify-end gap-3">
                <x-button variant="secondary" @click="show = false">Cancel</x-button>
                <x-button>Save Expense</x-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>