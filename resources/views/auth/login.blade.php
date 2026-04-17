<x-guest-layout>
    <div class="overflow-hidden rounded-[2rem] border border-slate-300 bg-[#f7f9fc] shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
        <div class="border-b border-slate-300/80 bg-[#eef2f7] px-8 py-8 text-slate-900">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Secure Access</p>
            <h1 class="mt-3 text-3xl font-semibold">Log in to Laravel POS</h1>
            <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">
                Use your assigned account to continue to the admin or cashier workspace.
            </p>
        </div>

        <div class="px-8 py-8 text-slate-900">
            <x-auth-session-status class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

            <div class="mb-6 rounded-2xl border border-slate-300 bg-white p-5 text-sm text-slate-600">
                <p class="font-semibold text-slate-900">Demo accounts</p>
                <p class="mt-3">Admin: <span class="font-mono text-slate-800">admin@pos.test</span> / <span class="font-mono text-slate-800">password</span></p>
                <p class="mt-1">Cashier: <span class="font-mono text-slate-800">cashier@pos.test</span> / <span class="font-mono text-slate-800">password</span></p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-600" />
                    <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@company.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div>
                    <div class="flex items-center justify-between gap-4">
                        <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-600" />

                        @if (Route::has('password.request'))
                            <a class="rounded-md text-sm font-medium text-slate-500 transition hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Enter your password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
                </div>

                <label for="remember_me" class="flex items-center gap-3 rounded-2xl border border-slate-300 bg-white px-4 py-3">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-slate-900 shadow-sm focus:ring-slate-900" name="remember">
                    <span class="text-sm text-slate-700">{{ __('Keep me signed in on this device') }}</span>
                </label>

                <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                    {{ __('Log in') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
