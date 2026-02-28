<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - FairShare</title>

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
<body class="min-h-screen bg-slate-50 text-slate-900">
    <main class="mx-auto grid min-h-screen max-w-6xl grid-cols-1 gap-8 px-4 py-8 lg:grid-cols-2 lg:items-center lg:px-8">
        <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-cyan-600 via-sky-600 to-blue-700 p-8 text-white shadow-xl shadow-sky-200">
            <div class="absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-cyan-300/20 blur-2xl"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-100">FairShare</p>
                <h1 class="mt-3 text-4xl font-black tracking-tight">Shared expenses with zero friction.</h1>
                <p class="mt-4 text-cyan-100">Track contributions, balances, and settlements in one workspace for your colocation.</p>
                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-sm">Live balances</div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-sm">Clean settlements</div>
                    <div class="rounded-2xl border border-white/20 bg-white/10 p-3 text-sm">Trusted history</div>
                </div>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-black tracking-tight text-slate-900">Welcome Back</h2>
                <p class="mt-2 text-slate-500">Sign in to continue to your dashboard.</p>
            </div>

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
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email Address</label>
                    <input name="email" value="{{ old('email') }}" type="email" placeholder="alex@example.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                    <input name="password" type="password" placeholder="********" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <x-button class="w-full">Sign In</x-button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                No account yet?
                <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Create one</a>
            </p>
        </section>
    </main>
</body>
</html>
