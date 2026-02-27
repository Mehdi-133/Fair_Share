<x-app-layout>
    @php
        $hasColocation = isset($colocation) && $colocation;
    @endphp

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Owner Dashboard</h2>
            <p class="text-slate-500">Manage members, invitations, categories, and colocation settings.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @if($hasColocation)
                    <a href="{{ route('colocations.manage', $colocation) }}"><x-button variant="secondary">Manage Members</x-button></a>
                @else
                    <x-button variant="secondary" disabled>Manage Members</x-button>
                @endif
                <a href="{{ route('expenses.create') }}"><x-button>Add Expense</x-button></a>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <x-card title="Members"><p class="text-2xl font-bold text-slate-900">{{ $membersCount ?? 0 }}</p><p class="text-xs text-slate-500">Active roommates</p></x-card>
        <x-card title="Monthly Expenses"><p class="text-2xl font-bold text-slate-900">{{ number_format((float) ($monthlyExpenses ?? 0), 2) }} MAD</p><p class="text-xs text-slate-500">This month total</p></x-card>
        <x-card title="Pending Invitations"><p class="text-2xl font-bold text-amber-600">{{ $pendingInvitations ?? 0 }}</p><p class="text-xs text-slate-500">Awaiting response</p></x-card>
    </div>

    <x-card title="Member Cards" subtitle="Roles and quick actions">
        @if(($members ?? collect())->isEmpty())
            <x-empty-state title="No members" description="No members found in this colocation." />
        @else
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4">
                @foreach($members as $member)
                    <article class="rounded-2xl border border-slate-200 p-4">
                        <div class="mb-3 flex items-center justify-between">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-100 text-sm font-bold text-indigo-700">{{ strtoupper(substr($member->name, 0, 1)) }}</div>
                            <x-badge :type="$member->pivot->role === 'owner' ? 'primary' : 'neutral'">{{ ucfirst($member->pivot->role) }}</x-badge>
                        </div>
                        <h3 class="font-semibold text-slate-900">{{ $member->name }}</h3>
                        <p class="text-xs text-slate-500">{{ $member->email }}</p>
                        <div class="mt-4">
                            <x-button variant="secondary" class="w-full" :disabled="$member->pivot->role === 'owner'">Remove Member</x-button>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </x-card>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
        <x-card title="Owner Actions">
            <div class="space-y-3">
                @if($hasColocation)
                    <a href="{{ route('colocations.manage', $colocation) }}#invite-member"><x-button class="w-full">Invite Member</x-button></a>
                @else
                    <x-button class="w-full" disabled>Invite Member</x-button>
                @endif
                <x-button variant="secondary" class="w-full" disabled>Manage Categories</x-button>
                <button x-data @click="$dispatch('open-modal', 'cancel-colocation-modal')" class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100">Cancel Colocation</button>
            </div>
        </x-card>

        <x-card title="Expense Statistics">
            <ul class="space-y-3 text-sm">
                <li class="flex justify-between"><span>This Month</span><strong>{{ number_format((float) ($monthlyExpenses ?? 0), 2) }} MAD</strong></li>
                <li class="flex justify-between"><span>Total Members</span><strong>{{ $membersCount ?? 0 }}</strong></li>
                <li class="flex justify-between"><span>Pending Invites</span><strong>{{ $pendingInvitations ?? 0 }}</strong></li>
            </ul>
        </x-card>
    </div>

    <x-modal name="cancel-colocation-modal" title="Cancel Colocation">
        <p class="text-sm text-slate-600">This is a destructive action. Are you sure you want to cancel this colocation?</p>
        <div class="mt-5 flex justify-end gap-2">
            <x-button variant="secondary" @click="show = false">Keep Colocation</x-button>
            <x-button variant="danger" disabled>Yes, Cancel</x-button>
        </div>
    </x-modal>
</x-app-layout>
