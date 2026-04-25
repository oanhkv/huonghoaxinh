<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        $vouchers = [
            [
                'code' => 'WELCOME10',
                'name' => 'Chào mừng khách mới giảm 10%',
                'type' => 'percent',
                'value' => 10,
                'min_order_amount' => 300000,
                'max_discount_amount' => 100000,
                'usage_limit' => 500,
                'used_count' => 12,
                'starts_at' => now()->subDays(7),
                'ends_at' => now()->addDays(60),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP30',
                'name' => 'Miễn phí ship 30K đơn từ 200K',
                'type' => 'fixed',
                'value' => 30000,
                'min_order_amount' => 200000,
                'max_discount_amount' => 30000,
                'usage_limit' => 1000,
                'used_count' => 80,
                'starts_at' => now()->subDays(3),
                'ends_at' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'VALENTINE50',
                'name' => 'Lễ Tình Nhân giảm 50K',
                'type' => 'fixed',
                'value' => 50000,
                'min_order_amount' => 500000,
                'max_discount_amount' => 50000,
                'usage_limit' => 200,
                'used_count' => 45,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(14),
                'is_active' => true,
            ],
            [
                'code' => 'BIRTHDAY15',
                'name' => 'Hoa sinh nhật giảm 15%',
                'type' => 'percent',
                'value' => 15,
                'min_order_amount' => 600000,
                'max_discount_amount' => 200000,
                'usage_limit' => 300,
                'used_count' => 33,
                'starts_at' => now()->subDays(5),
                'ends_at' => now()->addDays(45),
                'is_active' => true,
            ],
            [
                'code' => 'OPENING20',
                'name' => 'Khai trương phát đạt giảm 20%',
                'type' => 'percent',
                'value' => 20,
                'min_order_amount' => 1000000,
                'max_discount_amount' => 300000,
                'usage_limit' => 100,
                'used_count' => 18,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(20),
                'is_active' => true,
            ],
            [
                'code' => 'VIP100K',
                'name' => 'Khách VIP giảm ngay 100K',
                'type' => 'fixed',
                'value' => 100000,
                'min_order_amount' => 800000,
                'max_discount_amount' => 100000,
                'usage_limit' => 150,
                'used_count' => 25,
                'starts_at' => now()->subDays(10),
                'ends_at' => now()->addDays(90),
                'is_active' => true,
            ],
        ];

        foreach ($vouchers as $voucher) {
            Voucher::updateOrCreate(['code' => $voucher['code']], $voucher);
        }
    }
}
