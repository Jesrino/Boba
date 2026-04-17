<nav class="pointer-events-none fixed inset-0 z-40">
    <div
        x-show="sidebarOpen"
        x-transition.opacity
        @click="sidebarOpen = false"
        class="absolute inset-0 bg-slate-900/35 backdrop-blur-[2px] pointer-events-auto"
        x-cloak
    ></div>

    <aside
        x-show="sidebarOpen"
        x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="pointer-events-auto absolute left-0 top-0 h-full w-[300px] border-r border-slate-300/80 bg-[#edf1f6] shadow-[0_18px_55px_rgba(15,23,42,0.16)]"
        x-cloak
    >
        <div class="flex h-full flex-col gap-6 p-5 lg:p-6">
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('dashboard') }}" class="flex min-w-0 flex-1 items-center" @click="sidebarOpen = false">
                    <span>
                        <span class="block font-display text-lg font-semibold text-slate-900">Boba Terminal</span>
                        <span class="block text-xs uppercase tracking-[0.3em] text-amber-600">{{ Auth::user()->isAdmin() ? 'Admin' : 'Cashier' }}</span>
                    </span>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-300 bg-[#f8fafc] text-slate-700 shadow-sm"
                >
                    <svg class="h-4 w-4" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            @if (Auth::user()->isAdmin())
                <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="block rounded-[26px] border border-slate-300 bg-[#f7f9fc] p-4 text-slate-900 transition hover:bg-white">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold uppercase text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-semibold">{{ Auth::user()->name }}</p>
                            <p class="truncate text-sm text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-3">
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            {{ Auth::user()->role }}
                        </span>
                        <span class="text-sm font-medium text-slate-500">Profile</span>
                    </div>
                </a>
            @else
                <div class="rounded-[26px] border border-slate-300 bg-[#f7f9fc] p-4 text-slate-900">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold uppercase text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate font-semibold">{{ Auth::user()->name }}</p>
                            <p class="truncate text-sm text-slate-500">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center justify-between gap-3">
                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.3em] text-emerald-700">
                            {{ Auth::user()->role }}
                        </span>
                        <span class="text-sm font-medium text-slate-500">POS Cashier</span>
                    </div>
                </div>
            @endif

            <div>
                <p class="px-2 text-[11px] font-semibold uppercase tracking-[0.32em] text-slate-400">Workspace</p>
                <div class="mt-3 space-y-2">
                    @if (Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                            Overview
                        </a>
                        <a href="{{ route('admin.products.index') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.products.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                            Products
                        </a>
                        <a href="{{ route('admin.inventory.index') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.inventory.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                            Inventory
                        </a>
                        <a href="{{ route('admin.sales.index') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.sales.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                            Sales
                        </a>
                    @else
                        <a href="{{ route('cashier.pos') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('cashier.pos') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('cashier.transactions.index') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('cashier.transactions.*') || request()->routeIs('cashier.receipt.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                            Transactions
                        </a>
                    @endif
                </div>
            </div>

            <div class="mt-auto space-y-3">
                @if (Auth::user()->isAdmin())
                    <a href="{{ route('admin.cashiers.index') }}" @click="sidebarOpen = false" class="block rounded-2xl px-4 py-3 text-sm font-medium {{ request()->routeIs('admin.cashiers.*') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-[#e4eaf2] hover:text-slate-900' }}">
                        Users
                    </a>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full rounded-2xl border border-slate-300 bg-[#f8fafc] px-4 py-3 text-left text-sm font-medium text-slate-600 shadow-sm hover:bg-[#e8edf3] hover:text-slate-900">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </aside>
</nav>
