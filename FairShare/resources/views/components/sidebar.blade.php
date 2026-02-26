<aside class="hidden w-64 shrink-0 flex-col border-r border-slate-200 bg-white lg:flex">
    <div class="flex h-16 items-center px-6">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-600 text-lg font-bold text-white">E</span>
            <span class="text-lg font-bold tracking-tight text-slate-900">EasyColoc</span>
        </a>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        <x-sidebar-link :href="route('dashboard.user')" icon="home" :active="request()->routeIs('dashboard.*')">Dashboard</x-sidebar-link>
        <x-sidebar-link :href="route('colocations.index')" icon="users" :active="request()->routeIs('colocations.*')">Colocations</x-sidebar-link>
        <x-sidebar-link :href="route('expenses.index')" icon="receipt" :active="request()->routeIs('expenses.*')">Expenses</x-sidebar-link>
        <x-sidebar-link :href="route('balances.index')" icon="chart" :active="request()->routeIs('balances.*')">Balances</x-sidebar-link>

        <div class="pt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-400">Administration</div>
        <x-sidebar-link :href="route('dashboard.admin')" icon="chart" :active="request()->routeIs('dashboard.admin')">Admin Dashboard</x-sidebar-link>
        <x-sidebar-link :href="route('admin.index')" icon="shield" :active="request()->routeIs('admin.*')">User Management</x-sidebar-link>
    </nav>

    @auth
        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-200 p-3">
            @csrf
            <x-button type="submit" variant="secondary" class="w-full">Logout</x-button>
        </form>
    @endauth
</aside>
