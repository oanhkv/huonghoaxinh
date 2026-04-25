<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use App\Models\ContactReply;
use App\Models\User;
use Illuminate\Database\Seeder;

class ContactReplySeeder extends Seeder
{
    public function run(): void
    {
        // contact_replies.admin_id FK → users.id (xem migration). Lấy User admin legacy.
        $admin = User::where('email', 'admin1@huonghoaxinh.com')->first();
        // Reply mọi message đã đọc/đã trả lời để có lịch sử trao đổi.
        $messages = ContactMessage::whereIn('status', ['read', 'replied'])->get();

        if (! $admin || $messages->isEmpty()) {
            return;
        }

        ContactReply::query()->delete();

        $bodies = [
            'Chào anh/chị, cảm ơn anh/chị đã liên hệ với Hương Hoa Xinh. Bộ phận tư vấn sẽ gọi điện trong 30 phút tới để xác nhận chi tiết. Trân trọng!',
            'Cảm ơn anh/chị đã quan tâm. Bên em gửi báo giá chi tiết trong file đính kèm. Có gì anh/chị inbox lại em ạ.',
            'Em đã kiểm tra mã voucher và xác nhận đã hoạt động lại bình thường. Anh/chị thử lại nhé. Cảm ơn anh/chị nhiều!',
            'Em đã ghi nhận phản hồi và chuyển cho bộ phận giao hàng. Lần sau bên em sẽ chú ý hơn. Mong anh/chị thông cảm.',
            'Cảm ơn lời khen của anh/chị. Hẹn gặp lại anh/chị trong lần đặt hoa tới ạ!',
            'Em vừa kiểm tra lại đơn hàng giúp anh/chị, tài xế đang trên đường giao tới. Dự kiến 15 phút nữa sẽ có mặt.',
            'Bên em đã chuẩn bị mẫu hoa theo yêu cầu. Em gửi anh/chị xem trước hình thiết kế qua Zalo nhé.',
        ];

        $idx = 0;
        foreach ($messages as $msg) {
            // Mỗi message có 1-2 reply để mô phỏng trao đổi qua lại.
            $count = $msg->status === 'replied' ? 2 : 1;
            for ($i = 0; $i < $count; $i++) {
                ContactReply::create([
                    'contact_message_id' => $msg->id,
                    'admin_id' => $admin->id,
                    'subject' => 'Re: '.$msg->subject,
                    'body' => $bodies[$idx % count($bodies)],
                    'to_email' => $msg->email,
                    'sent_at' => ($msg->replied_at ?? $msg->read_at ?? now()->subDay())->copy()->addMinutes($i * 30),
                    'send_error' => null,
                ]);
                $idx++;
            }
        }
    }
}
