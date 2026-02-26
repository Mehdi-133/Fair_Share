<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EasyColoc</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            <div class="mb-10 text-center">
                <div class="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-indigo-600 shadow-xl shadow-indigo-200">
                    <span class="text-2xl font-bold text-white">E</span>
                </div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Welcome Back</h1>
                <p class="mt-2 text-slate-500">Log in to manage your colocation expenses.</p>
            </div>

            <x-card>
                <form method="POST" action="{{ route('login.attempt') }}" class="space-y-5">
                    @csrf

                    @if (session('message'))
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Email Address</label>
                        <input name="email" value="{{ old('email') }}" type="email" placeholder="alex@example.com" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-slate-700">Password</label>
                        <input name="password" type="password" placeholder="********" class="w-full rounded-xl border border-slate-200 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20">
                    </div>

                    <x-button class="w-full">Sign In</x-button>
                </form>
            </x-card>

            <p class="mt-8 text-center text-sm text-slate-500">
                No account yet?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Create one</a>
            </p>
        </div>
    </div>
</body>
</html>
