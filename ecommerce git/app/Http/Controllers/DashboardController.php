<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik sederhana
        $totalUsers     = User::count();
        $totalProducts  = Product::count();
        $totalOrders    = Order::count();
        $totalRevenue   = Order::sum('total_amount');
        $totalCategories = Category::count();

        // 5 order terbaru
        $latestOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'totalCategories',
            'latestOrders'
        ));
    }
}
