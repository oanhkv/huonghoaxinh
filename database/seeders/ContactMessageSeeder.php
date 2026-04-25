<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Trần Quốc Bảo',
                'email' => 'bao.tran@gmail.com',
                'phone' => '0911223344',
                'subject' => 'Tư vấn hoa khai trương',
                'message' => 'Mình muốn đặt 5 kệ hoa khai trương cho 5 chi nhánh mới, có thể tư vấn giúp mình không?',
                'status' => 'new',
                'days_ago' => 0,
            ],
            [
                'name' => 'Phạm Hoài Thư',
                'email' => 'thu.pham@gmail.com',
                'phone' => '0922334455',
                'subject' => 'Đơn hàng giao chậm',
                'message' => 'Đơn HHX240120001 của mình đặt giao 10h sáng nay nhưng đã 11h30 chưa thấy. Mong shop hỗ trợ.',
                'status' => 'read',
                'days_ago' => 1,
            ],
            [
                'name' => 'Nguyễn Đăng Khoa',
                'email' => 'khoa.nguyen@gmail.com',
                'phone' => '0933445566',
                'subject' => 'Báo giá hoa cưới',
                'message' => 'Mình cần báo giá set hoa cưới gồm: hoa cô dâu, hoa cài áo chú rể, hoa bàn tiệc 20 bàn. Cảm ơn shop!',
                'status' => 'replied',
                'days_ago' => 3,
            ],
            [
                'name' => 'Lê Phương Linh',
                'email' => 'linh.le@gmail.com',
                'phone' => '0944556677',
                'subject' => 'Hợp tác bán sỉ',
                'message' => 'Bên mình là chuỗi café, muốn hợp tác trang trí hoa hàng tuần. Vui lòng phản hồi sớm.',
                'status' => 'new',
                'days_ago' => 0,
            ],
            [
                'name' => 'Đinh Thanh Hùng',
                'email' => 'hung.dinh@gmail.com',
                'phone' => '0955667788',
                'subject' => 'Cảm ơn shop',
                'message' => 'Bó hoa hôm trước rất đẹp, vợ mình cảm động lắm. Cảm ơn cả shop ạ!',
                'status' => 'read',
                'days_ago' => 5,
            ],
            [
                'name' => 'Vũ Thị Hoa',
                'email' => 'hoa.vu@gmail.com',
                'phone' => '0966778899',
                'subject' => 'Hỏi về voucher',
                'message' => 'Mã WELCOME10 mình áp không được, vui lòng kiểm tra giúp ạ. Đơn của mình tổng 350K.',
                'status' => 'replied',
                'days_ago' => 2,
            ],
        ];

        foreach ($messages as $msg) {
            $payload = [
                'name' => $msg['name'],
                'email' => $msg['email'],
                'phone' => $msg['phone'],
                'subject' => $msg['subject'],
                'message' => $msg['message'],
                'status' => $msg['status'],
                'created_at' => now()->subDays($msg['days_ago']),
                'updated_at' => now()->subDays($msg['days_ago']),
            ];

            if ($msg['status'] !== 'new') {
                $payload['read_at'] = now()->subDays(max(0, $msg['days_ago'] - 1));
            }
            if ($msg['status'] === 'replied') {
                $payload['replied_at'] = now()->subDays(max(0, $msg['days_ago'] - 1));
            }

            ContactMessage::updateOrCreate(
                ['email' => $msg['email'], 'subject' => $msg['subject']],
                $payload
            );
        }
    }
}
