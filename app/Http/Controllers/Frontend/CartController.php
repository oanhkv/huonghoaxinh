<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Hiển thị trang giỏ hàng
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
                         ->with('product')
                         ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = 0; // Mặc định miễn phí
        $total = $subtotal + $shipping;

        return view('frontend.cart.index', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    // Thêm sản phẩm vào giỏ hàng
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'nullable|numeric|min:0',
                'variant' => 'nullable|string|max:255',
            ]);

            $product = Product::findOrFail($request->product_id);
            $price = $request->unit_price !== null ? $request->unit_price : $product->price;
            $variant = trim($request->variant ?? '');

            // Kiểm tra tồn kho tổng cho sản phẩm
            $existingQuantity = Cart::where('user_id', Auth::id())
                                    ->where('product_id', $request->product_id)
                                    ->sum('quantity');

            if ($existingQuantity + $request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá kho. Kho hiện có: ' . $product->stock
                ], 422);
            }

            // Kiểm tra xem cùng sản phẩm cùng giá/biến thể đã có trong giỏ hàng chưa
            $cartItem = Cart::where('user_id', Auth::id())
                            ->where('product_id', $request->product_id)
                            ->where('price', $price)
                            ->where('variant', $variant)
                            ->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Số lượng vượt quá kho'
                    ], 422);
                }

                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $price,
                    'variant' => $variant,
                ]);
            }

            $cartCount = Cart::where('user_id', Auth::id())->sum('quantity');

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng',
                'cart_count' => $cartCount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage()
            ], 500);
        }
    }

    // Cập nhật số lượng
    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền cập nhật'
            ], 403);
        }

        $otherQuantity = Cart::where('user_id', Auth::id())
                             ->where('product_id', $cart->product_id)
                             ->where('id', '!=', $cart->id)
                             ->sum('quantity');

        if ($otherQuantity + $request->quantity > $cart->product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá kho'
            ], 422);
        }

        $cart->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật số lượng thành công',
            'subtotal' => $cart->quantity * $cart->price
        ]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(Cart $cart)
    {
        if ($cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền xóa'
            ], 403);
        }

        $cart->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    // Xóa tất cả giỏ hàng
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tất cả sản phẩm trong giỏ hàng'
        ]);
    }

    // Lấy thông tin giỏ hàng (AJAX)
    public function getCart()
    {
        $cartItems = Cart::where('user_id', Auth::id())
                         ->with('product')
                         ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return response()->json([
            'items' => $cartItems,
            'count' => $cartItems->count(),
            'subtotal' => $subtotal,
            'total' => $subtotal
        ]);
    }

    // Áp dụng mã giảm giá
    public function applyVoucher(Request $request)
    {
        $request->validate([
            'voucher_code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->voucher_code)
                          ->where('is_active', true)
                          ->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không hợp lệ'
            ], 422);
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $discount = 0;
        if ($voucher->type === 'fixed') {
            $discount = $voucher->value;
        } elseif ($voucher->type === 'percentage') {
            $discount = ($subtotal * $voucher->value) / 100;
        }

        $total = max(0, $subtotal - $discount);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công',
            'discount' => $discount,
            'total' => $total
        ]);
    }
}
