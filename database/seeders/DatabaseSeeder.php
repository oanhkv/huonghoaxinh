<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Admin & users phải có trước (orders, reviews phụ thuộc users)
            AdminSeeder::class,
            UserSeeder::class,

            // Catalogue
            CategorySeeder::class,
            ProductSeeder::class,

            // Cấu hình hệ thống & nội dung
            WebsiteSettingSeeder::class,
            VoucherSeeder::class,
            BlogPostSeeder::class,

            // Dữ liệu giao dịch (cần users + products)
            OrderSeeder::class,
            ReviewSeeder::class,

            // Tin nhắn liên hệ
            ContactMessageSeeder::class,
            ContactReplySeeder::class,

            // Giỏ hàng & yêu thích cho user mẫu
            CartSeeder::class,
            WishlistSeeder::class,
        ]);
    }
}
