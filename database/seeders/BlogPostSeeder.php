<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Mẹo chọn hoa', 'slug' => 'meo-chon-hoa', 'description' => 'Kinh nghiệm chọn hoa cho mọi dịp.'],
            ['name' => 'Hoa sinh nhật', 'slug' => 'hoa-sinh-nhat', 'description' => 'Bộ sưu tập hoa đẹp dành tặng người thân.'],
            ['name' => 'Quà tặng', 'slug' => 'qua-tang', 'description' => 'Gợi ý quà tặng kèm hoa, thiệp và set đẹp.'],
            ['name' => 'Lễ tình nhân', 'slug' => 'le-tinh-nhan', 'description' => 'Hoa và quà cho những khoảnh khắc lãng mạn.'],
            ['name' => 'Trang trí nhà', 'slug' => 'trang-tri-nha', 'description' => 'Ý tưởng hoa & cây xanh cho không gian sống.'],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $posts = [
            [
                'title' => 'Cách chọn hoa hồng tươi cho người thương',
                'slug' => 'cach-chon-hoa-hong-tuoi-cho-nguoi-thuong',
                'blog_category_id' => BlogCategory::where('slug', 'meo-chon-hoa')->value('id'),
                'excerpt' => 'Những bí quyết chọn hoa hồng tươi để gửi gắm yêu thương vào từng cánh hoa.',
                'content' => 'Hoa hồng luôn là biểu tượng của tình yêu và sự ngọt ngào. Chọn hoa hồng tươi đòi hỏi bạn cần xem kỹ màu sắc, độ tươi và kiểu dáng bó hoa. Hãy ưu tiên những cánh hoa không bị nát, có thân thẳng và màu sắc đồng đều.',
                'image' => null,
                'is_active' => true,
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => '5 kiểu bó hoa sinh nhật đẹp cho cô ấy',
                'slug' => '5-kieu-bo-hoa-sinh-nhat-dep-cho-co-ay',
                'blog_category_id' => BlogCategory::where('slug', 'hoa-sinh-nhat')->value('id'),
                'excerpt' => 'Khám phá 5 kiểu bó hoa sinh nhật vừa sang vừa ngọt, dành tặng người phụ nữ quan trọng.',
                'content' => 'Sinh nhật là dịp hoàn hảo để tặng hoa đẹp. Những bó hoa tone hồng pastel, đỏ rượu và trắng tinh khôi luôn là lựa chọn an toàn nhưng rất ấn tượng. Bạn cũng có thể kết hợp hoa cưới mini và lá xanh để tăng vẻ sang trọng.',
                'image' => null,
                'is_active' => true,
                'published_at' => now()->subDays(3),
            ],
            [
                'title' => 'Gợi ý quà tặng 20/10 cho mẹ và vợ',
                'slug' => 'goi-y-qua-tang-20-10-cho-me-va-vo',
                'blog_category_id' => BlogCategory::where('slug', 'qua-tang')->value('id'),
                'excerpt' => 'Những món quà hoa tươi và kèm theo lựa chọn phù hợp cho ngày 20/10.',
                'content' => 'Ngày 20/10 đến gần, hãy chuẩn bị món quà ý nghĩa. Một bó hoa tươi rực rỡ kết hợp cùng thiệp viết tay là lựa chọn không bao giờ lỗi mốt. Bạn có thể chọn hoa cẩm chướng, hồng nhung hoặc lan hồ điệp tùy phong cách của người nhận.',
                'image' => null,
                'is_active' => true,
                'published_at' => now()->subDays(1),
            ],
            [
                'title' => '4 cách giữ hoa tươi lâu sau khi nhận',
                'slug' => '4-cach-giu-hoa-tuoi-lau-sau-khi-nhan',
                'blog_category_id' => BlogCategory::where('slug', 'meo-chon-hoa')->value('id'),
                'excerpt' => 'Mẹo chăm sóc hoa đơn giản để mỗi bó hoa luôn tươi mới trên bàn bạn.',
                'content' => 'Đổi nước hằng ngày, cắt gốc hoa và đặt nơi mát là 3 bước đơn giản. Tránh ánh nắng trực tiếp và nhiệt độ cao để hoa giữ được màu sắc và hương thơm lâu hơn.',
                'image' => null,
                'is_active' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Gợi ý bó hoa Valentine lãng mạn',
                'slug' => 'goi-y-bo-hoa-valentine-lang-man',
                'blog_category_id' => BlogCategory::where('slug', 'le-tinh-nhan')->value('id'),
                'excerpt' => 'Những mẫu hoa Valentine đẹp, ngọt ngào và phù hợp cho từng phong cách người yêu.',
                'content' => 'Hoa hồng đỏ kết hợp với baby trắng hoặc hoa tulip pastel tạo nên bouquets lãng mạn. Bạn có thể chọn thêm thiệp tay để tăng sự tinh tế và cảm xúc cho món quà.',
                'image' => null,
                'is_active' => true,
                'published_at' => now()->subDay(),
            ],
        ];

        foreach ($posts as $post) {
            BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                $post
            );
        }
    }
}
