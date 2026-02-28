<x-app-layout>
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Admin Panel</h2>
            <p class="text-slate-500">Global user management and moderation interface.</p>
            </div>
        </div>
    </section>

    @if(session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if($errors->any())
        <x-alert type="danger">{{ $errors->first() }}</x-alert>
    @endif

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <x-card title="Users"><p class="text-2xl font-bold text-slate-900">{{ $totalUsers ?? 0 }}</p></x-card>
        <x-card title="Colocations"><p class="text-2xl font-bold text-slate-900">{{ $totalColocations ?? 0 }}</p></x-card>
        <x-card title="Expenses"><p class="text-2xl font-bold text-slate-900">{{ $totalExpenses ?? 0 }}</p></x-card>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-card title="Active Colocations"><p class="text-2xl font-bold text-emerald-600">{{ $activeColocations ?? 0 }}</p></x-card>
        <x-card title="Pending Invitations"><p class="text-2xl font-bold text-amber-600">{{ $pendingInvitations ?? 0 }}</p></x-card>
        <x-card title="Accepted Invitations"><p class="text-2xl font-bold text-slate-900">{{ $acceptedInvitations ?? 0 }}</p></x-card>
        <x-card title="Refused Invitations"><p class="text-2xl font-bold text-rose-600">{{ $refusedInvitations ?? 0 }}</p></x-card>
    </div>

    <div class="grid grid-cols-1 gap-6 xl:grid-cols-3">
        <x-card title="Expense Overview">
            <div class="space-y-3 text-sm text-slate-700">
                <p class="flex items-center justify-between"><span>This month</span><span class="font-semibold text-slate-900">{{ number_format((float) ($expensesThisMonth ?? 0), 2) }}</span></p>
                <p class="flex items-center justify-between"><span>Average</span><span class="font-semibold text-slate-900">{{ number_format((float) ($avgExpenseAmount ?? 0), 2) }}</span></p>
                <p class="flex items-center justify-between"><span>Largest</span><span class="font-semibold text-slate-900">{{ number_format((float) ($biggestExpense ?? 0), 2) }}</span></p>
            </div>
        </x-card>

        <x-card title="Community Growth">
            <div class="space-y-3 text-sm text-slate-700">
                <p class="flex items-center justify-between"><span>New users this month</span><span class="font-semibold text-slate-900">{{ $usersThisMonth ?? 0 }}</span></p>
                <p class="flex items-center justify-between"><span>Active memberships</span><span class="font-semibold text-slate-900">{{ $activeMemberships ?? 0 }}</span></p>
                <p class="flex items-center justify-between"><span>Owners</span><span class="font-semibold text-slate-900">{{ $totalOwners ?? 0 }}</span></p>
            </div>
        </x-card>

        <x-card title="Top Spender">
            @php $topSpender = ($topSpenders ?? collect())->first(); @endphp
            @if($topSpender)
                <p class="text-sm text-slate-500">Highest total paid</p>
                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $topSpender->name }}</p>
                <p class="text-sm text-slate-500">{{ $topSpender->email }}</p>
                <p class="mt-3 text-2xl font-bold text-slate-900">{{ number_format((float) ($topSpender->total_paid ?? 0), 2) }}</p>
            @else
                <x-empty-state title="No spending data" description="No expenses available yet." />
            @endif
        </x-card>
    </div>

    <x-card title="Users Table">
        <x-table>
            <x-slot name="head">
                <th>Name</th>
                <th>Email</th>
                <th>Reputation</th>
                <th>Status</th>
                <th class="text-right">Actions</th>
            </x-slot>

            @php
                $rows = $users ?? collect();
                $rows = method_exists($rows, 'items') ? collect($rows->items()) : $rows;
            @endphp

            @forelse($rows as $u)
                <tr>
                    <td class="font-semibold text-slate-900">{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td>
                        @php $rep = (int) ($u->reputation_total ?? 0); @endphp
                        <span class="font-semibold {{ $rep >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ $rep >= 0 ? '+' : '' }}{{ $rep }}</span>
                    </td>
                    <td>
                        @if(($u->is_banned ?? false) || ($u->is_baned ?? false))
                            <x-badge type="danger">Banned</x-badge>
                        @else
                            <x-badge type="success">Active</x-badge>
                        @endif
                    </td>
                    <td class="text-right">
                        @if(($u->is_banned ?? false) || ($u->is_baned ?? false))
                            <form method="POST" action="{{ route('admin.users.unban', $u) }}" class="inline-block">
                                @csrf
                                <x-button type="submit" variant="secondary" class="!px-3 !py-1.5">Unban</x-button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.users.ban', $u) }}" class="inline-block">
                                @csrf
                                <x-button type="submit" variant="danger" class="!px-3 !py-1.5">Ban</x-button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-sm text-slate-500">No users available.</td>
                </tr>
            @endforelse
        </x-table>
    </x-card>
</x-app-layout>
