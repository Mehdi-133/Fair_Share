<aside class="fixed inset-y-0 left-0 z-50 hidden w-72 flex-col border-r border-slate-200 bg-white/95 backdrop-blur lg:flex">
    <div class="border-b border-slate-100 px-5 py-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 via-sky-500 to-blue-600 text-white shadow-md shadow-cyan-200">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 10.5L12 4.5l8.25 6v8.25a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V10.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20.25V13.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v6.75" />
                </svg>
            </span>
            <span>
                <span class="block text-lg font-black tracking-tight text-slate-900">EasyColoc</span>
                <span class="block text-xs font-medium text-slate-500">Shared living workspace</span>
            </span>
        </a>
    </div>

    <nav class="flex-1 space-y-1 px-3 py-4">
        @if(auth()->check() && ((auth()->user()->role ?? null) === 'admin' || auth()->user()->isGlobalAdmin()))
            <div class="px-2 pt-5 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Administration</div>
            <x-sidebar-link :href="route('dashboard.admin')" icon="chart" :active="request()->routeIs('dashboard.admin')">Admin Dashboard</x-sidebar-link>
            <x-sidebar-link :href="route('admin.index')" icon="shield" :active="request()->routeIs('admin.*')">User Management</x-sidebar-link>
        @else
            <p class="px-2 pb-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">Workspace</p>
            <x-sidebar-link :href="route('dashboard.user')" icon="home" :active="request()->routeIs('dashboard.*')">Dashboard</x-sidebar-link>
            <x-sidebar-link :href="route('colocations.index')" icon="users" :active="request()->routeIs('colocations.*')">Colocations</x-sidebar-link>
            <x-sidebar-link :href="route('expenses.index')" icon="receipt" :active="request()->routeIs('expenses.*')">Expenses</x-sidebar-link>
            <x-sidebar-link :href="route('balances.index')" icon="chart" :active="request()->routeIs('balances.*')">Balances</x-sidebar-link>
        @endif
    </nav>

    <div class="mx-4 mb-3 rounded-2xl border border-cyan-100 bg-gradient-to-r from-cyan-50 to-sky-50 p-3 text-xs text-cyan-900">
        <p class="font-semibold">Tip</p>
        <p class="mt-1">Use <span class="font-semibold">Manage Colocation</span> to invite members and organize categories.</p>
    </div>

    @auth
        <form method="POST" action="{{ route('logout') }}" class="border-t border-slate-100 p-3">
            @csrf
            <x-button type="submit" variant="secondary" class="w-full">Logout</x-button>
        </form>
    @endauth
</aside>
