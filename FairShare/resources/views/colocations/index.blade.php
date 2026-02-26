<x-app-layout>
    <div class="flex justify-between items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Your Colocations</h1>
            <p class="text-slate-500">Manage your shared spaces or join a new one.</p>
        </div>
        <x-button>+ Create New Space</x-button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="group relative bg-white border-2 border-indigo-500 rounded-2xl p-6 shadow-xl shadow-indigo-100 ring-4 ring-indigo-50">
            <div class="absolute top-4 right-4"><x-badge type="primary">Active</x-badge></div>
            <h3 class="text-xl font-bold text-slate-900 mb-1">Casa Blanca Loft</h3>
            <p class="text-sm text-slate-500 mb-6">4 Members • Casablanca, MA</p>
            <div class="flex -space-x-2 mb-6">
                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=A">
                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=B">
                <img class="w-8 h-8 rounded-full border-2 border-white" src="https://ui-avatars.com/api/?name=C">
                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-100 flex items-center justify-center text-[10px] font-bold">+1</div>
            </div>
            <x-button variant="primary" class="w-full">Open Dashboard</x-button>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-slate-300 transition-all group">
            <h3 class="text-xl font-bold text-slate-900 mb-1">Summer Beach House</h3>
            <p class="text-sm text-slate-500 mb-6">2 Members • Tangier, MA</p>
            <div class="mb-6 h-8 flex items-center text-xs text-slate-400 italic">Archived on Sept 2025</div>
            <x-button variant="secondary" class="w-full">View History</x-button>
        </div>
    </div>
</x-app-layout>