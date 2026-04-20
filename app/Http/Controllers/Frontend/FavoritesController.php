<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function index()
    {
        $favoriteItems = session('favorites', []);

        return view('frontend.favorites.index', compact('favoriteItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
        ]);

        $product = Product::where('id', $request->product_id)
            ->where('is_active', true)
            ->firstOrFail();

        $favorites = session()->get('favorites', []);

        if (isset($favorites[$product->id])) {
            return back()->with('success', 'Sản phẩm đã có trong yêu thích.');
        }

        $favorites[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'price' => $product->price,
            'image' => $product->image,
        ];

        session()->put('favorites', $favorites);

        return back()->with('success', 'Đã thêm sản phẩm vào danh sách yêu thích.');
    }

    public function destroy($productId)
    {
        $favorites = session('favorites', []);

        if (isset($favorites[$productId])) {
            unset($favorites[$productId]);

            if (count($favorites) > 0) {
                session()->put('favorites', $favorites);
            } else {
                session()->forget('favorites');
            }

            return back()->with('success', 'Đã xóa sản phẩm khỏi yêu thích.');
        }

        return back()->with('error', 'Sản phẩm không tồn tại trong yêu thích.');
    }
}
