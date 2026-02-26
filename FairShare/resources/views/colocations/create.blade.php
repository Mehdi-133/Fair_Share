<x-app-layout>
    <div class="max-w-2xl mx-auto py-12">
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-bold text-slate-900">Start a new Colocation</h1>
            <p class="text-slate-500 mt-2">Create a digital space for your roommates to manage finances.</p>
        </div>

        <x-card>
            <form class="space-y-6">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Colocation Name</label>
                        <input type="text" placeholder="e.g. Sunny Apartments 402" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Currency</label>
                        <select class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500/20 outline-none">
                            <option>MAD (Moroccan Dirham)</option>
                            <option>EUR (Euro)</option>
                            <option>USD (US Dollar)</option>
                        </select>
                    </div>

                    <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100 flex gap-4">
                        <div class="bg-white p-2 rounded-lg shrink-0 h-fit">🏙️</div>
                        <div>
                            <h4 class="text-sm font-bold text-indigo-900">Invite Tip</h4>
                            <p class="text-xs text-indigo-700 leading-relaxed mt-1">
                                After creation, you'll get a unique invite link to share with your roommates via WhatsApp or Email.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="/colocations" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                    <x-button class="px-10">Create Space</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>