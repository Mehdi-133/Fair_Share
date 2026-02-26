<x-app-layout>
    <div class="mx-auto max-w-2xl">
        <div class="mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Create Colocation</h2>
            <p class="text-slate-500">Create your shared space and invite roommates.</p>
        </div>

        <x-card>
            <form method="POST" action="{{ route('colocations.store') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <x-alert type="danger">{{ $errors->first() }}</x-alert>
                @endif

                <div>
                    <label for="name" class="mb-1.5 block text-sm font-semibold text-slate-700">Colocation Name</label>
                    <input id="name" name="name" value="{{ old('name') }}" type="text" maxlength="30" placeholder="e.g. Palm Residence" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                </div>

                <div>
                    <label for="description" class="mb-1.5 block text-sm font-semibold text-slate-700">Description</label>
                    <textarea id="description" name="description" maxlength="100" rows="4" placeholder="Small note about your shared accommodation" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">{{ old('description') }}</textarea>
                </div>

                <div class="rounded-2xl border border-indigo-100 bg-indigo-50 p-4 text-sm text-indigo-800">
                    After creation, you can invite members by email and start adding expenses.
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('colocations.index') }}"><x-button type="button" variant="secondary">Cancel</x-button></a>
                    <x-button type="submit">Create Colocation</x-button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>
