<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#EFF2F6] font-sans text-slate-900 antialiased">
        <div class="relative isolate min-h-screen overflow-hidden">
            <div class="absolute inset-0 bg-[linear-gradient(180deg,#eff2f6_0%,#e7edf4_100%)]"></div>

            <div class="relative mx-auto flex min-h-screen max-w-7xl items-center px-6 py-10 lg:px-8">
                <div class="grid w-full gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <section class="hidden lg:block">
                        <a href="/" class="inline-flex items-center">
                            <span>
                                <span class="block text-sm uppercase tracking-[0.35em] text-slate-500">Laravel POS</span>
                                <span class="mt-2 block text-3xl font-semibold text-slate-900">Professional point-of-sale access</span>
                            </span>
                        </a>

                        <p class="mt-8 max-w-xl text-lg leading-8 text-slate-600">
                            Secure sign-in for admins and cashiers with a clean workspace, fast access to the POS, and room to grow into inventory, checkout, and reporting.
                        </p>

                        <div class="mt-10 grid max-w-xl gap-4 sm:grid-cols-2">
                            <div class="rounded-3xl border border-slate-300 bg-[#f7f9fc] p-5 shadow-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Fast access</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Built for quick daily sign-ins so staff can get straight to selling.
                                </p>
                            </div>

                            <div class="rounded-3xl border border-slate-300 bg-[#f7f9fc] p-5 shadow-sm">
                                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-slate-500">Role aware</p>
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    Admin and cashier accounts are routed to the right POS experience.
                                </p>
                            </div>
                        </div>
                    </section>

                    <div class="w-full max-w-xl justify-self-center lg:justify-self-end">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
