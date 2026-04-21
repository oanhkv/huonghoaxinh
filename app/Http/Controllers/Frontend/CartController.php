<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Hiển thị trang giỏ hàng
    public function index()
    {
        $cartItems = $this->getCartItems();

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

            $existingQuantity = $this->getExistingQuantity((int) $request->product_id);

            if ($existingQuantity + $request->quantity > $product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá kho. Kho hiện có: ' . $product->stock
                ], 422);
            }

            if (Auth::check()) {
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
            } else {
                $guestCart = Session::get('guest_cart', []);
                $matchedIndex = null;

                foreach ($guestCart as $index => $item) {
                    if ((int) ($item['product_id'] ?? 0) === (int) $request->product_id
                        && (float) ($item['price'] ?? 0) === (float) $price
                        && (($item['variant'] ?? '') === $variant)
                    ) {
                        $matchedIndex = $index;
                        break;
                    }
                }

                if ($matchedIndex !== null) {
                    $newQuantity = ((int) $guestCart[$matchedIndex]['quantity']) + (int) $request->quantity;
                    if ($product->stock < $newQuantity) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Số lượng vượt quá kho'
                        ], 422);
                    }

                    $guestCart[$matchedIndex]['quantity'] = $newQuantity;
                } else {
                    $guestCart[] = [
                        'id' => (string) Str::uuid(),
                        'product_id' => (int) $request->product_id,
                        'quantity' => (int) $request->quantity,
                        'price' => (float) $price,
                        'variant' => $variant,
                    ];
                }

                Session::put('guest_cart', $guestCart);
            }

            $cartCount = $this->getCartItems()->sum('quantity');

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
    public function update(Request $request, string $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        if (Auth::check()) {
            $cartItem = Cart::where('id', $cart)
                ->where('user_id', Auth::id())
                ->with('product')
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quyền cập nhật'
                ], 403);
            }

            $otherQuantity = Cart::where('user_id', Auth::id())
                ->where('product_id', $cartItem->product_id)
                ->where('id', '!=', $cartItem->id)
                ->sum('quantity');

            if ($otherQuantity + $request->quantity > $cartItem->product->stock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng vượt quá kho'
                ], 422);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật số lượng thành công',
                'subtotal' => $cartItem->quantity * $cartItem->price
            ]);
        }

        $guestCart = Session::get('guest_cart', []);
        $itemIndex = collect($guestCart)->search(fn ($item) => ($item['id'] ?? '') === $cart);

        if ($itemIndex === false) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm trong giỏ'
            ], 404);
        }

        $cartItem = $guestCart[$itemIndex];
        $product = Product::find($cartItem['product_id']);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sản phẩm không còn tồn tại'
            ], 422);
        }

        $otherQuantity = collect($guestCart)
            ->reject(fn ($item) => ($item['id'] ?? '') === $cart)
            ->where('product_id', $cartItem['product_id'])
            ->sum('quantity');

        if ($otherQuantity + $request->quantity > $product->stock) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng vượt quá kho'
            ], 422);
        }

        $guestCart[$itemIndex]['quantity'] = (int) $request->quantity;
        Session::put('guest_cart', $guestCart);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật số lượng thành công',
            'subtotal' => ((int) $request->quantity) * ((float) $cartItem['price'])
        ]);
    }

    // Xóa sản phẩm khỏi giỏ hàng
    public function remove(string $cart)
    {
        if (Auth::check()) {
            $cartItem = Cart::where('id', $cart)
                ->where('user_id', Auth::id())
                ->first();

            if (!$cartItem) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quyền xóa'
                ], 403);
            }

            $cartItem->delete();
        } else {
            $guestCart = Session::get('guest_cart', []);
            $filtered = collect($guestCart)->reject(fn ($item) => ($item['id'] ?? '') === $cart)->values()->all();
            Session::put('guest_cart', $filtered);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng'
        ]);
    }

    // Xóa tất cả giỏ hàng
    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Session::forget('guest_cart');
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa tất cả sản phẩm trong giỏ hàng'
        ]);
    }

    // Lấy thông tin giỏ hàng (AJAX)
    public function getCart()
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $quantitySum = (int) $cartItems->sum(function ($item) {
            return (int) $item->quantity;
        });

        return response()->json([
            'items' => $cartItems->values(),
            'count' => $cartItems->count(),
            'quantity_sum' => $quantitySum,
            'subtotal' => $subtotal,
            'total' => $subtotal,
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

        $cartItems = $this->getCartItems();
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

    private function getCartItems()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with('product')->get();
        }

        $guestCart = Session::get('guest_cart', []);
        $productIds = collect($guestCart)->pluck('product_id')->unique()->values();
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return collect($guestCart)->map(function ($item) use ($products) {
            $product = $products->get((int) ($item['product_id'] ?? 0));
            if (!$product) {
                return null;
            }

            return (object) [
                'id' => (string) ($item['id'] ?? Str::uuid()),
                'user_id' => null,
                'product_id' => (int) $item['product_id'],
                'quantity' => (int) $item['quantity'],
                'price' => (float) $item['price'],
                'variant' => (string) ($item['variant'] ?? ''),
                'product' => $product,
            ];
        })->filter()->values();
    }

    private function getExistingQuantity(int $productId): int
    {
        if (Auth::check()) {
            return (int) Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->sum('quantity');
        }

        return (int) collect(Session::get('guest_cart', []))
            ->where('product_id', $productId)
            ->sum('quantity');
    }
}
