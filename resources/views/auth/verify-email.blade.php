<x-guest-layout>
    <div class="overflow-hidden rounded-[2rem] border border-slate-300 bg-[#f7f9fc] shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
        <div class="border-b border-slate-300/80 bg-[#eef2f7] px-8 py-8 text-slate-900">
            <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Email Verification</p>
            <h1 class="mt-3 text-3xl font-semibold">Verify your email address</h1>
            <p class="mt-3 max-w-md text-sm leading-6 text-slate-600">
                {{ __('Before getting started, please verify your email address using the link we just sent. If you did not receive it, we can send another.') }}
            </p>
        </div>

        <div class="space-y-6 px-8 py-8">
            @if (session('status') == 'verification-link-sent')
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                    {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                </div>
            @endif

            <div class="flex items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-sm font-semibold uppercase tracking-[0.2em] text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        {{ __('Resend Verification Email') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="rounded-md text-sm font-medium text-slate-500 transition hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900 focus:ring-offset-2">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
