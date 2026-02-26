<div class="min-h-screen flex items-center justify-center bg-slate-50 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 rounded-2xl shadow-xl shadow-indigo-200 mb-4">
                <span class="text-white text-2xl font-bold">E</span>
            </div>
            <h1 class="text-3xl font-bold text-slate-900 tracking-tight">Welcome Back</h1>
            <p class="text-slate-500 mt-2">Log in to manage your roommate expenses</p>
        </div>

        <x-card>
            <form class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input type="email" placeholder="alex@example.com" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <label class="block text-sm font-medium text-slate-700">Password</label>
                        <a href="#" class="text-xs text-indigo-600 hover:underline">Forgot?</a>
                    </div>
                    <input type="password" placeholder="••••••••" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none">
                </div>
                <x-button class="w-full py-4 text-base">Sign In</x-button>
            </form>
        </x-card>

        <p class="text-center mt-8 text-sm text-slate-500">
            Don't have an account? <a href="/register" class="text-indigo-600 font-semibold hover:underline">Start for free</a>
        </p>
    </div>
</div>