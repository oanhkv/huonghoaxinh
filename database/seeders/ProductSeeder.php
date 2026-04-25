<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()->select('id', 'name', 'slug')->get();
        if ($categories->isEmpty()) {
            return;
        }

        // Mỗi danh mục seed tối đa 10 sản phẩm => tổng ~50 (với 5 danh mục).
        // Khi chạy lại seeder, sẽ dọn bớt các bản seed dư (mốc 11-20) để DB không phình lên.
        $perCategory = 10;
        $previousPerCategory = 20;

        $imagePool = [
            // Picsum: ổn định theo seed, giúp mỗi sản phẩm khác ảnh (cần internet).
            'https://picsum.photos/seed/hoa-1/360/450',
            'https://picsum.photos/seed/hoa-2/360/450',
            'https://picsum.photos/seed/hoa-3/360/450',
            'https://picsum.photos/seed/hoa-4/360/450',
            'https://picsum.photos/seed/hoa-5/360/450',
            'https://picsum.photos/seed/hoa-6/360/450',
            'https://picsum.photos/seed/hoa-7/360/450',
            'https://picsum.photos/seed/hoa-8/360/450',
            'https://picsum.photos/seed/hoa-9/360/450',
            'https://picsum.photos/seed/hoa-10/360/450',
        ];

        $productKeywords = [
            'Bó hoa premium',
            'Hộp hoa nghệ thuật',
            'Giỏ hoa cao cấp',
            'Lẵng hoa chúc mừng',
            'Kệ hoa sự kiện',
            'Hoa mix tone pastel',
            'Hoa tone đỏ sang trọng',
            'Hoa tone trắng tinh khôi',
            'Hoa tone vàng ấm áp',
            'Hoa tone tím lãng mạn',
            'Hoa tulip hiện đại',
            'Hoa hồng Ecuador',
            'Hoa baby nhẹ nhàng',
            'Hoa ly thanh lịch',
            'Hoa hướng dương năng lượng',
            'Hoa cẩm chướng ngọt ngào',
            'Hoa đồng tiền may mắn',
            'Hoa mẫu đơn tinh tế',
            'Hoa lan hồ điệp',
            'Hoa mini để bàn',
            'Set quà hoa và thiệp',
            'Bình hoa phong cách Hàn',
            'Bó hoa tặng đối tác',
            'Bó hoa tặng mẹ',
            'Bó hoa tặng người yêu',
        ];

        $sizePresets = [
            [
                ['size' => 'Nhỏ', 'price' => 0],
                ['size' => 'Vừa', 'price' => 70000],
                ['size' => 'Lớn', 'price' => 140000],
            ],
            [
                ['size' => '40cm', 'price' => 0],
                ['size' => '50cm', 'price' => 60000],
                ['size' => '60cm', 'price' => 120000],
            ],
            [
                ['size' => 'Tiêu chuẩn', 'price' => 0],
                ['size' => 'Nâng cấp', 'price' => 85000],
            ],
        ];

        foreach ($categories as $category) {
            // Dọn các bản seed dư nếu trước đó seed 20/danh mục.
            for ($i = $perCategory + 1; $i <= $previousPerCategory; $i++) {
                $keyword = $productKeywords[($i - 1) % count($productKeywords)];
                $slug = Str::slug($category->slug.'-'.$keyword.'-'.$i);
                Product::query()->where('slug', $slug)->delete();
            }

            for ($i = 1; $i <= $perCategory; $i++) {
                $keyword = $productKeywords[($i - 1) % count($productKeywords)];
                $name = "{$keyword} {$category->name} #{$i}";
                $slug = Str::slug($category->slug.'-'.$keyword.'-'.$i);
                $seed = urlencode($category->slug.'-'.$i);
                $imageUrl = $imagePool[($i - 1) % count($imagePool)].'?v='.$seed;

                Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $name,
                        'description' => "Mẫu {$category->name} thiết kế theo phong cách hiện đại, phù hợp nhiều dịp tặng. Sản phẩm được bó thủ công, kiểm tra chất lượng trước khi giao.",
                        'price' => random_int(290000, 1250000),
                        'stock' => random_int(0, 80),
                        'category_id' => $category->id,
                        'is_featured' => $i <= 5,
                        'is_active' => true,
                        // Ảnh URL để hiển thị ngay ở trang user/admin
                        'image' => $imageUrl,
                        'sizes' => $sizePresets[($i - 1) % count($sizePresets)],
                    ]
                );
            }
        }

        // Đảm bảo tổng sản phẩm không vượt quá tổng seed mong muốn.
        // Giữ lại các sản phẩm mới nhất (theo id) để admin chỉ còn đúng 50.
        $targetTotal = $categories->count() * $perCategory;
        $idsToKeep = Product::query()
            ->orderByDesc('id')
            ->limit($targetTotal)
            ->pluck('id');

        Product::query()
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}
