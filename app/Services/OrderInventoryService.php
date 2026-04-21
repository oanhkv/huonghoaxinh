<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class OrderInventoryService
{
    /**
     * @param \Illuminate\Support\Collection<int, mixed> $cartItems
     */
    public function findStockIssue(Collection $cartItems): ?string
    {
        $grouped = $cartItems->groupBy('product_id');

        foreach ($grouped as $items) {
            $first = $items->first();
            $product = $first->product ?? Product::find($first->product_id);

            if (! $product || ! $product->is_active) {
                return 'Có sản phẩm không còn kinh doanh. Vui lòng cập nhật lại giỏ hàng.';
            }

            $needQty = (int) $items->sum('quantity');
            if ((int) $product->stock <= 0) {
                return "Sản phẩm \"{$product->name}\" đã hết hàng.";
            }

            if ($needQty > (int) $product->stock) {
                return "Sản phẩm \"{$product->name}\" chỉ còn {$product->stock} trong kho.";
            }
        }

        return null;
    }

    /**
     * @param \Illuminate\Support\Collection<int, mixed> $cartItems
     */
    public function reserveForCartItems(Collection $cartItems): void
    {
        $grouped = $cartItems->groupBy('product_id');

        foreach ($grouped as $productId => $items) {
            $needQty = (int) $items->sum('quantity');
            $product = Product::whereKey($productId)->lockForUpdate()->first();

            if (! $product || ! $product->is_active) {
                throw ValidationException::withMessages([
                    'shipping_address' => 'Một số sản phẩm không còn kinh doanh. Vui lòng kiểm tra lại giỏ hàng.',
                ]);
            }

            if ((int) $product->stock < $needQty) {
                throw ValidationException::withMessages([
                    'shipping_address' => "Sản phẩm \"{$product->name}\" chỉ còn {$product->stock} trong kho.",
                ]);
            }

            $product->decrement('stock', $needQty);
        }
    }

    public function restoreForOrder(Order $order): void
    {
        if (! $order->stock_deducted) {
            return;
        }

        $order->loadMissing('orderItems');

        foreach ($order->orderItems as $item) {
            $product = Product::whereKey($item->product_id)->lockForUpdate()->first();
            if (! $product) {
                continue;
            }

            $product->increment('stock', (int) $item->quantity);
        }

        $order->stock_deducted = false;
        $order->save();
    }
}

