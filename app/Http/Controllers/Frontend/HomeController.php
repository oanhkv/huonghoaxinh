<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Wishlist;
use App\Models\ContactMessage;
use App\Models\Voucher;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
                                    ->where('is_active', true)
                                    ->take(8)
                                    ->get();

        $mainCategories = Category::whereNull('parent_id')
                                  ->with('children')
                                  ->get();

        // Get user wishlists if authenticated
        $userWishlists = Auth::check()
            ? Auth::user()->wishlists()
                ->with('product')
                ->take(8)
                ->get()
            : collect([]);

        $customerReviews = Review::query()
            ->where('is_visible', true)
            ->with(['user', 'product'])
            ->latest()
            ->take(8)
            ->get();

        return view('frontend.home', compact('featuredProducts', 'mainCategories', 'userWishlists', 'customerReviews'));
    }

    // Trang Giới thiệu về chúng tôi
    public function about()
    {
        return view('frontend.about');
    }

    public function contact()
    {
        $mainCategories = Category::whereNull('parent_id')
                                  ->with('children')
                                  ->get();

        return view('frontend.contact', compact('mainCategories'));
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        ContactMessage::create($request->only(['name', 'email', 'phone', 'subject', 'message']));

        return redirect()->route('contact')->with('success', 'Cảm ơn bạn! Chúng tôi đã nhận được yêu cầu liên hệ và sẽ phản hồi sớm.');
    }

    // Trang Mã giảm giá
    public function vouchers()
    {
        $vouchers = Voucher::where('is_active', true)
                           ->where('starts_at', '<=', now())
                           ->where('ends_at', '>=', now())
                           ->orderBy('created_at', 'desc')
                           ->paginate(12);

        $mainCategories = Category::whereNull('parent_id')
                                  ->with('children')
                                  ->get();

        return view('frontend.vouchers', compact('vouchers', 'mainCategories'));
    }
}