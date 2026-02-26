<div class="bg-indigo-900 rounded-2xl p-6 text-white mb-8 flex flex-col md:flex-row justify-between items-center gap-6">
    <div>
        <h2 class="text-xl font-bold">Colocation Management</h2>
        <p class="text-indigo-200 text-sm">You are the administrator of "Casa Blanca Loft".</p>
    </div>
    <div class="flex gap-3">
        <x-button variant="secondary" class="bg-white/10 border-white/20 text-white hover:bg-white/20">
            Invite Roommate
        </x-button>
        <x-button variant="danger">
            Dissolve Coloc
        </x-button>
    </div>
</div>

<x-card title="Expense Categories" subtitle="Customize how you track spending">
    <div class="flex flex-wrap gap-2">
        @foreach(['Rent', 'Groceries', 'Internet', 'Electricity', 'Water', 'Repairs'] as $cat)
            <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 text-sm font-medium">
                {{ $cat }}
                <button class="text-slate-400 hover:text-rose-500">×</button>
            </div>
        @endforeach
        <button class="px-4 py-2 rounded-lg border border-dashed border-slate-300 text-slate-500 text-sm hover:border-indigo-500 hover:text-indigo-500 transition-colors">
            + Add New
        </button>
    </div>
</x-card>