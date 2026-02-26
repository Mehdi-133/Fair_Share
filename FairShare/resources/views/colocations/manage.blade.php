<x-app-layout>
    <section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Manage Colocation</h2>
            <p class="text-slate-500">Manage invitations, members, and colocation actions.</p>
        </div>
        <a href="{{ route('colocations.show', $colocation) }}"><x-button variant="secondary">Back to Details</x-button></a>
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
                <p class="mb-4 text-sm text-slate-600">Cancel this colocation permanently (UI confirmation only).</p>
                <button x-data @click="$dispatch('open-modal', 'cancel-colocation-modal')" class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 hover:bg-rose-100">Cancel Colocation</button>
            </x-card>
        </div>
    </section>

    <x-modal name="cancel-colocation-modal" title="Cancel Colocation">
        <p class="text-sm text-slate-600">Are you sure you want to cancel this colocation?</p>
        <form method="POST" action="{{ route('colocations.cancel', $colocation) }}" class="mt-5 flex justify-end gap-2">
            @csrf
            <x-button type="button" variant="secondary" @click="show = false">Close</x-button>
            <x-button type="submit" variant="danger">Confirm Cancel</x-button>
        </form>
    </x-modal>
</x-app-layout>

