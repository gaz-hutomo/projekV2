<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['required', 'string', 'max:100', 'unique:products,sku'],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image'       => ['nullable', 'image', 'max:2048'], // max 2MB
        ]);

        if ($request->hasFile('image')) {
            // Disimpan di storage/app/public/products
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        Product::create($data);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'sku'         => ['required', 'string', 'max:100', 'unique:products,sku,' . $product->id],
            'price'       => ['required', 'numeric', 'min:0'],
            'stock'       => ['required', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'image'       => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            // Hapus file lama jika ada
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
            }

            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = $path;
        }

        $product->update($data);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
