<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'user'])->latest();

        // Filter rating (opsional: ?rating=5)
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Pencarian sederhana (produk / user / komentar)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('product', function ($p) use ($q) {
                        $p->where('name', 'like', "%{$q}%");
                    })
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                          ->orWhere('email', 'like', "%{$q}%");
                    })
                    ->orWhere('comment', 'like', "%{$q}%");
            });
        }

        $reviews = $query->paginate(20)->withQueryString();

        return view('dashboard.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('dashboard.reviews.index')
            ->with('success', 'Review berhasil dihapus.');
    }
}
