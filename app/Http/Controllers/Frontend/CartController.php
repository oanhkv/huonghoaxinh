<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = session('cart', []);

        $cartTotal = collect($cartItems)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('frontend.cart.index', compact('cartItems', 'cartTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'size_label' => 'nullable|string|max:255',
            'size_price' => 'nullable|numeric|min:0',
        ]);

        $product = Product::where('id', $request->product_id)
            ->where('is_active', true)
            ->firstOrFail();

        if ($product->stock <= 0) {
            return back()->with('error', 'Sản phẩm hiện không còn hàng.');
        }

        $sizeLabel = $request->input('size_label', 'Standard');
        $sizePrice = $request->input('size_price', $product->price);
        $price = is_numeric($sizePrice) ? round($sizePrice) : $product->price;

        $cart = session()->get('cart', []);
        $cartKey = $product->id . '|' . $sizeLabel;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += 1;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $price,
                'size' => $sizeLabel,
                'quantity' => 1,
                'image' => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Sản phẩm đã được thêm vào giỏ hàng.');
    }

    public function update(Request $request, $cartKey)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);

        if (! isset($cart[$cartKey])) {
            return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
        }

        $parts = explode('|', $cartKey, 2);
        $productId = $parts[0];

        $product = Product::find($productId);
        if (! $product || ! $product->is_active) {
            return back()->with('error', 'Sản phẩm không hợp lệ.');
        }

        $quantity = (int) $request->quantity;
        if ($quantity > $product->stock) {
            return back()->with('error', 'Số lượng yêu cầu vượt quá tồn kho.');
        }

        $cart[$cartKey]['quantity'] = $quantity;
        session()->put('cart', $cart);

        return back()->with('success', 'Số lượng giỏ hàng đã được cập nhật.');
    }

    public function destroy($cartKey)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$cartKey])) {
            unset($cart[$cartKey]);
            if (count($cart) > 0) {
                session()->put('cart', $cart);
            } else {
                session()->forget('cart');
            }

            return back()->with('success', 'Sản phẩm đã được xóa khỏi giỏ hàng.');
        }

        return back()->with('error', 'Sản phẩm không tồn tại trong giỏ hàng.');
    }
}
