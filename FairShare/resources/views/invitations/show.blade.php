<x-app-layout>
    @php
        $state = $invitation->status ?? 'pending';
    @endphp

    <div class="mx-auto max-w-2xl">
        <x-card>
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold tracking-tight text-slate-900">Invitation to Join Colocation</h2>
                <p class="mt-2 text-slate-500">You were invited to join <span class="font-semibold text-slate-900">{{ $colocation->name ?? 'a colocation' }}</span>.</p>
            </div>

            <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                <p><strong>Email:</strong> {{ $invitation->email ?? auth()->user()->email }}</p>
                <p><strong>Status:</strong> {{ ucfirst($state) }}</p>
            </div>

            @if($state === 'pending')
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-end">
                    <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}">
                        @csrf
                        <x-button type="submit" variant="secondary" class="w-full sm:w-auto">Refuse Invitation</x-button>
                    </form>
                    <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                        @csrf
                        <x-button type="submit" class="w-full sm:w-auto">Accept Invitation</x-button>
                    </form>
                </div>
            @else
                <x-alert type="info">This invitation has already been {{ $state }}.</x-alert>
            @endif
        </x-card>
    </div>
</x-app-layout>
