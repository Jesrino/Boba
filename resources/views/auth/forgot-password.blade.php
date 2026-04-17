<x-guest-layout>
    <div class="overflow-hidden rounded-[2rem] border border-slate-300 bg-[#f7f9fc] shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
        <div class="border-b border-slate-300/80 bg-[#eef2f7] px-8 py-8 text-slate-900">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Password Recovery</p>
            <h1 class="mt-3 text-3xl font-semibold">Reset your password</h1>
            <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">
                Enter your email address and we will send you a secure reset link.
            </p>
        </div>

        <div class="px-8 py-8">
            <x-auth-session-status class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-600" />
                    <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900" type="email" name="email" :value="old('email')" required autofocus placeholder="name@company.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        {{ __('Email Password Reset Link') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
