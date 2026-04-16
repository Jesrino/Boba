<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    /**
     * @var list<string>
     */
    protected $appends = [
        'menu_image',
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'base_price',
        'available_sizes',
        'sugar_levels',
        'ice_levels',
        'add_on_options',
        'supports_customization',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_price' => 'decimal:2',
            'available_sizes' => 'array',
            'sugar_levels' => 'array',
            'ice_levels' => 'array',
            'add_on_options' => 'array',
            'supports_customization' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getMenuImageAttribute(): string
    {
        $name = strtolower($this->name);

        return match (true) {
            str_contains($name, 'fruit'),
            str_contains($name, 'lychee'),
            str_contains($name, 'peach'),
            str_contains($name, 'mango'),
            str_contains($name, 'passionfruit'),
            str_contains($name, 'lemon') => asset('images/menu/fruit-tea.svg'),
            str_contains($name, 'foam'),
            str_contains($name, 'cloud') => asset('images/menu/cheese-foam.svg'),
            str_contains($name, 'waffle'),
            str_contains($name, 'fries'),
            str_contains($name, 'chicken'),
            str_contains($name, 'nachos') => asset('images/menu/snacks.svg'),
            str_contains($name, 'thai'),
            str_contains($name, 'sesame'),
            str_contains($name, 'latte'),
            str_contains($name, 'coffee') => asset('images/menu/specialty.svg'),
            default => asset('images/menu/milk-tea.svg'),
        };
    }
}
