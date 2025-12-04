<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'description',
        'category_id',
        'image_path',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Contoh implementasi sederhana
    public function getDiscountedPrice(float $percent = 0): float
    {
        if ($percent <= 0) {
            return (float) $this->price;
        }

        $discount = ($percent / 100) * $this->price;

        return (float) max($this->price - $discount, 0);
    }
}
