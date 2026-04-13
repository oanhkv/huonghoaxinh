<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::where('is_active', true)->paginate(12);
        $categories = Category::all();

        return view('frontend.shop', compact('products', 'categories'));
    }
}