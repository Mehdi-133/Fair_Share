<header class="sticky top-0 z-40 flex h-16 shrink-0 items-center border-b border-slate-200 bg-white/80 px-4 backdrop-blur-md sm:px-6 lg:px-8">
    <div class="flex w-full items-center justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">EasyColoc</p>
            <h1 class="text-sm font-semibold text-slate-900">Shared Expenses Dashboard</h1>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden text-right sm:block">
                <p class="text-sm font-semibold text-slate-900">{{ auth()->user()->name ?? 'Guest' }}</p>
                <p class="text-xs text-slate-500">{{ auth()->user()->email ?? 'Not signed in' }}</p>
            </div>

            <div class="h-9 w-9 overflow-hidden rounded-xl bg-indigo-600 text-white">
                <div class="flex h-full w-full items-center justify-center text-sm font-bold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
                </div>
            </div>
        </div>
    </div>
</header>
