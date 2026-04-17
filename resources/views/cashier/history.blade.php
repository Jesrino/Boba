<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Cashier History</p>
            <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900">Recent transactions</h2>
        </div>
    </x-slot>

    <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <form method="GET" class="mb-6">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search receipt number" class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 focus:border-slate-900 focus:outline-none">
            </form>

            <div class="space-y-4">
                @forelse ($transactions as $transaction)
                    <div class="flex flex-col gap-3 rounded-[22px] bg-slate-50 px-4 py-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="font-medium text-slate-900">{{ $transaction->receipt_number }}</p>
                            <p class="text-sm text-slate-500">{{ $transaction->paid_at?->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <p class="font-semibold text-slate-900">PHP {{ number_format((float) $transaction->total_amount, 2) }}</p>
                            <a href="{{ route('cashier.receipt.show', $transaction) }}" class="rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white">View</a>
                        </div>
                    </div>
                @empty
                    <div class="rounded-[22px] bg-slate-50 px-4 py-4 text-sm text-slate-500">No transactions found.</div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
