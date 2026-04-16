<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.35em] text-amber-600">Receipt</p>
                <h2 class="font-display text-2xl font-semibold leading-tight text-slate-900">{{ $transaction->receipt_number }}</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('cashier.pos') }}" class="rounded-2xl border border-slate-300 bg-white px-4 py-3 text-sm font-semibold text-slate-700">
                    Back to POS
                </a>
                <button type="button" onclick="window.print()" class="rounded-2xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white">Print receipt</button>
            </div>
        </div>
    </x-slot>

    <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
        <div class="mx-auto max-w-3xl rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-2 border-b border-slate-200 pb-5 text-sm text-slate-600">
                <p><span class="font-semibold text-slate-900">Cashier:</span> {{ $transaction->cashier->name }}</p>
                <p><span class="font-semibold text-slate-900">Date:</span> {{ $transaction->paid_at?->format('M d, Y h:i A') }}</p>
                <p><span class="font-semibold text-slate-900">Payment:</span> {{ strtoupper($transaction->payment_method) }}</p>
            </div>

            <div class="mt-5 space-y-4">
                @foreach ($transaction->items as $item)
                    <div class="rounded-[22px] bg-slate-50 px-4 py-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-medium text-slate-900">{{ $item->product_name }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $item->size }} - {{ $item->sugar_level }} - {{ $item->ice_level }}</p>
                                @if ($item->add_ons)
                                    <p class="mt-2 text-xs uppercase tracking-[0.18em] text-amber-700">{{ implode(', ', $item->add_ons) }}</p>
                                @endif
                                @if ($item->notes)
                                    <p class="mt-2 text-sm text-slate-500">{{ $item->notes }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-slate-500">x{{ $item->quantity }}</p>
                                <p class="font-semibold text-slate-900">PHP {{ number_format((float) $item->line_total, 2) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 space-y-3 border-t border-slate-200 pt-5 text-sm">
                <div class="flex items-center justify-between"><span class="text-slate-500">Subtotal</span><span class="text-slate-900">PHP {{ number_format((float) $transaction->subtotal, 2) }}</span></div>
                <div class="flex items-center justify-between"><span class="text-slate-500">Tax</span><span class="text-slate-900">PHP {{ number_format((float) $transaction->tax_amount, 2) }}</span></div>
                <div class="flex items-center justify-between"><span class="text-slate-500">Amount received</span><span class="text-slate-900">PHP {{ number_format((float) $transaction->amount_received, 2) }}</span></div>
                <div class="flex items-center justify-between"><span class="text-slate-500">Change</span><span class="text-slate-900">PHP {{ number_format((float) $transaction->change_amount, 2) }}</span></div>
                <div class="flex items-center justify-between border-t border-slate-200 pt-3 text-base font-semibold"><span>Total</span><span>PHP {{ number_format((float) $transaction->total_amount, 2) }}</span></div>
            </div>
        </div>
    </div>
</x-app-layout>
