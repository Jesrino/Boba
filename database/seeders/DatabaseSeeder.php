<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\InventoryItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@pos.test'],
            [
                'name' => 'POS Admin',
                'role' => User::ROLE_ADMIN,
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        User::query()->updateOrCreate(
            ['email' => 'cashier@pos.test'],
            [
                'name' => 'POS Cashier',
                'role' => User::ROLE_CASHIER,
                'password' => 'password',
                'email_verified_at' => now(),
            ],
        );

        collect([
            'Milk Tea' => [
                ['name' => 'Brown Sugar Boba', 'price' => 75, 'description' => 'Rich brown sugar milk tea with tapioca pearls.'],
                ['name' => 'Taro Milk Tea', 'price' => 80, 'description' => 'Creamy taro classic.'],
                ['name' => 'Jasmine Milk Tea', 'price' => 70, 'description' => 'Floral tea with fresh milk.'],
                ['name' => 'Okinawa Milk Tea', 'price' => 82, 'description' => 'Deep caramel milk tea with roasted notes.'],
                ['name' => 'Wintermelon Milk Tea', 'price' => 78, 'description' => 'Smooth wintermelon sweetness with tea.'],
                ['name' => 'Matcha Milk Tea', 'price' => 90, 'description' => 'Earthy matcha with creamy milk tea base.'],
            ],
            'Fruit Tea' => [
                ['name' => 'Mango Fruit Tea', 'price' => 85, 'description' => 'Bright tropical tea.'],
                ['name' => 'Passionfruit Tea', 'price' => 85, 'description' => 'Tangy and refreshing.'],
                ['name' => 'Lychee Fruit Tea', 'price' => 88, 'description' => 'Floral lychee tea over ice.'],
                ['name' => 'Peach Fruit Tea', 'price' => 88, 'description' => 'Light peach tea with a clean finish.'],
                ['name' => 'Wintermelon Lemon Tea', 'price' => 86, 'description' => 'Citrus kick with wintermelon tea.'],
            ],
            'Cheese Foam' => [
                ['name' => 'Strawberry Cloud', 'price' => 95, 'description' => 'Strawberry tea finished with cream foam.'],
                ['name' => 'Matcha Cheese Foam', 'price' => 98, 'description' => 'Matcha tea topped with salted cream foam.'],
                ['name' => 'Oolong Cheese Foam', 'price' => 94, 'description' => 'Roasted oolong with creamy foam.'],
            ],
            'Specialty' => [
                ['name' => 'Thai Milk Tea', 'price' => 92, 'description' => 'Bold Thai tea with a silky finish.'],
                ['name' => 'Black Sesame Latte', 'price' => 105, 'description' => 'Nutty black sesame drink with milk.'],
                ['name' => 'Coffee Jelly Latte', 'price' => 99, 'description' => 'Creamy latte with coffee jelly.'],
            ],
            'Snacks' => [
                ['name' => 'Waffle Bites', 'price' => 55, 'description' => 'Warm waffle bites for easy upsell.'],
                ['name' => 'Fries', 'price' => 65, 'description' => 'Crispy fries for snack combos.'],
                ['name' => 'Chicken Pops', 'price' => 89, 'description' => 'Crispy chicken bites for combo meals.'],
                ['name' => 'Nachos', 'price' => 95, 'description' => 'Loaded nachos for sharing.'],
            ],
        ])->each(function (array $products, string $categoryName): void {
            $category = Category::query()->updateOrCreate(
                ['slug' => Str::slug($categoryName)],
                ['name' => $categoryName],
            );

            foreach ($products as $product) {
                Product::query()->updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $product['name'],
                    ],
                    [
                        'description' => $product['description'],
                        'base_price' => $product['price'],
                        'available_sizes' => $categoryName === 'Snacks' ? ['Regular'] : ['Small', 'Medium', 'Large'],
                        'sugar_levels' => $categoryName === 'Snacks' ? [] : ['0%', '25%', '50%', '75%', '100%'],
                        'ice_levels' => $categoryName === 'Snacks' ? [] : ['No Ice', 'Less Ice', 'Normal Ice', 'Extra Ice'],
                        'add_on_options' => $categoryName === 'Snacks'
                            ? []
                            : [
                                ['name' => 'Pearls', 'price' => 20],
                                ['name' => 'Crystal Boba', 'price' => 15],
                                ['name' => 'Cheese Foam', 'price' => 30],
                                ['name' => 'Grass Jelly', 'price' => 15],
                                ['name' => 'Oat Milk', 'price' => 25],
                            ],
                        'supports_customization' => $categoryName !== 'Snacks',
                        'is_active' => true,
                    ],
                );
            }
        });

        collect([
            ['name' => 'Brown Sugar Pearls', 'unit' => 'kg', 'stock' => 4, 'reorder_level' => 6],
            ['name' => 'Oat Milk', 'unit' => 'liters', 'stock' => 8, 'reorder_level' => 10],
            ['name' => 'Matcha Powder', 'unit' => 'kg', 'stock' => 2, 'reorder_level' => 3],
            ['name' => 'Jasmine Tea', 'unit' => 'kg', 'stock' => 7, 'reorder_level' => 4],
        ])->each(function (array $item): void {
            InventoryItem::query()->updateOrCreate(
                ['name' => $item['name']],
                $item,
            );
        });
    }
}
