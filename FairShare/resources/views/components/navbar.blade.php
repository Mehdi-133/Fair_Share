<header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/85 px-4 py-3 backdrop-blur-md sm:px-6 lg:px-8">
    <div class="flex w-full items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <span class="grid h-10 w-10 place-items-center rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-md shadow-cyan-100">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 10.5L12 4.5l8.25 6v8.25a.75.75 0 01-.75.75H4.5a.75.75 0 01-.75-.75V10.5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20.25V13.5a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v6.75" />
                </svg>
            </span>
            <div>
                <p class="text-[11px] font-bold uppercase tracking-[0.14em] text-cyan-600">EasyColoc</p>
                <h1 class="text-sm font-semibold text-slate-900 sm:text-base">Shared Expenses Dashboard</h1>
            </div>
        </div>

        @auth
            @php
                $repTotal = \App\Http\Controllers\ReputationController::totalForUser((int) auth()->id());
            @endphp
            <div class="relative" x-data="{ open: false }">
                <button
                    type="button"
                    class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-2 py-1.5 shadow-sm transition hover:border-slate-300 hover:bg-slate-50"
                    @click="open = !open"
                    @keydown.escape.window="open = false"
                    :aria-expanded="open.toString()"
                    aria-haspopup="true"
                >
                    <div class="hidden text-right sm:block">
                        <p class="text-xs font-semibold {{ $repTotal >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            Rep: {{ $repTotal >= 0 ? '+' : '' }}{{ $repTotal }}
                        </p>
                        <p class="text-sm font-semibold text-slate-900 leading-tight">{{ auth()->user()->name }}</p>
                        <p class="max-w-48 truncate text-xs text-slate-500">{{ auth()->user()->email }}</p>
                    </div>

                    <div class="h-9 w-9 overflow-hidden rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-sm ring-2 ring-cyan-50">
                        <div class="flex h-full w-full items-center justify-center text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                    </div>
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition.origin.top.right
                    class="absolute right-0 z-50 mt-2 w-56 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg"
                    style="display: none;"
                >
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50">My Profile</a>
                    <a href="{{ route('dashboard.user') }}" class="block px-4 py-2.5 text-sm text-slate-700 transition hover:bg-slate-50">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2.5 text-left text-sm text-rose-600 transition hover:bg-rose-50">Logout</button>
                    </form>
                </div>
            </div>
        @else
            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Login</a>
                <a href="{{ route('register') }}" class="rounded-lg bg-indigo-600 px-3 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">Register</a>
            </div>
        @endauth
    </div>
</header>
