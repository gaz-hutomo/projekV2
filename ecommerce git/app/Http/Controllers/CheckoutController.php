<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected function getCartOrNull(): ?Cart
    {
        $cartId = session('cart_id');

        if (!$cartId) {
            return null;
        }

        $cart = Cart::with('items.product')->find($cartId);

        if (!$cart) {
            return null;
        }

        // Pastikan cart terhubung ke user login
        if (Auth::check() && !$cart->user_id) {
            $cart->user_id = Auth::id();
            $cart->save();
        }

        return $cart;
    }

    public function index()
    {
        $user = Auth::user();
        $cart = $this->getCartOrNull();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        $total = $cart->calculateTotal();

        return view('checkout.index', compact('cart', 'total', 'user'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $cart = $this->getCartOrNull();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Keranjang kamu masih kosong.');
        }

        $data = $request->validate([
            'shipping_address' => ['required', 'string'],
            'payment_method'   => ['required', 'string', 'max:50'],
        ]);

        $total = $cart->calculateTotal();

        if ($total <= 0) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'Total belanja tidak valid.');
        }

        $order = null;

        DB::transaction(function () use ($user, $cart, $data, $total, &$order) {
            $orderNumber = 'ORD-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

            // 1. Buat order
            $order = Order::create([
                'order_number'     => $orderNumber,
                'user_id'          => $user->id,
                'total_amount'     => $total,
                'status'           => 'pending',
                'shipping_address' => $data['shipping_address'],
                'payment_method'   => $data['payment_method'],
                'tracking_number'  => null,
            ]);

            // 2. Order items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id'  => $order->id,
                    'product_id'=> $item->product_id,
                    'quantity'  => $item->quantity,
                    'price'     => $item->price_at_add,
                ]);
            }

            // 3. Payment record (pending)
            Payment::create([
                'order_id'       => $order->id,
                'payment_method' => $data['payment_method'],
                'payment_status' => 'pending',
                'amount'         => $total,
                'transaction_ref'=> null,
                'paid_at'        => null,
            ]);

            // 4. Update alamat user (opsional)
            $user->address = $data['shipping_address'];
            $user->save();

            // 5. Kosongkan cart
            $cart->items()->delete();
        });

        return redirect()
            ->route('account.orders.show', $order->id)
            ->with('success', 'Pesanan berhasil dibuat. Terima kasih!');
    }
}
