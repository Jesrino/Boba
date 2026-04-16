<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Admin Sales</p>
            <h2 class="font-display text-2xl font-semibold text-slate-900">Transaction history</h2>
        </div>
    </x-slot>
    <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
        <div class="rounded-[30px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Sales Search</p>
                    <h3 class="mt-2 font-display text-2xl font-semibold text-slate-900">Find receipts and review completed orders</h3>
                </div>
                <form method="GET" class="w-full md:max-w-sm">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search receipt number" class="w-full rounded-2xl border border-slate-200 bg-[#f6f8fb] px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none">
                </form>
            </div>
            <div class="mt-6 space-y-3">
                @foreach ($transactions as $transaction)
                    <div class="flex items-center justify-between rounded-[22px] bg-slate-50 px-4 py-4">
                        <div>
                            <p class="font-medium text-slate-900">{{ $transaction->receipt_number }}</p>
                            <p class="text-sm text-slate-500">{{ $transaction->cashier?->name }} - {{ $transaction->paid_at?->format('M d, h:i A') }}</p>
                        </div>
                        <p class="font-semibold text-slate-900">PHP {{ number_format((float) $transaction->total_amount, 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-6">{{ $transactions->links() }}</div>
        </div>
    </div>
</x-app-layout>
