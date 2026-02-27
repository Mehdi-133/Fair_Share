<x-app-layout>
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -left-20 -top-20 h-48 w-48 rounded-full bg-amber-200/30 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Colocations</h2>
            <p class="text-slate-500">View and manage your shared accommodations.</p>
            </div>
            @if(($canCreateColocation ?? true))
                <a href="{{ route('colocations.create') }}"><x-button>Create Colocation</x-button></a>
            @else
                <div class="text-left md:text-right">
                    <x-button disabled>Create Colocation</x-button>
                    <p class="mt-1 text-xs text-amber-700">Cancel your active colocation to create a new one.</p>
                </div>
            @endif
        </div>
    </section>

    @php
        $items = $colocations ?? collect();
        $canCreateColocation = $canCreateColocation ?? true;
    @endphp

    @if($items->isEmpty())
        <x-empty-state title="No colocation yet" description="Create your first colocation to start managing shared expenses.">
            @if($canCreateColocation)
                <a href="{{ route('colocations.create') }}"><x-button>Create Colocation</x-button></a>
            @else
                <x-button disabled>Create Colocation</x-button>
            @endif
        </x-empty-state>
    @else
        <div class="grid grid-cols-1 items-stretch gap-6 md:grid-cols-2 xl:grid-cols-3">
            @foreach($items as $colocation)
                <x-card class="h-full">
                    @php
                        $canManage = $colocation->status !== 'cancelled' && $colocation->isOwner(auth()->id());
                    @endphp
                    <div class="flex h-full flex-col">
                        <div class="mb-4 flex items-start justify-between gap-3">
                            <div class="min-h-16">
                            <h3 class="text-lg font-bold text-slate-900">{{ $colocation->name }}</h3>
                            <p class="text-sm text-slate-500">{{ \Illuminate\Support\Str::words($colocation->description ?: 'No description', 10) }}</p>
                            </div>
                            <x-badge :type="$colocation->status === 'active' ? 'success' : 'warning'">{{ ucfirst($colocation->status) }}</x-badge>
                        </div>

                        <div class="mb-4 text-xs text-slate-500">{{ $colocation->users->count() }} members</div>

                        @if($colocation->status !== 'cancelled')
                            <div class="mt-auto grid grid-cols-1 gap-2 sm:grid-cols-2">
                                <a href="{{ route('colocations.show', $colocation) }}"><x-button class="w-full">Open</x-button></a>
                                @if($canManage)
                                    <a href="{{ route('colocations.manage', $colocation) }}"><x-button variant="secondary" class="w-full">Manage</x-button></a>
                                @else
                                    <x-button variant="secondary" class="w-full" disabled>Manage</x-button>
                                @endif
                            </div>
                        @else
                            <div class="mt-auto">
                                <a href="{{ route('colocations.show', $colocation) }}"><x-button variant="secondary" class="w-full">Expense Details</x-button></a>
                            </div>
                        @endif
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
</x-app-layout>
