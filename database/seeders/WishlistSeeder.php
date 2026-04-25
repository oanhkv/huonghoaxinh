<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;

class WishlistSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->whereNotIn('email', ['admin1@huonghoaxinh.com'])
            ->take(3)
            ->get();
        $products = Product::take(15)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        Wishlist::query()->delete();

        foreach ($users as $user) {
            $picks = $products->random(min(3, $products->count()));
            foreach ($picks as $product) {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                ]);
            }
        }
    }
}
