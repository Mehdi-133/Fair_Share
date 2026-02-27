<x-app-layout>
    @php
        $currentUserId = (int) ($currentUserId ?? auth()->id());
        $suggestions = $settlementSuggestions ?? [];
        $balances = $memberBalances ?? [];
    @endphp

    @if(!empty($noColocation))
        <x-alert type="warning">You are not in an active colocation yet, so no balances can be calculated.</x-alert>
    @endif

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/40 blur-2xl"></div>
        <div class="absolute -bottom-20 -left-16 h-48 w-48 rounded-full bg-amber-200/40 blur-2xl"></div>
        <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-3xl font-black tracking-tight text-slate-900">Balances and Settlements</h2>
                <p class="mt-1 text-slate-600">Live split for your colocation. Edit any expense and this updates instantly.</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white/80 px-4 py-3 text-sm shadow-sm backdrop-blur">
                <span class="text-slate-500">Active suggestions</span>
                <p class="text-xl font-extrabold text-slate-900">{{ count($suggestions) }}</p>
            </div>
        </div>
    </section>

    <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-3">
        <div class="rounded-2xl border border-cyan-100 bg-cyan-50/80 p-5 shadow-sm transition hover:-translate-y-0.5">
            <p class="text-sm font-semibold uppercase tracking-wide text-cyan-700">Total Paid</p>
            <p class="mt-2 text-3xl font-black text-cyan-900">{{ number_format((float) ($totalPaid ?? 0), 2) }} MAD</p>
        </div>
        <div class="rounded-2xl border border-amber-100 bg-amber-50/80 p-5 shadow-sm transition hover:-translate-y-0.5">
            <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Individual Share</p>
            <p class="mt-2 text-3xl font-black text-amber-900">{{ number_format((float) ($individualShare ?? 0), 2) }} MAD</p>
        </div>
        <div class="rounded-2xl border p-5 shadow-sm transition hover:-translate-y-0.5 {{ ((float) ($currentBalance ?? 0)) >= 0 ? 'border-emerald-100 bg-emerald-50/80' : 'border-rose-100 bg-rose-50/80' }}">
            <p class="text-sm font-semibold uppercase tracking-wide {{ ((float) ($currentBalance ?? 0)) >= 0 ? 'text-emerald-700' : 'text-rose-700' }}">Your Balance</p>
            <p class="mt-2 text-3xl font-black {{ ((float) ($currentBalance ?? 0)) >= 0 ? 'text-emerald-900' : 'text-rose-900' }}">{{ number_format((float) ($currentBalance ?? 0), 2) }} MAD</p>
        </div>
    </div>

    <x-card title="Who Owes Who" subtitle="Filter to focus on your own settlements">
        <div class="mb-4 flex flex-wrap gap-2">
            <button type="button" data-filter="all" class="settle-filter rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700">All</button>
            <button type="button" data-filter="you-owe" class="settle-filter rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700">You Owe</button>
            <button type="button" data-filter="owes-you" class="settle-filter rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-sm font-semibold text-slate-700">Owes You</button>
        </div>

        @if(empty($suggestions))
            <x-empty-state title="No settlements needed" description="Everyone is balanced for now." />
        @else
            <ul id="settlement-list" class="space-y-3">
                @foreach($suggestions as $item)
                    @php
                        $type = 'neutral';
                        if ((int) $item['from']->id === $currentUserId) {
                            $type = 'you-owe';
                        } elseif ((int) $item['to']->id === $currentUserId) {
                            $type = 'owes-you';
                        }
                    @endphp
                    <li data-type="{{ $type }}" class="settlement-item flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 p-3 transition hover:border-slate-200 hover:bg-white">
                        <span class="text-sm text-slate-700">
                            <strong class="text-slate-900">{{ $item['from']->name }}</strong>
                            owes
                            <strong class="text-slate-900">{{ $item['to']->name }}</strong>
                        </span>
                        <strong class="text-slate-900">{{ number_format((float) $item['amount'], 2) }} MAD</strong>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>

    <x-card title="Member Balances" subtitle="Paid amount versus fair share">
        @if(empty($balances))
            <x-empty-state title="No member balances yet" description="Add expenses to generate balances." />
        @else
            <ul class="space-y-3">
                @foreach($balances as $item)
                    <li class="rounded-xl border border-slate-100 bg-slate-50 p-4 transition hover:border-slate-200 hover:bg-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item['user']->name }}</p>
                                <p class="text-xs text-slate-500">Paid {{ number_format((float) $item['paid'], 2) }} MAD • Share {{ number_format((float) $item['share'], 2) }} MAD</p>
                            </div>
                            <strong class="text-lg {{ ((float) $item['balance']) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">{{ number_format((float) $item['balance'], 2) }} MAD</strong>
                        </div>
                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-200">
                            @php
                                $shareValue = max((float) $item['share'], 0.01);
                                $ratio = min(100, max(0, ((float) $item['paid'] / $shareValue) * 100));
                            @endphp
                            <div class="h-full rounded-full {{ ((float) $item['balance']) >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}" style="width: {{ number_format($ratio, 2, '.', '') }}%"></div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filters = document.querySelectorAll('.settle-filter');
            const items = document.querySelectorAll('.settlement-item');

            function applyFilter(mode) {
                items.forEach(function (item) {
                    const type = item.getAttribute('data-type');
                    item.style.display = (mode === 'all' || type === mode) ? '' : 'none';
                });
                filters.forEach(function (btn) {
                    const active = btn.getAttribute('data-filter') === mode;
                    btn.classList.toggle('bg-slate-900', active);
                    btn.classList.toggle('text-white', active);
                    btn.classList.toggle('border-slate-900', active);
                });
            }

            filters.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    applyFilter(btn.getAttribute('data-filter'));
                });
            });

            applyFilter('all');
        });
    </script>
</x-app-layout>
