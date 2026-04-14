<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

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

        return view('frontend.home', compact('featuredProducts', 'mainCategories'));
    }

    // Trang Giới thiệu về chúng tôi
    public function about()
    {
        return view('frontend.about');
    }
}