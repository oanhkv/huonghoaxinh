<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\Review;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = collect();
        if (Schema::hasTable('products')) {
            $featuredProducts = Product::where('is_featured', true)
                ->where('is_active', true)
                ->take(8)
                ->get();
        }

        $mainCategories = collect();
        if (Schema::hasTable('categories')) {
            $mainCategories = Category::whereNull('parent_id')
                ->with('children')
                ->withCount(['products' => function ($q) {
                    $q->where('is_active', true);
                }])
                ->get();
        }

        // Get user wishlists if authenticated
        $userWishlists = collect();
        if (Auth::check() && Schema::hasTable('wishlists')) {
            $userWishlists = Auth::user()->wishlists()
                ->with('product')
                ->take(8)
                ->get();
        }

        $customerReviews = collect();
        if (Schema::hasTable('reviews')) {
            $customerReviews = Review::query()
                ->where('is_visible', true)
                ->with(['user', 'product'])
                ->latest()
                ->take(8)
                ->get();
        }

        $blogPosts = collect();
        if (Schema::hasTable('blog_posts')) {
            $blogPosts = BlogPost::query()
                ->where('is_active', true)
                ->with('category')
                ->orderByDesc('published_at')
                ->take(8)
                ->get();
        }

        return view('frontend.home', compact('featuredProducts', 'mainCategories', 'userWishlists', 'customerReviews', 'blogPosts'));
    }

    // Trang Giới thiệu về chúng tôi
    public function about()
    {
        return view('frontend.about');
    }

    /**
     * "Liên hệ" cũ → chuyển hướng tới chat trực tiếp.
     * Chưa login thì chuyển sang trang đăng nhập, rồi quay lại chat.
     */
    public function contact()
    {
        if (Auth::check()) {
            return redirect()->route('account.contact.index');
        }
        return redirect()->route('login', ['intended' => 'chat']);
    }

    // Trang Mã giảm giá
    public function vouchers()
    {
        $vouchers = Schema::hasTable('vouchers')
            ? Voucher::where('is_active', true)
                ->where('starts_at', '<=', now())
                ->where('ends_at', '>=', now())
                ->orderBy('created_at', 'desc')
                ->paginate(12)
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12);

        $mainCategories = collect();
        if (Schema::hasTable('categories')) {
            $mainCategories = Category::whereNull('parent_id')
                ->with('children')
                ->get();
        }

        return view('frontend.vouchers', compact('vouchers', 'mainCategories'));
    }
}
