<x-app-layout>
    @php
        $categories = ($colocation ?? null)?->categories ?? collect();
    @endphp

    <div class="mx-auto max-w-2xl space-y-6">
        <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
            <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-cyan-200/35 blur-2xl"></div>
            <div class="relative">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Edit Expense</h2>
            <p class="text-slate-500">Update an existing shared expense.</p>
            </div>
        </section>

        <x-card>
            <form method="POST" action="{{ route('expenses.update', $expense) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Title</label>
                    <input type="text" name="title" value="{{ old('title', $expense->title) }}" placeholder="e.g. Electricity Bill" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea name="description" rows="3" placeholder="Expense details" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>{{ old('description', $expense->description) }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Amount</label>
                        <input type="number" name="amount" value="{{ old('amount', $expense->amount) }}" step="0.01" min="0.01" placeholder="0.00" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-semibold text-slate-700">Date</label>
                        <input type="date" name="date" value="{{ old('date', optional($expense->date)->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                    </div>
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Category</label>
                    <select name="category_id" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id', $expense->category_id) === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('expenses.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
                    <x-button type="submit">Update Expense</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
