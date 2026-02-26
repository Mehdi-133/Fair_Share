<x-app-layout>
    <section class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">Admin Panel</h2>
            <p class="text-slate-500">Global user management and moderation interface.</p>
        </div>
    </section>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <x-card title="Users"><p class="text-2xl font-bold text-slate-900">{{ $totalUsers ?? 0 }}</p></x-card>
        <x-card title="Colocations"><p class="text-2xl font-bold text-slate-900">{{ $totalColocations ?? 0 }}</p></x-card>
        <x-card title="Expenses"><p class="text-2xl font-bold text-slate-900">{{ $totalExpenses ?? 0 }}</p></x-card>
    </div>

    <x-card title="Users Table">
        <x-table>
            <x-slot name="head">
                <th>Name</th>
                <th>Email</th>
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
                        @if(($u->is_banned ?? false) || ($u->is_baned ?? false))
                            <x-badge type="danger">Banned</x-badge>
                        @else
                            <x-badge type="success">Active</x-badge>
                        @endif
                    </td>
                    <td class="text-right">
                        @if(($u->is_banned ?? false) || ($u->is_baned ?? false))
                            <x-button variant="secondary" class="!px-3 !py-1.5">Unban</x-button>
                        @else
                            <x-button variant="danger" class="!px-3 !py-1.5">Ban</x-button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-sm text-slate-500">No users available.</td>
                </tr>
            @endforelse
        </x-table>
    </x-card>
</x-app-layout>
