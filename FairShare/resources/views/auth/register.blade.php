<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - FairShare</title>

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
        <section class="relative overflow-hidden rounded-3xl border border-slate-200 bg-gradient-to-br from-amber-500 via-orange-500 to-rose-500 p-8 text-white shadow-xl shadow-orange-200">
            <div class="absolute -right-16 -top-16 h-52 w-52 rounded-full bg-white/10 blur-2xl"></div>
            <div class="absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-amber-300/20 blur-2xl"></div>
            <div class="relative">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-amber-100">FairShare</p>
                <h1 class="mt-3 text-4xl font-black tracking-tight">Create your shared finance workspace.</h1>
                <p class="mt-4 text-amber-100">Set up your account and start managing coloc expenses with transparent balances.</p>
                <ul class="mt-8 space-y-2 text-sm text-amber-50">
                    <li>Clear member responsibilities</li>
                    <li>Accurate expense split</li>
                    <li>Fast debt settlement</li>
                </ul>
            </div>
        </section>

        <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="mb-6">
                <h2 class="text-3xl font-black tracking-tight text-slate-900">Create Account</h2>
                <p class="mt-2 text-slate-500">Join FairShare and start tracking with your roommates.</p>
            </div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf

                @if ($errors->any())
                    <div class="rounded-xl border border-rose-100 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Full Name</label>
                    <input name="name" value="{{ old('name') }}" type="text" placeholder="Alex Johnson" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Email</label>
                    <input name="email" value="{{ old('email') }}" type="email" placeholder="alex@example.com" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Password</label>
                    <input name="password" type="password" placeholder="********" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <div>
                    <label class="mb-1.5 block text-sm font-semibold text-slate-700">Confirm Password</label>
                    <input name="password_confirmation" type="password" placeholder="********" class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 outline-none transition-all focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                </div>

                <x-button class="w-full">Create Account</x-button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">
                Already registered?
                <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign in</a>
            </p>
        </section>
    </main>
</body>
</html>
