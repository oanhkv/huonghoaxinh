<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->whereNotIn('email', ['admin1@huonghoaxinh.com'])
            ->get();
        $products = Product::all();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'paid', 'cod', 'delivered', 'cancelled'];

        $orderTemplates = [
            [
                'shipping_address' => '12 Lê Lợi, Quận 1, TP.HCM',
                'phone' => '0901234567',
                'note' => 'Giao trước 17h, gọi trước khi tới.',
                'days_ago' => 1,
                'status' => 'pending',
                'item_count' => 2,
            ],
            [
                'shipping_address' => '45 Nguyễn Trãi, Quận 5, TP.HCM',
                'phone' => '0902345678',
                'note' => 'Đính kèm thiệp viết tay: Chúc mừng sinh nhật!',
                'days_ago' => 3,
                'status' => 'paid',
                'item_count' => 1,
            ],
            [
                'shipping_address' => '78 Cầu Giấy, Quận Cầu Giấy, Hà Nội',
                'phone' => '0903456789',
                'note' => null,
                'days_ago' => 5,
                'status' => 'delivered',
                'item_count' => 3,
            ],
            [
                'shipping_address' => '102 Nguyễn Huệ, Quận 1, TP.HCM',
                'phone' => '0904567890',
                'note' => 'Giao tận tay người nhận, không giao bảo vệ.',
                'days_ago' => 7,
                'status' => 'cod',
                'item_count' => 2,
            ],
            [
                'shipping_address' => '34 Hai Bà Trưng, Quận Hoàn Kiếm, Hà Nội',
                'phone' => '0905678901',
                'note' => 'Khách đổi ý, không lấy hàng.',
                'days_ago' => 10,
                'status' => 'cancelled',
                'item_count' => 1,
            ],
            [
                'shipping_address' => '56 Trần Phú, Hải Châu, Đà Nẵng',
                'phone' => '0906789012',
                'note' => 'Giao trước 12h trưa thứ 7.',
                'days_ago' => 2,
                'status' => 'paid',
                'item_count' => 2,
            ],
        ];

        // Xoá order cũ để tránh trùng dữ liệu khi seed lại nhiều lần.
        OrderItem::query()->delete();
        Order::query()->delete();

        foreach ($orderTemplates as $i => $tpl) {
            $user = $users[$i % $users->count()];
            $orderCode = 'HHX'.now()->format('ymd').str_pad((string) ($i + 1), 4, '0', STR_PAD_LEFT);

            $items = $products->random(min($tpl['item_count'], $products->count()));
            $total = 0;
            $orderItems = [];
            foreach ($items as $product) {
                $qty = random_int(1, 3);
                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $qty,
                    'price' => $product->price,
                ];
                $total += $product->price * $qty;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_code' => $orderCode,
                'total_amount' => $total,
                'status' => $tpl['status'],
                'stock_deducted' => in_array($tpl['status'], ['paid', 'cod', 'delivered'], true),
                'shipping_address' => $tpl['shipping_address'],
                'phone' => $tpl['phone'],
                'note' => $tpl['note'],
                'created_at' => now()->subDays($tpl['days_ago']),
                'updated_at' => now()->subDays(max(0, $tpl['days_ago'] - 1)),
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }
        }
    }
}
