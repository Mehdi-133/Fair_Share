<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Manage Colocation</h1>
        <p class="text-slate-500">Update settings, roles, and membership for <span class="text-indigo-600 font-semibold">Casa Blanca Loft</span>.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            <x-card title="General Information">
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Display Name</label>
                            <input type="text" value="Casa Blanca Loft" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1">Monthly Budget Goal</label>
                            <input type="text" value="12,000 MAD" class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        </div>
                    </div>
                    <div class="pt-2">
                        <x-button>Save Changes</x-button>
                    </div>
                </form>
            </x-card>

            <x-card title="Member Permissions">
                <div class="space-y-4">
                    @foreach(['Sarah Miller', 'Alex Johnson', 'Marc Dupont'] as $index => $name)
                    <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{$name}}" class="w-10 h-10 rounded-full">
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $name }}</p>
                                <p class="text-xs text-slate-400 italic">Joined 4 months ago</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <select class="text-xs font-bold bg-slate-100 border-none rounded-lg py-1 px-3 outline-none focus