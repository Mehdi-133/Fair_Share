<x-app-layout>
    @php
        $profileUser = $user ?? auth()->user();
        $reputationTotal = (int) ($reputationTotal ?? 0);
        $reputationHistory = $reputationHistory ?? collect();
    @endphp

    <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-50 via-white to-amber-50 p-6">
        <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full bg-cyan-200/35 blur-2xl"></div>
        <div class="relative flex flex-col gap-2">
            <h2 class="text-2xl font-bold tracking-tight text-slate-900">My Profile</h2>
            <p class="text-slate-500">Your account information.</p>
        </div>
    </section>

    <x-card title="Profile Details" subtitle="Basic account data">
        <dl class="grid grid-cols-1 gap-4 text-sm sm:grid-cols-2">
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <dt class="text-slate-500">Full Name</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $profileUser->name }}</dd>
            </div>
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <dt class="text-slate-500">Email Address</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ $profileUser->email }}</dd>
            </div>
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 sm:col-span-2">
                <dt class="text-slate-500">Reputation Score</dt>
                <dd class="mt-1 text-2xl font-bold {{ $reputationTotal >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                    {{ $reputationTotal }}
                </dd>
            </div>
        </dl>
    </x-card>

    <x-card title="Reputation History" subtitle="Latest +1/-1 actions">
        @if($reputationHistory->isEmpty())
            <x-empty-state title="No reputation events yet" description="No reputation activity recorded." />
        @else
            <ul class="space-y-2">
                @foreach($reputationHistory as $entry)
                    <li class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-3 py-2 text-sm">
                        <div>
                            <p class="font-medium text-slate-900">{{ $entry->reason ?? 'Reputation update' }}</p>
                            <p class="text-xs text-slate-500">{{ optional($entry->created_at)->format('M d, Y H:i') }}</p>
                        </div>
                        <strong class="{{ (int) $entry->score >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ (int) $entry->score >= 0 ? '+' : '' }}{{ (int) $entry->score }}
                        </strong>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-card>
</x-app-layout>
