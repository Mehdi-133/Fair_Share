<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyColoc - Shared Living Made Simple</title>

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
<body class="h-full overflow-hidden antialiased text-slate-900">
    <div class="h-screen">
        <x-sidebar />

        <div class="relative flex h-screen min-w-0 flex-1 flex-col overflow-hidden lg:ml-72">
            <div aria-hidden="true" class="pointer-events-none absolute inset-0">
                <div class="absolute -left-20 -top-16 h-72 w-72 rounded-full bg-cyan-100/70 blur-3xl"></div>
                <div class="absolute -right-24 top-1/3 h-80 w-80 rounded-full bg-amber-100/70 blur-3xl"></div>
            </div>
            <x-navbar />

            <main class="relative z-10 flex-1 overflow-y-auto p-4 md:p-8">
                <div class="mx-auto max-w-7xl space-y-8">
                    @if (session('message'))
                        <x-alert type="success">{{ session('message') }}</x-alert>
                    @endif

                    @if (session('link'))
                        <x-alert type="info">{{ session('link') }}</x-alert>
                    @endif

                    @if ($errors->any())
                        <x-alert type="danger">{{ $errors->first() }}</x-alert>
                    @endif

                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
