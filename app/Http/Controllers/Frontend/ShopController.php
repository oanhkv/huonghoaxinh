<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)->with('category');

        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $category = Category::where('slug', $categorySlug)->first();

            if ($category) {
                $categoryIds = [$category->id];
                $subIds = $category->children()->pluck('id')->toArray();
                $categoryIds = array_merge($categoryIds, $subIds);

                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('price_range')) {
            switch ($request->price_range) {
                case 'under_500':
                    $query->where('price', '<', 500000);
                    break;
                case '500_1000':
                    $query->whereBetween('price', [500000, 1000000]);
                    break;
                case 'over_1000':
                    $query->where('price', '>', 1000000);
                    break;
            }
        }

        // Lọc theo màu sắc — dùng JSON_SEARCH vì MySQL có thể lưu unicode escape (\uXXXX)
        // khiến whereJsonContains không match được chuỗi tiếng Việt.
        $selectedColors = collect((array) $request->input('colors', []))
            ->filter(fn ($c) => isset(Product::COLOR_OPTIONS[$c]))
            ->values();
        if ($selectedColors->isNotEmpty()) {
            $query->where(function ($q) use ($selectedColors) {
                foreach ($selectedColors as $color) {
                    $q->orWhereRaw("JSON_SEARCH(colors, 'one', ?) IS NOT NULL", [$color]);
                }
            });
        }

        // Lọc theo nguyên liệu / loại hoa
        $selectedMaterials = collect((array) $request->input('materials', []))
            ->filter(fn ($m) => in_array($m, Product::MATERIAL_OPTIONS, true))
            ->values();
        if ($selectedMaterials->isNotEmpty()) {
            $query->where(function ($q) use ($selectedMaterials) {
                foreach ($selectedMaterials as $material) {
                    $q->orWhereRaw("JSON_SEARCH(materials, 'one', ?) IS NOT NULL", [$material]);
                }
            });
        }

        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'featured':
                $query->where('is_featured', true)->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNull('parent_id')->with('children')->withCount('products')->get();

        return view('frontend.shop', [
            'products' => $products,
            'categories' => $categories,
            'colorOptions' => Product::COLOR_OPTIONS,
            'materialOptions' => Product::MATERIAL_OPTIONS,
            'selectedColors' => $selectedColors->all(),
            'selectedMaterials' => $selectedMaterials->all(),
        ]);
    }
}
