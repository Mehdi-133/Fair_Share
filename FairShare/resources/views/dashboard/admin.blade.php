<x-app-layout>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900">System Overview</h1>
        <p class="text-slate-500">Global statistics and platform health</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-card title="Total Users">
            <span class="text-3xl font-bold">12.4k</span>
            <span class="ml-2 text-sm text-emerald-500 font-medium">+14%</span>
        </x-card>
        <x-card title="Active Colocs">
            <span class="text-3xl font-bold">3,102</span>
        </x-card>
        <x-card title="Transactions">
            <span class="text-3xl font-bold">45.2M MAD</span>
        </x-card>
        <x-card title="Server Status">
            <x-badge type="success">Operational</x-badge>
        </span class="block mt-2 text-xs text-slate-400">Uptime: 99.9%</span>
        </x-card>
    </div>

    <x-card title="Platform Moderation">
        <x-table>
            <x-slot name="head">
                <th>User</th>
                <th>Status</th>
                <th>Reports</th>
                <th>Joined</th>
                <th class="text-right">Actions</th>
            </x-slot>
            <tr>
                <td class="font-medium">john_doe@gmail.com</td>
                <td><x-badge type="success">Active</x-badge></td>
                <td>0</td>
                <td>Jan 2026</td>
                <td class="text-right">
                    <x-button variant="danger" class="!py-1 !px-3 text-xs">Ban</x-button>
                </td>
            </tr>
            <tr class="opacity-50 grayscale">
                <td class="font-medium">scammer_99@hotmail.com</td>
                <td><x-badge type="danger">Banned</x-badge></td>
                <td>14</td>
                <td>Feb 2026</td>
                <td class="text-right">
                    <x-button variant="secondary" class="!py-1 !px-3 text-xs">Unban</x-button>
                </td>
            </tr>
        </x-table>
    </x-card>
</x-app-layout>