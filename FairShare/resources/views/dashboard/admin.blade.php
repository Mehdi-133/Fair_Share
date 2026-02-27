<x-app-layout>
    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Global Admin Dashboard</h2>
            <p class="text-slate-500">Platform metrics and moderation controls.</p>
            </div>
            <a href="{{ route('admin.index') }}"><x-button>Open Admin Panel</x-button></a>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">
        <x-card title="Total Users"><p class="text-3xl font-bold text-slate-900">{{ $totalUsers ?? 0 }}</p></x-card>
        <x-card title="Total Colocations"><p class="text-3xl font-bold text-slate-900">{{ $totalColocations ?? 0 }}</p></x-card>
        <x-card title="Total Expenses"><p class="text-3xl font-bold text-slate-900">{{ $totalExpenses ?? 0 }}</p></x-card>
        <x-card title="Banned Users"><p class="text-3xl font-bold text-rose-600">{{ ($bannedUsers ?? collect())->count() }}</p></x-card>
    </div>

    <x-card title="Banned Users List">
        @if(($bannedUsers ?? collect())->isEmpty())
            <x-empty-state title="No banned users" description="No users are currently banned." />
        @else
            <x-table>
                <x-slot name="head">
                    <th>User</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </x-slot>

                @foreach($bannedUsers as $user)
                    <tr>
                        <td class="font-semibold text-slate-900">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><x-badge type="danger">Banned</x-badge></td>
                        <td class="text-right"><x-button variant="secondary" class="!px-3 !py-1.5">Unban</x-button></td>
                    </tr>
                @endforeach
            </x-table>
        @endif
    </x-card>
</x-app-layout>
