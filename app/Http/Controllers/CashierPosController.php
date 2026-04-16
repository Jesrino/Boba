<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CashierPosController extends Controller
{
    private const SIZE_PRICE_MAP = [
        'Small' => 0,
        'Medium' => 10,
        'Large' => 20,
    ];

    private const ADD_ON_PRICE_MAP = [
        'Pearls' => 20,
        'Crystal Boba' => 15,
        'Cheese Foam' => 30,
        'Grass Jelly' => 15,
        'Oat Milk' => 25,
    ];

    public function index(Request $request): View
    {
        $cashier = $request->user();

        $categories = Category::query()
            ->with(['products' => fn ($query) => $query->where('is_active', true)->orderBy('name')])
            ->orderBy('name')
            ->get();

        $recentTransactions = Transaction::query()
            ->with('items')
            ->whereBelongsTo($cashier, 'cashier')
            ->latest('paid_at')
            ->latest()
            ->limit(8)
            ->get();

        $todayTransactions = Transaction::query()
            ->whereBelongsTo($cashier, 'cashier')
            ->whereDate('paid_at', today());

        $ordersToday = (clone $todayTransactions)->count();
        $salesToday = (float) (clone $todayTransactions)->sum('total_amount');
        $averageTicket = $ordersToday > 0
            ? round($salesToday / $ordersToday, 2)
            : 0;

        return view('cashier.pos', [
            'user' => $cashier,
            'categories' => $categories,
            'products' => $categories->flatMap->products->values(),
            'recentTransactions' => $recentTransactions,
            'ordersToday' => $ordersToday,
            'salesToday' => $salesToday,
            'averageTicket' => $averageTicket,
            'paymentMethods' => ['cash', 'gcash'],
            'defaultSugarLevels' => ['0%', '25%', '50%', '75%', '100%'],
            'defaultIceLevels' => ['No Ice', 'Less Ice', 'Normal Ice', 'Extra Ice'],
            'defaultAddOnOptions' => array_map(
                fn (string $name, int $price) => ['name' => $name, 'price' => $price],
                array_keys(self::ADD_ON_PRICE_MAP),
                array_values(self::ADD_ON_PRICE_MAP),
            ),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cart' => ['required', 'string'],
            'payment_method' => ['required', 'in:cash,gcash'],
            'amount_received' => ['required', 'numeric', 'min:0'],
        ]);

        $cart = collect(json_decode($validated['cart'], true));

        abort_if($cart->isEmpty(), 422, 'Cart is empty.');

        $lineItems = $this->buildLineItems($cart);
        $subtotal = $lineItems->sum('line_total');
        $discountAmount = 0;
        $taxAmount = round($subtotal * 0.12, 2);
        $totalAmount = round($subtotal + $taxAmount - $discountAmount, 2);
        $amountReceived = round((float) $validated['amount_received'], 2);

        if ($amountReceived < $totalAmount) {
            return back()
                ->withInput()
                ->withErrors(['amount_received' => 'Amount received must cover the total amount.']);
        }

        $changeAmount = round($amountReceived - $totalAmount, 2);

        $transaction = DB::transaction(function () use (
            $request,
            $validated,
            $lineItems,
            $subtotal,
            $discountAmount,
            $taxAmount,
            $totalAmount,
            $amountReceived,
            $changeAmount
        ) {
            $transaction = Transaction::query()->create([
                'receipt_number' => $this->generateReceiptNumber(),
                'user_id' => $request->user()->id,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'amount_received' => $amountReceived,
                'change_amount' => $changeAmount,
                'payment_method' => $validated['payment_method'],
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            $transaction->items()->createMany(
                $lineItems->map(fn (array $item) => [
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'size' => $item['size'],
                    'sugar_level' => $item['sugar_level'],
                    'ice_level' => $item['ice_level'],
                    'add_ons' => $item['add_ons'],
                    'notes' => $item['notes'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['line_total'],
                ])->all()
            );

            return $transaction;
        });

        return redirect()->route('cashier.receipt.show', $transaction);
    }

    public function history(Request $request): View
    {
        $search = trim((string) $request->string('search'));

        $transactions = Transaction::query()
            ->with('items')
            ->whereBelongsTo($request->user(), 'cashier')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('receipt_number', 'like', "%{$search}%");
            })
            ->latest('paid_at')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('cashier.history', [
            'transactions' => $transactions,
            'search' => $search,
        ]);
    }

    public function showReceipt(Request $request, Transaction $transaction): View
    {
        abort_unless(
            $request->user()->isAdmin() || $transaction->user_id === $request->user()->id,
            403
        );

        $transaction->loadMissing('items', 'cashier');

        return view('cashier.receipt', [
            'transaction' => $transaction,
        ]);
    }

    private function buildLineItems(Collection $cart): Collection
    {
        return $cart->map(function (array $item): array {
            $product = Product::query()->findOrFail($item['product_id'] ?? null);

            $quantity = max(1, (int) ($item['quantity'] ?? 1));
            $size = (string) ($item['size'] ?? 'Medium');
            $sugarLevel = (string) ($item['sugar_level'] ?? '50%');
            $iceLevel = (string) ($item['ice_level'] ?? 'Normal Ice');
            $notes = trim((string) ($item['notes'] ?? ''));
            $addOns = collect($item['add_ons'] ?? [])
                ->map(fn ($addOn) => trim((string) $addOn))
                ->filter()
                ->values();

            $productAddOnPrices = collect($product->add_on_options ?: array_map(
                fn (string $name, int $price) => ['name' => $name, 'price' => $price],
                array_keys(self::ADD_ON_PRICE_MAP),
                array_values(self::ADD_ON_PRICE_MAP),
            ))->mapWithKeys(fn (array $addOn) => [
                (string) ($addOn['name'] ?? '') => (float) ($addOn['price'] ?? 0),
            ]);

            $unitPrice = (float) $product->base_price;
            $unitPrice += self::SIZE_PRICE_MAP[$size] ?? 0;
            $unitPrice += $addOns->sum(fn (string $addOn) => $productAddOnPrices[$addOn] ?? self::ADD_ON_PRICE_MAP[$addOn] ?? 0);

            return [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'size' => $size,
                'sugar_level' => $sugarLevel,
                'ice_level' => $iceLevel,
                'add_ons' => $addOns->all(),
                'notes' => $notes !== '' ? $notes : null,
                'quantity' => $quantity,
                'unit_price' => round($unitPrice, 2),
                'line_total' => round($unitPrice * $quantity, 2),
            ];
        });
    }

    private function generateReceiptNumber(): string
    {
        return 'R'.now()->format('YmdHis').random_int(10, 99);
    }
}
