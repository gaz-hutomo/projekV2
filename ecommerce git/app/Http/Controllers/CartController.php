<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    protected function getOrCreateCart(): Cart
    {
        $cartId = session('cart_id');

        if ($cartId) {
            $cart = Cart::with('items.product')->find($cartId);
            if ($cart) {
                // jika user login dan cart belum punya user_id, isi
                if (Auth::check() && !$cart->user_id) {
                    $cart->user_id = Auth::id();
                    $cart->save();
                }

                return $cart;
            }
        }

        // buat cart baru
        $cart = Cart::create([
            'user_id' => Auth::id() ?: null,
        ]);

        session(['cart_id' => $cart->id]);

        return $cart->load('items.product');
    }

    public function index()
    {
        $cart = $this->getOrCreateCart();
        $cart->load('items.product');

        $total = $cart->calculateTotal();

        return view('cart.index', compact('cart', 'total'));
    }

    public function add(Request $request, Product $product)
    {
        $qty = (int) $request->input('qty', 1);
        $qty = max(1, $qty);

        $cart = $this->getOrCreateCart();
        $cart->addItem($product, $qty);

        return redirect()
            ->route('cart.index')
            ->with('success', 'Produk berhasil ditambahkan ke keranjang.');
    }

    public function update(Request $request, CartItem $item)
    {
        $cart = $this->getOrCreateCart();

        // pastikan item milik cart sekarang
        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $qty = (int) $request->input('qty', 1);
        $qty = max(0, $qty);

        if ($qty === 0) {
            $item->delete();
        } else {
            $item->quantity = $qty;
            $item->save();
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Keranjang berhasil diperbarui.');
    }

    public function remove(CartItem $item)
    {
        $cart = $this->getOrCreateCart();

        if ($item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function clear()
    {
        $cart = $this->getOrCreateCart();
        $cart->items()->delete();

        return redirect()
            ->route('cart.index')
            ->with('success', 'Keranjang dikosongkan.');
    }
}
