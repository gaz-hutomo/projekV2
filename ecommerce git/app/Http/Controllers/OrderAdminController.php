<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'payment'])
            ->latest()
            ->paginate(15);

        return view('dashboard.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items.product', 'payment']);

        return view('dashboard.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['user', 'payment']);

        // daftar status yang boleh dipilih
        $orderStatuses = ['pending', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed', 'refunded'];

        return view('dashboard.orders.edit', compact('order', 'orderStatuses', 'paymentStatuses'));
    }

    public function update(Request $request, Order $order)
    {
        $order->load('payment');

        $data = $request->validate([
            'status'           => ['required', 'string', 'max:50'],
            'tracking_number'  => ['nullable', 'string', 'max:255'],
            'payment_status'   => ['nullable', 'string', 'max:50'],
        ]);

        // 1. Update status order & tracking_number
        $order->status = $data['status'];
        $order->tracking_number = $data['tracking_number'] ?? null;
        $order->save();

        // 2. Update status pembayaran jika ada
        if ($order->payment && isset($data['payment_status'])) {
            $order->payment->payment_status = $data['payment_status'];

            // kalau status jadi 'paid', set paid_at kalau belum ada
            if ($data['payment_status'] === 'paid' && !$order->payment->paid_at) {
                $order->payment->paid_at = now();
            }

            // kalau bukan 'paid', bisa set paid_at null (opsional)
            if ($data['payment_status'] !== 'paid') {
                $order->payment->paid_at = null;
            }

            $order->payment->save();
        }

        return redirect()
            ->route('dashboard.orders.edit', $order)
            ->with('success', 'Status pesanan & pembayaran berhasil diperbarui.');
    }
}
