<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Blocked</title>

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
<body class="min-h-screen antialiased text-slate-900">
    <main class="mx-auto flex min-h-screen w-full max-w-3xl items-center px-4 py-10">
        <section class="w-full rounded-3xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-amber-50 p-8 shadow-sm">
            <div class="mb-6 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-rose-100 text-rose-700">
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-8.08 14A2 2 0 003.92 21h16.16a2 2 0 001.71-3.14l-8.08-14a2 2 0 00-3.42 0z"/>
                </svg>
            </div>

            <h1 class="text-3xl font-black tracking-tight text-slate-900">You Are Blocked</h1>
            <p class="mt-3 text-slate-600">
                Your account has been blocked by an administrator. You cannot access colocations, expenses, or other features.
            </p>

            <div class="mt-6 rounded-2xl border border-rose-100 bg-white/80 p-4 text-sm text-rose-700">
                If you believe this is a mistake, contact the platform administrator.
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Logout
                </button>
            </form>
        </section>
    </main>
</body>
</html>
