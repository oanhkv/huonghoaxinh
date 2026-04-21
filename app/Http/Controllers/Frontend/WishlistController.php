<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display wishlist page
     */
    public function index()
    {
        $wishlists = Auth::user()->wishlists()->with('product')->paginate(12);

        return view('frontend.wishlist.index', [
            'wishlists' => $wishlists,
        ]);
    }

    /**
     * Add product to wishlist via AJAX
     */
    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $product = Product::findOrFail($request->product_id);

            // Check if already in wishlist
            $exists = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sản phẩm đã có trong yêu thích',
                ], 400);
            }

            // Add to wishlist
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã thêm vào yêu thích ❤️',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove product from wishlist via AJAX
     */
    public function remove(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa khỏi yêu thích',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Toggle wishlist status
     */
    public function toggle(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
            ]);

            $exists = Wishlist::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($exists) {
                $exists->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Đã xóa khỏi yêu thích',
                    'action' => 'removed',
                ]);
            } else {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Đã thêm vào yêu thích ❤️',
                    'action' => 'added',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check if product is in wishlist
     */
    public function isInWishlist($productId)
    {
        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'in_wishlist' => $exists,
        ]);
    }
}
