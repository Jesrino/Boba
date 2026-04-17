<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(Request $request): View
    {
        $trendPeriod = $request->string('trend')->lower()->value() === 'month' ? 'month' : '7d';
        $trendEnd = $trendPeriod === 'month'
            ? now()->endOfMonth()
            : now()->endOfDay();
        $trendStart = $trendPeriod === 'month'
            ? now()->startOfYear()
            : now()->copy()->subDays(6)->startOfDay();

        $transactions = Transaction::query()->with('cashier')->latest('paid_at')->latest()->limit(6)->get();
        $salesTotal = (float) Transaction::query()->sum('total_amount');
        $todaySales = (float) Transaction::query()->whereDate('paid_at', today())->sum('total_amount');
        $weeklySales = (float) Transaction::query()->whereDate('paid_at', '>=', today()->subDays(6))->sum('total_amount');
        $averageTicket = (float) Transaction::query()->avg('total_amount');

        $trendTransactions = Transaction::query()
            ->whereBetween('paid_at', [$trendStart, $trendEnd])
            ->get(['user_id', 'paid_at', 'total_amount']);

        $trendSummaries = $trendTransactions
            ->groupBy(function (Transaction $transaction) use ($trendPeriod): string {
                return $trendPeriod === 'month'
                    ? $transaction->paid_at?->format('Y-m')
                    : $transaction->paid_at?->toDateString();
            })
            ->map(fn ($items) => [
                'sales' => round((float) $items->sum('total_amount'), 2),
                'orders' => $items->count(),
                'cashiers' => $items->pluck('user_id')->filter()->unique()->count(),
            ]);

        $trendDates = $trendPeriod === 'month'
            ? collect(range(1, (int) now()->month))->map(
                fn (int $month) => now()->copy()->startOfYear()->month($month)->startOfMonth()
            )
            : collect(CarbonPeriod::create($trendStart->copy()->startOfDay(), $trendEnd->copy()->startOfDay()));

        $salesTrend = $trendDates
            ->map(function (Carbon $date) use ($trendPeriod, $trendSummaries): array {
                $summaryKey = $trendPeriod === 'month'
                    ? $date->format('Y-m')
                    : $date->toDateString();

                $summary = $trendSummaries->get($summaryKey, [
                    'sales' => 0,
                    'orders' => 0,
                    'cashiers' => 0,
                ]);

                return [
                    'label' => $trendPeriod === 'month' ? $date->format('M') : $date->format('D'),
                    'date' => $trendPeriod === 'month' ? $date->format('Y') : $date->format('M d'),
                    'sales' => (float) $summary['sales'],
                    'orders' => (int) $summary['orders'],
                    'cashiers' => (int) $summary['cashiers'],
                ];
            })
            ->values();

        $trendSalesTotal = (float) $salesTrend->sum('sales');
        $trendOrderTotal = (int) $salesTrend->sum('orders');
        $trendActiveCashiers = $trendTransactions->pluck('user_id')->filter()->unique()->count();

        $topCashierInPeriod = Transaction::query()
            ->selectRaw('user_id, COUNT(*) as order_count, SUM(total_amount) as sales_total, MAX(paid_at) as latest_paid_at')
            ->with('cashier')
            ->whereBetween('paid_at', [$trendStart, $trendEnd])
            ->groupBy('user_id')
            ->orderByDesc('sales_total')
            ->first();

        $topProducts = TransactionItem::query()
            ->selectRaw('product_name, SUM(quantity) as quantity_sold, SUM(line_total) as gross_sales')
            ->groupBy('product_name')
            ->orderByDesc('quantity_sold')
            ->limit(5)
            ->get();

        $cashierPerformance = Transaction::query()
            ->selectRaw('user_id, COUNT(*) as order_count, SUM(total_amount) as sales_total, MAX(paid_at) as latest_paid_at')
            ->with('cashier')
            ->groupBy('user_id')
            ->orderByDesc('sales_total')
            ->limit(4)
            ->get();

        $paymentMix = collect(['cash', 'gcash'])->map(function (string $method) use ($salesTotal): array {
            $amount = (float) Transaction::query()->where('payment_method', $method)->sum('total_amount');

            return [
                'label' => strtoupper($method),
                'amount' => $amount,
                'share' => $salesTotal > 0 ? round(($amount / $salesTotal) * 100, 1) : 0,
            ];
        });

        $lowStockItems = InventoryItem::query()
            ->whereColumn('stock', '<=', 'reorder_level')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        return view('admin.dashboard', [
            'cashierCount' => User::query()->where('role', User::ROLE_CASHIER)->count(),
            'productCount' => Product::query()->count(),
            'categoryCount' => Category::query()->count(),
            'inventoryLowCount' => InventoryItem::query()->whereColumn('stock', '<=', 'reorder_level')->count(),
            'salesTotal' => $salesTotal,
            'todaySales' => $todaySales,
            'weeklySales' => $weeklySales,
            'averageTicket' => $averageTicket,
            'transactions' => $transactions,
            'trendPeriod' => $trendPeriod,
            'trendStart' => $trendStart,
            'trendEnd' => $trendEnd,
            'trendSalesTotal' => $trendSalesTotal,
            'trendOrderTotal' => $trendOrderTotal,
            'trendActiveCashiers' => $trendActiveCashiers,
            'topCashierInPeriod' => $topCashierInPeriod,
            'salesTrend' => $salesTrend,
            'salesTrendMax' => max(1, (float) $salesTrend->max('sales')),
            'topProducts' => $topProducts,
            'cashierPerformance' => $cashierPerformance,
            'paymentMix' => $paymentMix,
            'lowStockItems' => $lowStockItems,
        ]);
    }

    public function cashiers(): View
    {
        return view('admin.cashiers', [
            'cashiers' => User::query()->where('role', User::ROLE_CASHIER)->orderBy('name')->get(),
        ]);
    }

    public function products(Request $request): View
    {
        $editingProduct = null;

        if ($request->filled('edit')) {
            $editingProduct = Product::query()->findOrFail($request->integer('edit'));
        }

        return view('admin.products', [
            'products' => Product::query()->with('category')->orderBy('name')->get(),
            'categories' => Category::query()->withCount('products')->orderBy('name')->get(),
            'editingProduct' => $editingProduct,
            'productFormData' => $this->productFormData($request, $editingProduct),
        ]);
    }

    public function inventory(Request $request): View
    {
        $editingItem = null;

        if ($request->filled('edit')) {
            $editingItem = InventoryItem::query()->findOrFail($request->integer('edit'));
        }

        return view('admin.inventory', [
            'inventoryItems' => InventoryItem::query()->orderBy('name')->get(),
            'editingItem' => $editingItem,
            'inventoryFormData' => $this->inventoryFormData($request, $editingItem),
        ]);
    }

    public function sales(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $transactions = Transaction::query()
            ->with('cashier')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('receipt_number', 'like', "%{$search}%");
            })
            ->latest('paid_at')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.sales', [
            'transactions' => $transactions,
            'search' => $search,
        ]);
    }

    public function voidApprovals(): View
    {
        return view('admin.voids');
    }

    public function storeProduct(Request $request): RedirectResponse
    {
        Product::query()->create($this->validatedProductData($request));

        return redirect()
            ->route('admin.products.index')
            ->with('status', 'Menu item added.');
    }

    public function updateProduct(Request $request, Product $product): RedirectResponse
    {
        $product->update($this->validatedProductData($request));

        return redirect()
            ->route('admin.products.index', ['edit' => $product->id])
            ->with('status', 'Menu item updated.');
    }

    public function storeInventoryItem(Request $request): RedirectResponse
    {
        InventoryItem::query()->create($this->validatedInventoryItemData($request));

        return redirect()
            ->route('admin.inventory.index')
            ->with('status', 'Ingredient added.');
    }

    public function updateInventoryItem(Request $request, InventoryItem $inventoryItem): RedirectResponse
    {
        $inventoryItem->update($this->validatedInventoryItemData($request));

        return redirect()
            ->route('admin.inventory.index', ['edit' => $inventoryItem->id])
            ->with('status', 'Ingredient updated.');
    }

    private function validatedProductData(Request $request): array
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'base_price' => ['required', 'numeric', 'min:0'],
            'available_sizes' => ['required', 'string'],
            'sugar_levels' => ['nullable', 'string'],
            'ice_levels' => ['nullable', 'string'],
            'add_on_options' => ['nullable', 'string'],
        ]);

        $supportsCustomization = $request->boolean('supports_customization');

        return [
            'category_id' => (int) $validated['category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?: null,
            'base_price' => $validated['base_price'],
            'available_sizes' => $this->normalizeList($validated['available_sizes']),
            'sugar_levels' => $supportsCustomization ? $this->normalizeList($validated['sugar_levels'] ?? '') : [],
            'ice_levels' => $supportsCustomization ? $this->normalizeList($validated['ice_levels'] ?? '') : [],
            'add_on_options' => $supportsCustomization ? $this->normalizeAddOns($validated['add_on_options'] ?? '') : [],
            'supports_customization' => $supportsCustomization,
            'is_active' => $request->boolean('is_active'),
        ];
    }

    private function normalizeList(string $value): array
    {
        $normalized = collect(preg_split('/[\r\n,]+/', $value) ?: [])
            ->map(fn (string $item) => trim($item))
            ->filter()
            ->values()
            ->all();

        return $normalized !== [] ? $normalized : ['Regular'];
    }

    private function normalizeAddOns(string $value): array
    {
        return collect(preg_split('/[\r\n]+/', $value) ?: [])
            ->map(function (string $line): ?array {
                [$name, $price] = array_pad(explode(':', trim($line), 2), 2, null);

                $name = trim((string) $name);

                if ($name === '') {
                    return null;
                }

                return [
                    'name' => $name,
                    'price' => round((float) trim((string) ($price ?? 0)), 2),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    private function validatedInventoryItemData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'stock' => ['required', 'numeric', 'min:0'],
            'reorder_level' => ['required', 'numeric', 'min:0'],
        ]);
    }

    private function productFormData(Request $request, ?Product $product): array
    {
        $base = $product ? [
            'name' => $product->name,
            'category_id' => (string) $product->category_id,
            'base_price' => (string) $product->base_price,
            'description' => $product->description ?? '',
            'available_sizes' => implode(', ', $product->available_sizes ?? []),
            'sugar_levels' => implode(', ', $product->sugar_levels ?? []),
            'ice_levels' => implode(', ', $product->ice_levels ?? []),
            'add_on_options' => collect($product->add_on_options ?? [])
                ->map(fn (array $addOn) => ($addOn['name'] ?? '').':'.($addOn['price'] ?? 0))
                ->implode("\n"),
            'is_active' => (bool) $product->is_active,
            'supports_customization' => (bool) $product->supports_customization,
        ] : [
            'name' => '',
            'category_id' => (string) Category::query()->orderBy('name')->value('id'),
            'base_price' => '',
            'description' => '',
            'available_sizes' => 'Small, Medium, Large',
            'sugar_levels' => '0%, 25%, 50%, 75%, 100%',
            'ice_levels' => 'No Ice, Less Ice, Normal Ice, Extra Ice',
            'add_on_options' => "Pearls:20\nCrystal Boba:15\nCheese Foam:30",
            'is_active' => true,
            'supports_customization' => true,
        ];

        return [
            'name' => (string) old('name', $base['name']),
            'category_id' => (string) old('category_id', $base['category_id']),
            'base_price' => (string) old('base_price', $base['base_price']),
            'description' => (string) old('description', $base['description']),
            'available_sizes' => (string) old('available_sizes', $base['available_sizes']),
            'sugar_levels' => (string) old('sugar_levels', $base['sugar_levels']),
            'ice_levels' => (string) old('ice_levels', $base['ice_levels']),
            'add_on_options' => (string) old('add_on_options', $base['add_on_options']),
            'is_active' => (bool) old('is_active', $base['is_active']),
            'supports_customization' => (bool) old('supports_customization', $base['supports_customization']),
        ];
    }

    private function inventoryFormData(Request $request, ?InventoryItem $inventoryItem): array
    {
        $base = $inventoryItem ? [
            'name' => $inventoryItem->name,
            'unit' => $inventoryItem->unit,
            'stock' => (string) $inventoryItem->stock,
            'reorder_level' => (string) $inventoryItem->reorder_level,
        ] : [
            'name' => '',
            'unit' => '',
            'stock' => '',
            'reorder_level' => '',
        ];

        return [
            'name' => (string) old('name', $base['name']),
            'unit' => (string) old('unit', $base['unit']),
            'stock' => (string) old('stock', $base['stock']),
            'reorder_level' => (string) old('reorder_level', $base['reorder_level']),
        ];
    }
}
