<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }} | Simple POS</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#EFF2F6] text-slate-900">
        <div class="relative isolate overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,#eff2f6_0%,#e7edf4_100%)]"></div>

            <div class="relative mx-auto flex min-h-screen max-w-7xl flex-col px-6 py-10 lg:px-8">
                <header class="flex items-center justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Laravel POS</p>
                        <h1 class="mt-3 text-3xl font-semibold sm:text-4xl">Simple POS starter</h1>
                    </div>

                    @auth
                        <nav class="flex items-center gap-3">
                            <a href="{{ route('pos.index') }}" class="rounded-full bg-slate-900 px-5 py-2 text-sm font-semibold text-white shadow-sm">
                                Open POS
                            </a>
                        </nav>
                    @endauth
                </header>

                <main class="grid flex-1 items-center gap-10 py-12 lg:grid-cols-[1.3fr_0.9fr]">
                    <section>
                        <p class="text-sm uppercase tracking-[0.35em] text-slate-500">Phase 1 Placeholder</p>
                        <h2 class="mt-4 max-w-3xl text-4xl font-semibold leading-tight sm:text-6xl">
                            Admin and cashier login are ready for the first POS shell.
                        </h2>
                        <p class="mt-6 max-w-2xl text-base leading-7 text-slate-600">
                            This version uses Laravel Breeze for authentication and gives each seeded user a simple role-aware POS homepage. It is intentionally lightweight so we can extend it step by step.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('login') }}" class="rounded-full bg-slate-900 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                                Log in
                            </a>

                            @auth
                                <a href="{{ route('pos.index') }}" class="rounded-full border border-slate-300 bg-[#f8fafc] px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-white">
                                    Go to POS
                                </a>
                            @endauth
                        </div>
                    </section>

                    <section class="rounded-[2rem] border border-slate-300 bg-[#f7f9fc] p-6 shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Demo accounts</p>

                        <div class="mt-6 space-y-4">
                            <div class="rounded-2xl border border-slate-300 bg-white p-4">
                                <p class="text-sm font-semibold text-slate-900">Admin</p>
                                <p class="mt-2 text-sm text-slate-600">Email: admin@pos.test</p>
                                <p class="text-sm text-slate-600">Password: password</p>
                            </div>

                            <div class="rounded-2xl border border-slate-300 bg-white p-4">
                                <p class="text-sm font-semibold text-slate-900">Cashier</p>
                                <p class="mt-2 text-sm text-slate-600">Email: cashier@pos.test</p>
                                <p class="text-sm text-slate-600">Password: password</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl border border-slate-300 bg-[#eef2f7] p-4 text-sm leading-6 text-slate-600">
                            Next steps can be products, categories, cart, checkout, and sales reports.
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </body>
</html>
