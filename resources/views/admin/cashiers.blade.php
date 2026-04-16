<x-app-layout>
    <x-slot name="header"><h2 class="font-display text-2xl font-semibold text-slate-900">Cashiers</h2></x-slot>
    <div class="px-5 py-5 sm:px-7 sm:py-7 lg:px-8">
        <div class="rounded-[28px] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="space-y-3">
                @foreach ($cashiers as $cashier)
                    <div class="flex items-center justify-between rounded-[20px] bg-slate-50 px-4 py-4">
                        <div><p class="font-medium text-slate-900">{{ $cashier->name }}</p><p class="text-sm text-slate-500">{{ $cashier->email }}</p></div>
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700">{{ $cashier->role }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
