<x-app-layout>
    @php
        $categories = ($colocation ?? null)?->categories ?? collect();
    @endphp

    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Add Expense</h2>
            <p class="text-slate-500">Record a shared expense for your colocation.</p>
        </div>

        <x-card>
            <form class="space-y-5">
                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" placeholder="e.g. Electricity Bill" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea rows="3" placeholder="Optional details" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20"></textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Amount</label>
                        <input type="number" step="0.01" min="0" placeholder="0.00" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Date</label>
                        <input type="date" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Category</label>
                    <select class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option>Select category</option>
                        @foreach($categories as $category)
                            <option>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('expenses.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
                    <x-button type="submit">Save Expense</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
