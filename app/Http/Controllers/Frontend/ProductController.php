<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                          ->where('is_active', true)
                          ->with(['category', 'visibleReviews.user'])
                          ->firstOrFail();

        // Lấy sản phẩm liên quan (cùng danh mục)
        $relatedProducts = Product::where('category_id', $product->category_id)
                                  ->where('id', '!=', $product->id)
                                  ->where('is_active', true)
                                  ->take(4)
                                  ->get();

        $canReview = false;
        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if (! $userReview) {
                $canReview = Order::where('user_id', Auth::id())
                    ->whereIn('status', ['delivered', 'paid'])
                    ->whereHas('orderItems', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();
            }
        }

        return view('frontend.product.show', compact('product', 'relatedProducts', 'canReview', 'userReview'));
    }
}