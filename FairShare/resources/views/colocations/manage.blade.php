<x-app-layout>
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -left-16 -top-16 h-40 w-40 rounded-full bg-cyan-200/35 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Manage Colocation</h2>
            <p class="text-slate-500">Manage invitations, members, and colocation actions.</p>
            </div>
            <a href="{{ route('colocations.show', $colocation) }}"><x-button variant="secondary">Back to Details</x-button></a>
        </div>
    </section>

    <section class="space-y-6">
        <div class="grid grid-cols-1 items-stretch gap-6 lg:grid-cols-2">
            <x-card id="invite-member" title="Invite Member" class="h-full">
                <form method="POST" action="{{ route('invitations.send', $colocation) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Email</label>
                        <input name="email" type="email" placeholder="roommate@email.com" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                    </div>
                    <x-button type="submit">Send Invitation</x-button>
                </form>
            </x-card>

            <x-card title="Pending Invitations" class="h-full">
                @if(($colocation->invitations ?? collect())->isEmpty())
                    <x-empty-state title="No invitations" description="No pending invitations at this time." />
                @else
                    <ul class="space-y-3">
                        @foreach($colocation->invitations as $invitation)
                            <li class="flex items-center justify-between rounded-xl border border-slate-100 p-3">
                                <span class="text-sm text-slate-700">{{ $invitation->email }}</span>
                                <x-badge :type="$invitation->status === 'pending' ? 'warning' : 'neutral'">{{ ucfirst($invitation->status) }}</x-badge>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </x-card>
        </div>

        <x-card title="Categories Management" subtitle="Create, rename, or delete expense categories">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <form method="POST" action="{{ route('colocations.categories.store', $colocation) }}" class="space-y-3">
                        @csrf
                        <label class="mb-1 block text-sm font-medium text-slate-700">New Category</label>
                        <div class="flex gap-2">
                            <input name="name" type="text" maxlength="50" placeholder="e.g. Groceries" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                            <x-button type="submit">Add</x-button>
                        </div>
                    </form>
                </div>

                <div>
                    @if(($colocation->categories ?? collect())->isEmpty())
                        <x-empty-state title="No categories" description="Create your first category to organize expenses." />
                    @else
                        <ul class="space-y-3">
                            @foreach($colocation->categories->sortBy('name') as $category)
                                <li class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                                        <form method="POST" action="{{ route('colocations.categories.update', [$colocation, $category]) }}" class="flex flex-1 items-center gap-2">
                                            @csrf
                                            @method('PUT')
                                            <input name="name" type="text" value="{{ $category->name }}" maxlength="50" class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20" required>
                                            <x-button type="submit" variant="secondary" class="!px-3 !py-2">Rename</x-button>
                                        </form>

                                        <form method="POST" action="{{ route('colocations.categories.destroy', [$colocation, $category]) }}" onsubmit="return confirm('Delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <x-button type="submit" variant="danger" class="!px-3 !py-2">Delete</x-button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </x-card>

        <div class="grid grid-cols-1 items-start gap-6 xl:grid-cols-3">
            <x-card title="Members Management" class="xl:col-span-2">
                <x-table>
                    <x-slot name="head">
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th class="text-right">Actions</th>
                    </x-slot>
                    @foreach($colocation->users as $member)
                        <tr>
                            <td class="font-semibold text-slate-900">{{ $member->name }}</td>
                            <td>{{ $member->email }}</td>
                            <td><x-badge :type="$member->pivot->role === 'owner' ? 'primary' : 'neutral'">{{ ucfirst($member->pivot->role) }}</x-badge></td>
                            <td class="text-right">
                                <x-button variant="danger" class="!px-3 !py-1.5" :disabled="$member->pivot->role === 'owner'">Remove</x-button>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            </x-card>

            <x-card title="Danger Zone">
                <div class="rounded-2xl border border-rose-200 bg-gradient-to-br from-rose-50 to-white p-4">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 rounded-xl bg-rose-100 p-2 text-rose-700">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.08 14A2 2 0 003.92 21h16.16a2 2 0 001.71-3.14l-8.08-14a2 2 0 00-3.42 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-rose-800">Cancel this colocation permanently</p>
                            <p class="mt-1 text-sm text-rose-700/90">This action makes the colocation read-only for everyone.</p>
                        </div>
                    </div>

                    <div class="mt-4 rounded-xl border border-rose-100 bg-white/80 px-3 py-2 text-xs text-rose-800">
                        Allowed only when there is no outstanding debt, or no other active members remain.
                    </div>

                    <button x-data @click="$dispatch('open-modal', 'cancel-colocation-modal')" class="mt-4 w-full rounded-xl border border-rose-300 bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm shadow-rose-200 transition hover:bg-rose-700">
                        Cancel Colocation
                    </button>
                </div>
            </x-card>
        </div>
    </section>

    <x-modal name="cancel-colocation-modal" title="Cancel Colocation">
        <p class="text-sm text-slate-600">This action is irreversible. Are you sure you want to cancel this colocation?</p>
        <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" class="mt-5 flex justify-end gap-2">
            @csrf
            <x-button type="button" variant="secondary" @click="show = false">Close</x-button>
            <x-button type="submit" variant="danger">Confirm Cancel</x-button>
        </form>
    </x-modal>
</x-app-layout>

