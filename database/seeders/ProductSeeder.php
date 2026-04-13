<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Bó Hoa Hồng Đỏ Tươi',
                'slug' => 'bo-hoa-hong-do-tuoi',
                'description' => 'Bó hoa hồng đỏ tươi rất đẹp, thích hợp tặng người yêu.',
                'price' => 450000,
                'stock' => 30,
                'category_id' => 2,
                'is_featured' => true,
                'image' => 'products/hoa-hong-do.jpg'
            ],
            [
                'name' => 'Giỏ Hoa Sinh Nhật Sang Trọng',
                'slug' => 'gio-hoa-sinh-nhat',
                'description' => 'Giỏ hoa đa dạng màu sắc chúc mừng sinh nhật.',
                'price' => 650000,
                'stock' => 20,
                'category_id' => 1,
                'is_featured' => true,
                'image' => 'products/gio-hoa-sinh-nhat.jpg'
            ],
            // Bạn có thể thêm nhiều sản phẩm nữa...
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}