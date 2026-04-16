<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">{{ $user->isAdmin() ? 'Admin Overview' : 'Cashier POS' }}</p>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900">
                    {{ $user->isAdmin() ? 'Boba shop operations' : 'Front counter POS' }}
                </h2>
            </div>

            <div class="flex flex-wrap gap-3">
                <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    {{ $user->role }}
                </div>
                <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">
                    {{ $user->isAdmin() ? 'Live overview' : 'Queue 12' }}
                </div>
            </div>
        </div>
    </x-slot>

    @if ($user->isAdmin())
        <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
            <div class="grid gap-6 2xl:grid-cols-[minmax(0,1.7fr)_400px]">
                <div class="space-y-6">
                    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-[26px] border border-slate-200 bg-slate-900 p-6 text-white">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-300">Gross Sales</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold">PHP 3,284.20</h3>
                            <p class="mt-2 text-sm text-slate-300">Today</p>
                        </article>
                        <article class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Orders</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">214</h3>
                            <p class="mt-2 text-sm text-slate-500">Completed</p>
                        </article>
                        <article class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Avg Ticket</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">PHP 153.50</h3>
                            <p class="mt-2 text-sm text-slate-500">Per order</p>
                        </article>
                        <article class="rounded-[26px] border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-700">Low Stock</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">3 items</h3>
                            <p class="mt-2 text-sm text-emerald-800">Need attention</p>
                        </article>
                    </section>

                    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_0.85fr]">
                        <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Overview</p>
                                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Store snapshot</h3>
                                </div>
                                <span class="rounded-full bg-slate-100 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700">Realtime</span>
                            </div>

                            <div class="mt-6 grid gap-4 md:grid-cols-2">
                                <div class="rounded-[24px] bg-slate-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Top sellers</p>
                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                        <li class="flex items-center justify-between"><span>Brown Sugar Boba</span><span class="font-semibold text-slate-900">62 cups</span></li>
                                        <li class="flex items-center justify-between"><span>Jasmine Milk Tea</span><span class="font-semibold text-slate-900">48 cups</span></li>
                                        <li class="flex items-center justify-between"><span>Strawberry Cloud</span><span class="font-semibold text-slate-900">31 cups</span></li>
                                    </ul>
                                </div>
                                <div class="rounded-[24px] bg-slate-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Channels</p>
                                    <ul class="mt-4 space-y-3 text-sm text-slate-600">
                                        <li class="flex items-center justify-between"><span>Walk-in</span><span class="font-semibold text-slate-900">54%</span></li>
                                        <li class="flex items-center justify-between"><span>Pickup</span><span class="font-semibold text-slate-900">28%</span></li>
                                        <li class="flex items-center justify-between"><span>Delivery</span><span class="font-semibold text-slate-900">18%</span></li>
                                    </ul>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Operations</p>
                            <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Live counters</h3>
                            <div class="mt-6 space-y-4">
                                <div class="rounded-[22px] bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-900">Cashier stations</p>
                                    <p class="mt-2 text-sm text-slate-600">2 active</p>
                                </div>
                                <div class="rounded-[22px] bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-900">Kitchen display</p>
                                    <p class="mt-2 text-sm text-slate-600">9 drinks in queue</p>
                                </div>
                                <div class="rounded-[22px] bg-amber-50 p-4">
                                    <p class="text-sm font-semibold text-slate-900">Stock notice</p>
                                    <p class="mt-2 text-sm text-amber-800">Brown sugar pearls low</p>
                                </div>
                            </div>
                        </article>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Inventory</p>
                        <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Low stock watch</h3>
                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4"><span>Brown sugar pearls</span><span class="font-semibold text-amber-700">14%</span></div>
                            <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4"><span>Oat milk</span><span class="font-semibold text-amber-700">21%</span></div>
                            <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4"><span>Matcha powder</span><span class="font-semibold text-amber-700">18%</span></div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    @else
        <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
            <div class="grid gap-6 2xl:grid-cols-[minmax(0,1.85fr)_400px]">
                <div class="space-y-6">
                    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <article class="rounded-[26px] border border-slate-200 bg-slate-900 p-6 text-white">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-300">Queue</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold">12</h3>
                            <p class="mt-2 text-sm text-slate-300">Live orders</p>
                        </article>
                        <article class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Ticket Time</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">6 min</h3>
                            <p class="mt-2 text-sm text-slate-500">Average</p>
                        </article>
                        <article class="rounded-[26px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Loyalty Scan</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">27</h3>
                            <p class="mt-2 text-sm text-slate-500">Today</p>
                        </article>
                        <article class="rounded-[26px] border border-emerald-200 bg-emerald-50 p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-emerald-700">Upsell Rate</p>
                            <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">34%</h3>
                            <p class="mt-2 text-sm text-emerald-800">Add-ons</p>
                        </article>
                    </section>

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">POS Menu</p>
                                <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Quick add drinks</h3>
                            </div>
                            <div class="flex flex-wrap gap-2 text-sm">
                                <span class="rounded-full bg-slate-900 px-4 py-2 font-medium text-white">Milk Tea</span>
                                <span class="rounded-full bg-slate-100 px-4 py-2 font-medium text-slate-600">Fruit Tea</span>
                                <span class="rounded-full bg-slate-100 px-4 py-2 font-medium text-slate-600">Cheese Foam</span>
                                <span class="rounded-full bg-slate-100 px-4 py-2 font-medium text-slate-600">Snacks</span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 lg:grid-cols-2 2xl:grid-cols-3">
                            <article class="rounded-[26px] border border-slate-200 bg-[#fcf7f1] p-4 shadow-sm">
                                <div class="rounded-[22px] bg-[#8a4b12] p-5 text-white">
                                    <p class="text-xs uppercase tracking-[0.28em] text-amber-100">Best seller</p>
                                    <h4 class="mt-2 font-display text-xl font-semibold">Brown Sugar Boba</h4>
                                    <p class="mt-8 text-3xl font-semibold">PHP 95.00</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-sm text-slate-600">Large, pearls included</div>
                                    <button class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Add</button>
                                </div>
                            </article>

                            <article class="rounded-[26px] border border-slate-200 bg-[#f3fbf8] p-4 shadow-sm">
                                <div class="rounded-[22px] bg-[#0f766e] p-5 text-white">
                                    <p class="text-xs uppercase tracking-[0.28em] text-emerald-100">Fresh line</p>
                                    <h4 class="mt-2 font-display text-xl font-semibold">Jasmine Green Tea</h4>
                                    <p class="mt-8 text-3xl font-semibold">PHP 85.00</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-sm text-slate-600">With aloe or jelly</div>
                                    <button class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Add</button>
                                </div>
                            </article>

                            <article class="rounded-[26px] border border-slate-200 bg-[#fff6f7] p-4 shadow-sm">
                                <div class="rounded-[22px] bg-[#e85d75] p-5 text-white">
                                    <p class="text-xs uppercase tracking-[0.28em] text-rose-100">Seasonal</p>
                                    <h4 class="mt-2 font-display text-xl font-semibold">Strawberry Cloud</h4>
                                    <p class="mt-8 text-3xl font-semibold">PHP 110.00</p>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="text-sm text-slate-600">Cream top</div>
                                    <button class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Add</button>
                                </div>
                            </article>
                        </div>
                    </section>

                    <section class="grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_0.9fr]">
                        <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Drink Builder</p>
                                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Core modifiers</h3>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-5 md:grid-cols-2">
                                <div class="rounded-[22px] bg-slate-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Cup Size</p>
                                    <div class="mt-4 grid grid-cols-3 gap-3">
                                        <span class="rounded-2xl border border-slate-200 bg-white px-3 py-3 text-center text-sm text-slate-600">Small</span>
                                        <span class="rounded-2xl bg-slate-900 px-3 py-3 text-center text-sm text-white">Medium</span>
                                        <span class="rounded-2xl border border-slate-200 bg-white px-3 py-3 text-center text-sm text-slate-600">Large</span>
                                    </div>
                                </div>
                                <div class="rounded-[22px] bg-slate-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Tea Strength</p>
                                    <div class="mt-4 flex flex-wrap gap-3">
                                        <span class="rounded-full bg-white px-4 py-2 text-sm text-slate-600">Light</span>
                                        <span class="rounded-full bg-slate-900 px-4 py-2 text-sm text-white">Regular</span>
                                        <span class="rounded-full bg-white px-4 py-2 text-sm text-slate-600">Bold</span>
                                    </div>
                                </div>
                                <div class="rounded-[22px] bg-slate-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Sugar Level</p>
                                    <div class="mt-4 grid grid-cols-4 gap-2 text-xs font-semibold uppercase tracking-[0.18em]">
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-500">0%</span>
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-500">25%</span>
                                        <span class="rounded-2xl bg-slate-900 px-3 py-3 text-center text-white">50%</span>
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-500">100%</span>
                                    </div>
                                </div>
                                <div class="rounded-[22px] bg-slate-50 p-5">
                                    <p class="text-sm font-semibold text-slate-900">Ice Level</p>
                                    <div class="mt-4 grid grid-cols-4 gap-2 text-xs font-semibold uppercase tracking-[0.18em]">
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-500">None</span>
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-500">Less</span>
                                        <span class="rounded-2xl bg-slate-900 px-3 py-3 text-center text-white">Normal</span>
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-500">Extra</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 rounded-[22px] border border-dashed border-slate-200 p-5">
                                <p class="text-sm font-semibold text-slate-900">Toppings</p>
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <span class="rounded-full bg-slate-900 px-4 py-2 text-sm text-white">Pearls +PHP 20</span>
                                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">Crystal boba +PHP 15</span>
                                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">Cheese foam +PHP 30</span>
                                    <span class="rounded-full bg-slate-100 px-4 py-2 text-sm text-slate-600">Oat milk +PHP 25</span>
                                </div>
                            </div>
                        </article>

                        <article class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                            <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Quick tools</p>
                            <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Cashier panel</h3>
                            <div class="mt-6 space-y-4">
                                <div class="rounded-[22px] bg-slate-50 p-4">
                                    <p class="text-sm font-semibold text-slate-900">Order channel</p>
                                    <div class="mt-3 grid grid-cols-3 gap-3 text-sm">
                                        <span class="rounded-2xl bg-slate-900 px-3 py-3 text-center text-white">Walk-in</span>
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-600 shadow-sm">Pickup</span>
                                        <span class="rounded-2xl bg-white px-3 py-3 text-center text-slate-600 shadow-sm">Delivery</span>
                                    </div>
                                </div>
                                <div class="rounded-[22px] bg-slate-50 p-4 text-sm text-slate-600">Loyalty scan</div>
                                <div class="rounded-[22px] bg-slate-50 p-4 text-sm text-slate-600">Kitchen sync</div>
                                <div class="rounded-[22px] bg-slate-50 p-4 text-sm text-slate-600">Pearl timer: 12 min</div>
                            </div>
                        </article>
                    </section>
                </div>

                <aside class="space-y-6">
                    <section class="rounded-[28px] border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-slate-300">Current Ticket</p>
                                <h3 class="mt-2 font-display text-2xl font-semibold">Order #B23109</h3>
                            </div>
                            <span class="rounded-full bg-emerald-400/15 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.25em] text-emerald-300">Preparing</span>
                        </div>

                        <div class="mt-6 space-y-4">
                            <article class="rounded-[22px] bg-white/5 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-white">Brown Sugar Boba</p>
                                        <p class="mt-1 text-sm text-slate-300">Large, 50% sugar, normal ice</p>
                                        <p class="mt-2 text-xs uppercase tracking-[0.2em] text-amber-300">Pearls, cheese foam</p>
                                    </div>
                                    <p class="font-semibold">PHP 125.00</p>
                                </div>
                            </article>
                            <article class="rounded-[22px] bg-white/5 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-white">Jasmine Green Tea</p>
                                        <p class="mt-1 text-sm text-slate-300">Medium, 25% sugar, light ice</p>
                                        <p class="mt-2 text-xs uppercase tracking-[0.2em] text-sky-300">Lychee jelly</p>
                                    </div>
                                    <p class="font-semibold">PHP 90.00</p>
                                </div>
                            </article>
                        </div>

                        <div class="mt-6 space-y-3 border-t border-white/10 pt-5 text-sm">
                            <div class="flex items-center justify-between text-slate-300"><span>Subtotal</span><span>PHP 215.00</span></div>
                            <div class="flex items-center justify-between text-slate-300"><span>Discount</span><span>- PHP 20.00</span></div>
                            <div class="flex items-center justify-between text-slate-300"><span>Tax</span><span>PHP 25.80</span></div>
                            <div class="flex items-center justify-between border-t border-white/10 pt-3 text-base font-semibold text-white"><span>Total</span><span>PHP 220.80</span></div>
                        </div>

                        <button class="mt-6 w-full rounded-[20px] bg-white px-5 py-4 text-sm font-semibold text-slate-900">Confirm Payment</button>
                    </section>

                    <section class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Queue Board</p>
                        <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Live prep</h3>
                        <div class="mt-5 space-y-3">
                            <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4"><div><p class="font-medium text-slate-900">Pickup 102</p><p class="text-sm text-slate-500">2 drinks</p></div><span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">Mixing</span></div>
                            <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4"><div><p class="font-medium text-slate-900">Dine-in A4</p><p class="text-sm text-slate-500">3 milk teas</p></div><span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Queued</span></div>
                            <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4"><div><p class="font-medium text-slate-900">Delivery D18</p><p class="text-sm text-slate-500">4 fruit teas</p></div><span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">Ready</span></div>
                        </div>
                    </section>
                </aside>
            </div>
        </div>
    @endif
</x-app-layout>
