<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function storeFromOrder(Request $request, Order $order, OrderItem $item)
    {
        $user = Auth::user();

        // Pastikan order milik user yang login
        if ($order->user_id !== $user->id) {
            abort(403);
        }

        // Pastikan item memang milik order ini
        if ($item->order_id !== $order->id) {
            abort(403);
        }

        // Hanya boleh review jika order sudah selesai (completed)
        if ($order->status !== 'completed') {
            return back()->with('error', 'Kamu hanya bisa mereview produk dari pesanan yang sudah selesai.');
        }

        // Pastikan produknya ada
        if (!$item->product) {
            return back()->with('error', 'Produk tidak tersedia untuk direview.');
        }

        $data = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        // Satu user hanya satu review per produk (boleh update)
        $review = Review::firstOrNew([
            'user_id'    => $user->id,
            'product_id' => $item->product_id,
        ]);

        $review->rating  = $data['rating'];
        $review->comment = $data['comment'] ?? null;
        $review->save();

        return back()->with('success', 'Review untuk produk "' . $item->product->name . '" berhasil disimpan.');
    }

    // public function store(Request $request, Product $product)
    // {
    //     $user = Auth::user();

    //     // Pastikan user pernah beli produk ini dengan status order "completed"
    //     $hasPurchased = OrderItem::where('product_id', $product->id)
    //         ->whereHas('order', function ($q) use ($user) {
    //             $q->where('user_id', $user->id)
    //               ->where('status', 'completed');
    //         })
    //         ->exists();

    //     if (! $hasPurchased) {
    //         return back()->with('error', 'Kamu hanya bisa mereview produk yang sudah kamu beli.');
    //     }

    //     $data = $request->validate([
    //         'rating'  => ['required', 'integer', 'min:1', 'max:5'],
    //         'comment' => ['nullable', 'string'],
    //     ]);

    //     // 1 user hanya 1 review per produk – kalau sudah ada, update
    //     $review = Review::firstOrNew([
    //         'user_id'    => $user->id,
    //         'product_id' => $product->id,
    //     ]);

    //     $review->rating  = $data['rating'];
    //     $review->comment = $data['comment'] ?? null;
    //     $review->save();

    //     return back()->with('success', 'Review berhasil disimpan.');
    // }

    public function destroy(Review $review)
    {
        $user = Auth::user();

        if ($review->user_id !== $user->id) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }
}
