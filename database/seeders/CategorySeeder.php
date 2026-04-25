<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hoa Sinh Nhật',
                'slug' => 'hoa-sinh-nhat',
                'description' => 'Bó hoa, giỏ hoa rực rỡ dành tặng người thân yêu trong ngày sinh nhật đáng nhớ.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B025-I-Love-You-3-400x500.webp',
            ],
            [
                'name' => 'Hoa Tình Yêu',
                'slug' => 'hoa-tinh-yeu',
                'description' => 'Hoa hồng đỏ, hoa tình nhân – gửi gắm trọn vẹn yêu thương đến người thương.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B014-Bo-Hoa-Do-Tham-400x500.webp',
            ],
            [
                'name' => 'Hoa Chúc Mừng',
                'slug' => 'hoa-chuc-mung',
                'description' => 'Lẵng hoa, kệ hoa chúc mừng sự kiện, lễ kỷ niệm và những dịp trọng đại.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt106-khai-truong-hong-phat-400x500.webp',
            ],
            [
                'name' => 'Hoa Tang Lễ',
                'slug' => 'hoa-tang-le',
                'description' => 'Hoa kính viếng, vòng hoa tang lễ trang trọng và thành kính phân ưu.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Hoa-Chia-Buon-TH-CB011-e1720663109658-400x500.webp',
            ],
            [
                'name' => 'Hoa Khai Trương',
                'slug' => 'hoa-khai-truong',
                'description' => 'Kệ hoa khai trương sang trọng, mang đến tài lộc và may mắn cho ngày trọng đại.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt-105-400x500.webp',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(['slug' => $cat['slug']], $cat);
        }

        $birthdayId = Category::where('slug', 'hoa-sinh-nhat')->value('id');
        $loveId = Category::where('slug', 'hoa-tinh-yeu')->value('id');
        $congratsId = Category::where('slug', 'hoa-chuc-mung')->value('id');

        $children = [
            [
                'name' => 'Bó Hoa Sinh Nhật',
                'slug' => 'bo-hoa-sinh-nhat',
                'description' => 'Các mẫu bó hoa sinh nhật trẻ trung, hợp tặng bạn bè và người thân.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2024/09/Bo-Hoa-Tuoi-TH-B008-F-400x500.jpg',
                'parent_id' => $birthdayId,
            ],
            [
                'name' => 'Hoa Hồng Tình Yêu',
                'slug' => 'hoa-hong-tinh-yeu',
                'description' => 'Hoa hồng đỏ Ecuador – biểu tượng tình yêu nồng cháy.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2023/09/Bo-Hoa-Tuoi-TH-B010-400x500.webp',
                'parent_id' => $loveId,
            ],
            [
                'name' => 'Lẵng Hoa Chúc Mừng',
                'slug' => 'lang-hoa-chuc-mung',
                'description' => 'Lẵng hoa cao cấp dùng cho các dịp chúc mừng và sự kiện trang trọng.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Lang-Hoa-De-Ban-TH-H020-e1720662707697-400x500.webp',
                'parent_id' => $congratsId,
            ],
        ];

        foreach ($children as $child) {
            Category::updateOrCreate(['slug' => $child['slug']], $child);
        }
    }
}
