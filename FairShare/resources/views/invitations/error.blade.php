<x-app-layout>
    @php
        $errorMessage = session('invitation_error') ?? 'This invitation token is invalid, expired, or already used.';
    @endphp

    <div class="mx-auto max-w-xl">
        <x-card>
            <div class="text-center">
                <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-rose-100 text-rose-600">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M5.07 19h13.86c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.34 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-slate-900">Invitation Error</h2>
                <p class="mt-2 text-slate-500">{{ $errorMessage }}</p>
            </div>

            <div class="mt-6 flex justify-center">
                @auth
                    <a href="{{ route('dashboard') }}"><x-button>Back to Dashboard</x-button></a>
                @else
                    <a href="{{ route('login') }}"><x-button>Go to Login</x-button></a>
                @endauth
            </div>
        </x-card>
    </div>
</x-app-layout>
