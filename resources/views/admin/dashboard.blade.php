<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Admin Overview</p>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900">Store analytics</h2>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.products.index') }}" class="secondary-action">Manage products</a>
                <a href="{{ route('admin.inventory.index') }}" class="primary-action">Review inventory</a>
            </div>
        </div>
    </x-slot>

    <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <article class="metric-card-dark p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-300">Total Sales</p>
                <h3 class="mt-4 font-display text-3xl font-semibold">PHP {{ number_format($salesTotal, 2) }}</h3>
                <p class="mt-2 text-sm text-slate-300">All recorded transactions</p>
            </article>
            <article class="metric-card p-6">
                <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Today</p>
                <h3 class="mt-4 font-display text-3xl font-semibold text-slate-900">PHP {{ number_format($todaySales, 2) }}</h3>
                <p class="mt-2 text-sm text-slate-500">{{ $transactions->count() }} recent sales loaded</p>
            </article>
            <article class="rounded-[26px] border border-cyan-200 bg-cyan-50 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-cyan-900">7-Day Sales</p>
                <h3 class="mt-4 font-display text-3xl font-semibold text-slate-950">PHP {{ number_format($weeklySales, 2) }}</h3>
                <p class="mt-2 text-sm font-medium text-cyan-900">Rolling weekly view</p>
            </article>
            <article class="rounded-[26px] border border-amber-200 bg-amber-50 p-6 shadow-sm">
                <p class="text-xs uppercase tracking-[0.3em] text-amber-800">Avg Ticket</p>
                <h3 class="mt-4 font-display text-3xl font-semibold text-slate-950">PHP {{ number_format($averageTicket, 2) }}</h3>
                <p class="mt-2 text-sm font-medium text-amber-900">Across all completed orders</p>
            </article>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[1.35fr_0.95fr]">
            <section class="panel-card rounded-[30px]">
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                        <div>
                            <p class="section-kicker">Trend</p>
                            <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">
                                {{ $trendPeriod === 'month' ? 'Sales by month' : 'Sales over the last 7 days' }}
                            </h3>
                            <p class="mt-2 text-sm text-slate-500">
                                {{ $trendPeriod === 'month'
                                    ? $trendStart->format('Y')
                                    : $trendStart->format('M d, Y').' to '.$trendEnd->format('M d, Y') }}
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('admin.dashboard', ['trend' => '7d']) }}" class="{{ $trendPeriod === '7d' ? 'primary-action' : 'secondary-action' }}">
                                Last 7 days
                            </a>
                            <a href="{{ route('admin.dashboard', ['trend' => 'month']) }}" class="{{ $trendPeriod === 'month' ? 'primary-action' : 'secondary-action' }}">
                                By month
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-4 text-sm">
                        <div class="panel-soft px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Sales</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">PHP {{ number_format($trendSalesTotal, 2) }}</p>
                            <p class="mt-1 text-xs text-slate-500">Within the selected range</p>
                        </div>
                        <div class="panel-soft px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Orders</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $trendOrderTotal }}</p>
                            <p class="mt-1 text-xs text-slate-500">Completed transactions</p>
                        </div>
                        <div class="panel-soft px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Active Cashiers</p>
                            <p class="mt-2 text-xl font-semibold text-slate-900">{{ $trendActiveCashiers }}</p>
                            <p class="mt-1 text-xs text-slate-500">Cashiers with recorded sales</p>
                        </div>
                        <div class="panel-soft px-4 py-4">
                            <p class="text-xs uppercase tracking-[0.25em] text-slate-400">Top Cashier</p>
                            @if ($topCashierInPeriod && $topCashierInPeriod->cashier)
                                <p class="mt-2 text-base font-semibold text-slate-900">{{ $topCashierInPeriod->cashier->name }}</p>
                                <p class="mt-1 text-xs text-slate-500">
                                    PHP {{ number_format((float) $topCashierInPeriod->sales_total, 2) }} • {{ (int) $topCashierInPeriod->order_count }} orders
                                </p>
                            @else
                                <p class="mt-2 text-base font-semibold text-slate-900">No sales yet</p>
                                <p class="mt-1 text-xs text-slate-500">Waiting for cashier activity</p>
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="flex flex-wrap gap-3 text-sm">
                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-slate-600">
                                <span class="h-2 w-2 rounded-full bg-slate-900"></span>
                                Real transaction data
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-slate-600">
                                Products: {{ $productCount }}
                            </div>
                            <div class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-4 py-2 text-slate-600">
                                Low stock: {{ $inventoryLowCount }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 overflow-x-auto pb-2">
                    <div class="{{ $salesTrend->count() <= 10 ? 'grid grid-cols-7 items-end gap-4' : 'flex min-w-max items-end gap-4' }}">
                    @foreach ($salesTrend as $point)
                        <div class="flex {{ $salesTrend->count() <= 10 ? 'w-full' : 'w-[94px] shrink-0' }} flex-col items-center gap-3">
                            <div class="flex h-72 w-full items-end rounded-[24px] bg-[#f4f7fb] p-3">
                                <div
                                    class="w-full rounded-[18px] bg-[linear-gradient(180deg,#0f172a_0%,#1d4ed8_100%)]"
                                    style="height: {{ max(10, ($point['sales'] / $salesTrendMax) * 100) }}%;"
                                    title="PHP {{ number_format($point['sales'], 2) }} | {{ $point['orders'] }} orders | {{ $point['cashiers'] }} cashiers"
                                ></div>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-slate-900">{{ $point['label'] }}</p>
                                <p class="text-xs text-slate-500">{{ $point['date'] }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $point['orders'] }} orders</p>
                                <p class="text-xs text-slate-400">{{ $point['cashiers'] }} cashiers</p>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </section>

            <div class="space-y-6">
                <section class="panel-card rounded-[30px]">
                    <p class="section-kicker">Payment Mix</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Where revenue is coming from</h3>
                    <div class="mt-6 space-y-4">
                        @foreach ($paymentMix as $mix)
                            <div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="font-medium text-slate-900">{{ $mix['label'] }}</span>
                                    <span class="text-slate-500">PHP {{ number_format($mix['amount'], 2) }} - {{ number_format($mix['share'], 1) }}%</span>
                                </div>
                                <div class="mt-2 h-3 rounded-full bg-slate-100">
                                    <div class="h-3 rounded-full bg-slate-900" style="width: {{ max(6, $mix['share']) }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="panel-card rounded-[30px]">
                    <p class="section-kicker">Inventory Watch</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Items to restock</h3>
                    <div class="mt-5 space-y-3">
                        @forelse ($lowStockItems as $item)
                            <div class="rounded-[20px] bg-amber-50 px-4 py-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-medium text-slate-900">{{ $item->name }}</p>
                                        <p class="text-sm text-slate-500">{{ rtrim(rtrim(number_format((float) $item->stock, 2), '0'), '.') }} {{ $item->unit }} left</p>
                                    </div>
                                    <p class="text-sm font-semibold text-amber-800">Reorder at {{ rtrim(rtrim(number_format((float) $item->reorder_level, 2), '0'), '.') }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-[20px] bg-slate-50 px-4 py-4 text-sm text-slate-500">Inventory looks healthy right now.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[1.05fr_0.95fr]">
            <section class="panel-card rounded-[30px]">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="section-kicker">Top Sellers</p>
                        <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Best-performing menu items</h3>
                    </div>
                    <a href="{{ route('admin.products.index') }}" class="secondary-action px-4 py-3">Manage products</a>
                </div>
                <div class="mt-6 space-y-3">
                    @forelse ($topProducts as $product)
                        <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $product->product_name }}</p>
                                <p class="text-sm text-slate-500">{{ (int) $product->quantity_sold }} cups sold</p>
                            </div>
                            <p class="font-semibold text-slate-900">PHP {{ number_format((float) $product->gross_sales, 2) }}</p>
                        </div>
                    @empty
                        <div class="rounded-[22px] bg-slate-50 px-4 py-4 text-sm text-slate-500">No sales data yet.</div>
                    @endforelse
                </div>
            </section>

            <section class="panel-card rounded-[30px]">
                <p class="section-kicker">Operations</p>
                <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Cashier performance and recent sales</h3>

                <div class="mt-6 space-y-3">
                    @foreach ($cashierPerformance as $cashier)
                        <div class="rounded-[22px] bg-slate-50 px-4 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $cashier->cashier?->name ?? 'Unknown cashier' }}</p>
                                    <p class="text-sm text-slate-500">{{ (int) $cashier->order_count }} orders handled</p>
                                    <p class="mt-1 text-xs text-slate-400">
                                        Latest sale: {{ $cashier->latest_paid_at ? \Carbon\Carbon::parse($cashier->latest_paid_at)->format('M d, Y h:i A') : 'No recent sale' }}
                                    </p>
                                </div>
                                <p class="font-semibold text-slate-900">PHP {{ number_format((float) $cashier->sales_total, 2) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 border-t border-slate-200 pt-6">
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Recent Transactions</p>
                    <div class="mt-4 space-y-3">
                        @forelse ($transactions as $transaction)
                            <div class="flex items-center justify-between rounded-[20px] bg-slate-50 px-4 py-4">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $transaction->receipt_number }}</p>
                                    <p class="text-sm text-slate-500">{{ $transaction->cashier?->name }} - {{ $transaction->paid_at?->format('M d, h:i A') }}</p>
                                </div>
                                <p class="font-semibold text-slate-900">PHP {{ number_format((float) $transaction->total_amount, 2) }}</p>
                            </div>
                        @empty
                            <div class="rounded-[20px] bg-slate-50 px-4 py-4 text-sm text-slate-500">No transactions yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
