<?php

namespace Database\Seeders;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->whereNotIn('email', ['admin1@huonghoaxinh.com'])
            ->take(3)
            ->get();
        $products = Product::take(8)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        Cart::query()->delete();

        $idx = 0;
        foreach ($users as $user) {
            // Mỗi user có 2 mặt hàng đang trong giỏ
            $picks = $products->random(min(2, $products->count()));
            foreach ($picks as $product) {
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => random_int(1, 3),
                    'price' => $product->price,
                    'variant' => $idx % 2 === 0 ? 'Bó vừa' : 'Bó lớn',
                ]);
                $idx++;
            }
        }
    }
}
