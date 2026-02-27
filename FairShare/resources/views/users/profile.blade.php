<x-app-layout>
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
                <dd class="mt-1 font-semibold text-slate-900">{{ auth()->user()->name }}</dd>
            </div>
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4">
                <dt class="text-slate-500">Email Address</dt>
                <dd class="mt-1 font-semibold text-slate-900">{{ auth()->user()->email }}</dd>
            </div>
        </dl>
    </x-card>
</x-app-layout>
