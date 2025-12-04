<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\OrderItem;

class ShopController extends Controller
{
    // Halaman utama / catalog
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $query = Product::with('category')->latest();

        // Optional: filter kategori via query string ?category=slug
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Optional: pencarian nama produk ?q=keyword
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('name', 'LIKE', "%{$q}%");
        }

        $products = $query->paginate(12)->withQueryString();

        return view('shop.index', [
            'products'   => $products,
            'categories' => $categories,
            'currentCategorySlug' => $request->category,
            'search'     => $request->q,
        ]);
    }

    // Halaman produk detail
    public function show(Product $product)
    {
        $product->load(['category', 'reviews.user']);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->latest()
            ->take(4)
            ->get();

        $averageRating = $product->reviews->avg('rating');

        $userReview = null;
        $canReview  = false;

        if (auth()->check()) {
            $userReview = $product->reviews->firstWhere('user_id', auth()->id());

            $canReview = OrderItem::where('product_id', $product->id)
                ->whereHas('order', function ($q) {
                    $q->where('user_id', auth()->id())
                    ->where('status', 'completed');
                })
                ->exists();
        }

        return view('shop.show', compact(
            'product',
            'relatedProducts',
            'averageRating',
            'userReview',
            'canReview'
        ));
    }
}
