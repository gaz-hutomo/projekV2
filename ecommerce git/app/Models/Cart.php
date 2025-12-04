<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    public function addItem(Product $product, int $qty = 1)
    {
        $qty = max(1, $qty);

        $item = $this->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->quantity += $qty;
            $item->save();
        } else {
            $this->items()->create([
                'product_id'   => $product->id,
                'quantity'     => $qty,
                'price_at_add' => $product->price,
            ]);
        }

        return $this;
    }

    public function removeItem(int $productId)
    {
        $this->items()->where('product_id', $productId)->delete();

        return $this;
    }

    public function calculateTotal(): float
    {
        return (float) $this->items->sum(function ($item) {
            return $item->price_at_add * $item->quantity;
        });
    }
}
