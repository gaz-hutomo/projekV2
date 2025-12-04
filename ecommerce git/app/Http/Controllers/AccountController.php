<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;

class AccountController extends Controller
{
    public function profile()
    {
        $user = Auth::user();

        return view('account.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'   => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string'],
        ]);

        $user->update($data);

        return redirect()
            ->route('account.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    public function orders()
    {
        $user = Auth::user();

        $orders = Order::with('payment')
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('account.orders', compact('orders'));
    }

    public function orderShow($orderId)
    {
        $user = Auth::user();

        $order = Order::with(['items.product', 'payment'])
            ->where('user_id', $user->id)
            ->findOrFail($orderId);

        // Ambil semua review user ini untuk produk yang ada di order ini
        $productIds = $order->items->pluck('product_id')->filter()->unique();

        $userReviews = Review::where('user_id', $user->id)
            ->whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id'); // [product_id => Review]

        return view('account.order_show', compact('order', 'userReviews'));
    }

}
