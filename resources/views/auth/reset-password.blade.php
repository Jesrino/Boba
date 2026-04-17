<x-guest-layout>
    <div class="overflow-hidden rounded-[2rem] border border-slate-300 bg-[#f7f9fc] shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
        <div class="border-b border-slate-300/80 bg-[#eef2f7] px-8 py-8 text-slate-900">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-500">New Password</p>
            <h1 class="mt-3 text-3xl font-semibold">Choose a new password</h1>
            <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">
                Update your password to regain access to your Laravel POS account.
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5 px-8 py-8">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-600" />
                <x-text-input id="email" class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-600" />
                <x-text-input id="password" class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900" type="password" name="password" required autocomplete="new-password" placeholder="Create a secure password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-sm font-semibold uppercase tracking-[0.18em] text-slate-600" />
                <x-text-input id="password_confirmation" class="mt-2 block w-full rounded-2xl border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm focus:border-slate-900 focus:ring-slate-900" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Repeat your password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600" />
            </div>

            <div class="flex justify-end">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
