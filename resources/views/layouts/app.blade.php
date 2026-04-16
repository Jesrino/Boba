<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#EFF2F6] font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="min-h-screen bg-[linear-gradient(180deg,#eff2f6_0%,#e7edf4_100%)] text-slate-900">
            @include('layouts.navigation')

            <div class="min-h-screen p-2 sm:p-3 lg:p-4">
                <div class="min-h-[calc(100vh-0.5rem)] overflow-hidden rounded-[28px] border border-slate-300/80 bg-[#f6f8fb] shadow-[0_18px_55px_rgba(15,23,42,0.08)]">
                    @isset($header)
                        <header class="flex items-center gap-4 border-b border-slate-300/80 bg-[#eef2f7] px-5 py-5 sm:px-7 lg:px-8">
                            <button
                                type="button"
                                @click="sidebarOpen = true"
                                class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border border-slate-300 bg-[#f8fafc] text-slate-700 shadow-sm"
                            >
                                <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="min-w-0 flex-1">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main>
                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
