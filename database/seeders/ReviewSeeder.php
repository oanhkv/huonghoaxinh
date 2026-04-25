<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::query()
            ->whereNotIn('email', ['admin1@huonghoaxinh.com'])
            ->get();
        $products = Product::take(15)->get();

        if ($users->isEmpty() || $products->isEmpty()) {
            return;
        }

        $reviewTemplates = [
            ['rating' => 5, 'comment' => 'Hoa rất tươi, bó đẹp như hình. Người yêu mình cực kỳ thích, sẽ ủng hộ shop tiếp.'],
            ['rating' => 5, 'comment' => 'Giao hàng nhanh, đóng gói cẩn thận. Hoa giữ được 5 ngày vẫn còn tươi.'],
            ['rating' => 4, 'comment' => 'Hoa đẹp, mùi thơm dịu nhẹ. Trừ 1 sao vì giao hơi trễ so với hẹn nhưng vẫn ok.'],
            ['rating' => 5, 'comment' => 'Shop tư vấn rất nhiệt tình. Bó hoa gửi đi sinh nhật mẹ, mẹ vui lắm.'],
            ['rating' => 5, 'comment' => 'Tone màu sang trọng đúng như mô tả. Đáng tiền lắm, mọi người yên tâm đặt nhé!'],
            ['rating' => 4, 'comment' => 'Đẹp ạ, giá hợp lý. Có thiệp đính kèm nữa nên rất bất ngờ.'],
            ['rating' => 5, 'comment' => 'Mua tặng đối tác, rất vừa ý. Nhân viên gói quà chu đáo, khách nhận khen mãi.'],
            ['rating' => 3, 'comment' => 'Hoa ổn, nhưng vài cánh có hơi héo. Mong shop kiểm tra kỹ hơn trước khi giao.'],
            ['rating' => 5, 'comment' => 'Lẵng hoa khai trương cực sang, chủ tiệm khen quá trời. Sẽ giới thiệu bạn bè.'],
            ['rating' => 4, 'comment' => 'Form hoa đẹp, đúng chủ đề Valentine. Giao nhanh trong 2 tiếng.'],
            ['rating' => 5, 'comment' => 'Hoa xinh, có hộp quà kèm theo nữa. Đúng chuẩn quà tặng dịp đặc biệt.'],
            ['rating' => 5, 'comment' => 'Lần thứ 3 đặt rồi, lần nào cũng hài lòng. Đặc biệt thích cách shop chăm sóc khách.'],
            ['rating' => 4, 'comment' => 'Đẹp, sang trọng. Có thể thay đổi vài chi tiết theo yêu cầu, rất linh hoạt.'],
            ['rating' => 5, 'comment' => 'Hoa hồng đỏ to, đẹp và rất tươi. Người được tặng cảm động đến phát khóc 🥹'],
            ['rating' => 5, 'comment' => 'Giá tốt nhất khu vực mình tìm được, chất lượng vượt mong đợi.'],
        ];

        Review::query()->delete();

        $idx = 0;
        foreach ($products as $product) {
            // Mỗi product có 1-2 review, đảm bảo 1 user chỉ review 1 product một lần.
            $reviewerCount = random_int(1, min(2, $users->count()));
            $reviewers = $users->shuffle()->take($reviewerCount);

            foreach ($reviewers as $reviewer) {
                $template = $reviewTemplates[$idx % count($reviewTemplates)];
                $idx++;

                Review::create([
                    'user_id' => $reviewer->id,
                    'product_id' => $product->id,
                    'rating' => $template['rating'],
                    'comment' => $template['comment'],
                    'is_visible' => true,
                    'created_at' => now()->subDays(random_int(1, 30)),
                    'updated_at' => now()->subDays(random_int(0, 5)),
                ]);
            }
        }
    }
}
