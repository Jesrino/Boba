<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierPosTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_can_access_pos_but_not_admin_dashboard(): void
    {
        $cashier = User::factory()->create([
            'role' => User::ROLE_CASHIER,
        ]);

        $this->actingAs($cashier)
            ->get(route('cashier.pos'))
            ->assertOk();

        $this->actingAs($cashier)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_dashboard_but_not_cashier_pos(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk();

        $this->actingAs($admin)
            ->get(route('cashier.pos'))
            ->assertForbidden();
    }

    public function test_cashier_can_complete_checkout_and_create_receipt(): void
    {
        $cashier = User::factory()->create([
            'role' => User::ROLE_CASHIER,
        ]);

        $category = Category::query()->create([
            'name' => 'Milk Tea',
            'slug' => 'milk-tea',
        ]);

        $product = Product::query()->create([
            'category_id' => $category->id,
            'name' => 'Taro Milk Tea',
            'description' => 'Classic taro drink.',
            'base_price' => 80,
            'available_sizes' => ['Small', 'Medium', 'Large'],
            'is_active' => true,
        ]);

        $cart = [[
            'product_id' => $product->id,
            'product_name' => $product->name,
            'size' => 'Large',
            'sugar_level' => '50%',
            'ice_level' => 'Less Ice',
            'add_ons' => ['Pearls'],
            'notes' => 'Less sweet',
            'quantity' => 2,
        ]];

        $response = $this->actingAs($cashier)->post(route('cashier.checkout'), [
            'cart' => json_encode($cart, JSON_THROW_ON_ERROR),
            'payment_method' => 'cash',
            'amount_received' => 300,
        ]);

        $transaction = Transaction::query()->first();

        $this->assertDatabaseCount('transactions', 1);
        $this->assertDatabaseCount('transaction_items', 1);
        $this->assertNotNull($transaction);
        $response->assertRedirect(route('cashier.receipt.show', $transaction));

        $item = TransactionItem::query()->first();

        $this->assertSame('Taro Milk Tea', $item?->product_name);
        $this->assertSame(2, $item?->quantity);
        $this->assertSame('cash', $transaction?->payment_method);
    }
}
