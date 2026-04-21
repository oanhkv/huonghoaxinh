<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:5|max:2000',
        ]);

        $user = Auth::user();
        $productId = (int) $request->product_id;

        if (! $this->userCanReviewProduct($user->id, $productId)) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm đã mua và đơn hàng đã hoàn tất (đã giao hoặc đã thanh toán).');
        }

        if (Review::where('user_id', $user->id)->where('product_id', $productId)->exists()) {
            return back()->with('error', 'Bạn đã gửi đánh giá cho sản phẩm này.');
        }

        Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'rating' => (int) $request->rating,
            'comment' => $request->comment,
            'is_visible' => true,
        ]);

        $product = Product::find($productId);

        return redirect()
            ->route('product.show', $product->slug)
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm.');
    }

    private function userCanReviewProduct(int $userId, int $productId): bool
    {
        return Order::where('user_id', $userId)
            ->whereIn('status', ['delivered', 'paid'])
            ->whereHas('orderItems', function ($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();
    }
}
