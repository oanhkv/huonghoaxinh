<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\WebsiteSetting;

class HomeController extends Controller
{
    public function index()
    {
        $limit = (int) WebsiteSetting::getValue('featured_products_limit', 8);
        $limit = max(4, min(24, $limit));

        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->take($limit)
            ->get();

        $mainCategories = Category::whereNull('parent_id')
            ->with('children')
            ->get();

        return view('frontend.home', compact('featuredProducts', 'mainCategories'));
    }
}