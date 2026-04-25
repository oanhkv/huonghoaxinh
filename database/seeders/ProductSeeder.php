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
        $catalog = [
            'hoa-sinh-nhat' => [
                ['name' => 'Bó Hoa Sinh Nhật I Love You', 'price' => 690000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B025-I-Love-You-3-400x500.webp', 'desc' => 'Bó hoa hồng đỏ phối baby trắng – lời chúc sinh nhật ngọt ngào và lãng mạn.'],
                ['name' => 'Bó Hoa Bình Yên',           'price' => 590000, 'image' => 'https://tramhoa.com/wp-content/uploads/2023/02/Bo-Hoa-Tuoi-TH-B051-400x500.webp', 'desc' => 'Tone pastel nhẹ nhàng, mang đến cảm giác bình yên trong ngày đặc biệt.'],
                ['name' => 'Bó Hoa Tương Tư',           'price' => 750000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B009-1-400x500.webp', 'desc' => 'Hoa hồng phấn kết hợp lá xanh – nói thay nỗi nhớ thầm lặng.'],
                ['name' => 'Bó Hoa Forever Love',       'price' => 890000, 'image' => 'https://tramhoa.com/wp-content/uploads/2024/11/Bo-Hoa-Tuoi-TH-B012-Forever-Love-F-400x500.jpg', 'desc' => 'Bó hoa hồng đỏ rực – lời tỏ tình tinh tế, sang trọng và đẳng cấp.'],
                ['name' => 'Bó Hoa Tình Hồng',          'price' => 650000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B016-Tinh-Hong-400x500.webp', 'desc' => 'Bó hoa hồng nhung tone đỏ rượu, sang trọng và quý phái.'],
                ['name' => 'Bó Hoa Rộn Ràng',           'price' => 720000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B005-Ron-Rang-400x500.webp', 'desc' => 'Bó hoa nhiều màu rực rỡ, mang lại không khí vui tươi cho buổi tiệc.'],
                ['name' => 'Bó Hoa Kỷ Niệm Đẹp',        'price' => 980000, 'image' => 'https://tramhoa.com/wp-content/uploads/2024/09/Bo-Hoa-Tuoi-TH-B008-F-400x500.jpg', 'desc' => 'Hoa hồng cao cấp kết hợp hoa lan – bó hoa kỷ niệm sang trọng.'],
                ['name' => 'Bó Hoa Ánh Dương',          'price' => 540000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B032-Anh-Duong-1-400x500.webp', 'desc' => 'Hoa hướng dương mang năng lượng tích cực, lời chúc tươi sáng.'],
            ],

            'hoa-tinh-yeu' => [
                ['name' => 'Bó Hoa Đỏ Thắm Valentine',   'price' => 850000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B014-Bo-Hoa-Do-Tham-400x500.webp', 'desc' => 'Hoa hồng đỏ thắm – biểu tượng tình yêu nồng nàn và say đắm.'],
                ['name' => 'Bó 100 Bông Hồng Đỏ',         'price' => 2890000, 'image' => 'https://tramhoa.com/wp-content/uploads/2023/09/Bo-Hoa-Tuoi-TH-B010-400x500.webp', 'desc' => 'Bó hoa 100 bông hồng đỏ – món quà đặc biệt cho người đặc biệt.'],
                ['name' => 'Bó Hoa My Everything',        'price' => 990000,  'image' => 'https://tramhoa.com/wp-content/uploads/2023/04/Bo-Hoa-Tuoi-TH-B107-everything-400x500.webp', 'desc' => 'Hoa hồng phấn – em là tất cả của anh, lời tỏ tình lãng mạn.'],
                ['name' => 'Bó Hoa Âm Thầm',              'price' => 690000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B024-3-e1720603830156-400x500.webp', 'desc' => 'Tone tím lãng mạn, gửi gắm nỗi nhớ âm thầm và da diết.'],
                ['name' => 'Giỏ Hoa Trái Tim',            'price' => 880000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Gio-hoa-trai-tim-G050-400x500.webp', 'desc' => 'Giỏ hoa hình trái tim – ngọt ngào và đầy ý nghĩa cho người ấy.'],
                ['name' => 'Giỏ Hoa Vì Yêu',              'price' => 760000,  'image' => 'https://tramhoa.com/wp-content/uploads/2024/12/Gio-Hoa-TH-G006-1-400x500.webp', 'desc' => 'Giỏ hoa tone đỏ pha hồng – vì yêu nên dành tất cả.'],
                ['name' => 'Giỏ Hoa Hồng Kem Chàng Vàng', 'price' => 820000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Gio-Hoa-TH-G024-Chang-Vang-2-e1720603583809-400x500.webp', 'desc' => 'Giỏ hoa tone kem nhẹ nhàng, sang trọng cho ngày kỷ niệm.'],
                ['name' => 'Bó Hoa Tone Pastel',          'price' => 680000,  'image' => 'https://tramhoa.com/wp-content/uploads/2024/06/Gio-Hoa-TH-G057-Chan-Phuong-1-400x500.jpg', 'desc' => 'Tone pastel ngọt ngào dành tặng nửa kia, dịu dàng và quyến rũ.'],
            ],

            'hoa-chuc-mung' => [
                ['name' => 'Lẵng Hoa Bừng Sáng',         'price' => 950000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Lang-Hoa-De-Ban-TH-H052-Bung-Sang-1-400x500.webp', 'desc' => 'Lẵng hoa rực rỡ chúc mừng sự kiện thêm phần long trọng.'],
                ['name' => 'Lẵng Hoa Của Trời',          'price' => 1050000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Lang-Hoa-De-Ban-TH-H020-e1720662707697-400x500.webp', 'desc' => 'Lẵng hoa cao cấp với tone vàng nổi bật, sang trọng và quý phái.'],
                ['name' => 'Lẵng Hoa Khúc Khải Hoàn',    'price' => 1280000, 'image' => 'https://tramhoa.com/wp-content/uploads/2021/09/Lang-Hoa-De-Ban-TH-H040-Khuc-Khai-Hoan-1-e1720662090673-400x500.webp', 'desc' => 'Lẵng hoa hoành tráng cho lễ kỷ niệm và chúc mừng thành công.'],
                ['name' => 'Lẵng Hoa Baby',              'price' => 720000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/11/Lang-Hoa-De-Ban-TH-H077-Lang-Hoa-Baby-1-400x500.webp', 'desc' => 'Lẵng hoa baby trắng tinh khôi, nhẹ nhàng và thanh lịch.'],
                ['name' => 'Giỏ Hoa Khởi Đầu Mới',       'price' => 880000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Gio-Hoa-TH-G043-Khoi-Dau-Moi-400x500.webp', 'desc' => 'Giỏ hoa chúc mừng khởi đầu mới, gửi lời chúc tốt đẹp nhất.'],
                ['name' => 'Giỏ Hoa Nụ Cười',            'price' => 650000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Gio-Hoa-TH-G017-1-1-e1720662825573-400x500.webp', 'desc' => 'Giỏ hoa tươi vui, mang đến niềm vui và nụ cười cho người nhận.'],
                ['name' => 'Giỏ Hoa Vững Vàng',          'price' => 990000,  'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Hoa-Hop-Go-TH-H047-Vung-Vang-e1720603262309-400x500.webp', 'desc' => 'Giỏ hoa hộp gỗ chắc chắn, lời chúc vững vàng trên đường đời.'],
                ['name' => 'Giỏ Hoa Thăng Tiến',         'price' => 1180000, 'image' => 'https://tramhoa.com/wp-content/uploads/2020/02/Hoa-Hop-Go-TH-H119-2-400x500.webp', 'desc' => 'Giỏ hoa cao cấp – chúc mừng thăng tiến trong sự nghiệp.'],
            ],

            'hoa-tang-le' => [
                ['name' => 'Hoa Chia Buồn Kính Viếng',   'price' => 1280000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Hoa-Chia-Buon-TH-CB011-e1720663109658-400x500.webp', 'desc' => 'Hoa kính viếng trang trọng, thành kính chia buồn cùng gia quyến.'],
                ['name' => 'Hoa Chia Buồn Lắng Đọng',    'price' => 1390000, 'image' => 'https://tramhoa.com/wp-content/uploads/2020/09/Hoa-Chia-Buon-TH-CB033-Lang-Dong-1-400x500.webp', 'desc' => 'Tone trắng tinh khôi, nỗi buồn lắng đọng và trang nghiêm.'],
                ['name' => 'Hoa Chia Buồn Thương Tiếc',  'price' => 1450000, 'image' => 'https://tramhoa.com/wp-content/uploads/2020/08/Hoa-Chia-Buon-TH-CB096-Thuong-Tiec-1-400x500.webp', 'desc' => 'Vòng hoa thương tiếc – tiễn biệt người đã khuất.'],
                ['name' => 'Hoa Chia Buồn Phân Ưu',      'price' => 1320000, 'image' => 'https://tramhoa.com/wp-content/uploads/2021/04/Hoa-Chia-Buon-TH-CB095-1-400x500.webp', 'desc' => 'Hoa phân ưu trang trọng dành cho lễ tang và viếng thăm.'],
                ['name' => 'Hoa Thành Kính Phân Ưu',     'price' => 1500000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Hoa-Chia-Buon-TH-CB050-400x500.webp', 'desc' => 'Vòng hoa thành kính phân ưu, thay lời tiễn biệt.'],
                ['name' => 'Hoa Chia Buồn An Nghỉ',      'price' => 1240000, 'image' => 'https://tramhoa.com/wp-content/uploads/2021/01/Hoa-Chia-Buon-TH-CB111-An-Nghi-400x500.webp', 'desc' => 'Hoa trắng tinh khôi, cầu cho linh hồn người đã khuất an nghỉ.'],
                ['name' => 'Vòng Hoa Kính Viếng',        'price' => 1680000, 'image' => 'https://tramhoa.com/wp-content/uploads/2020/08/Hoa-Chia-Buon-TH-CB098-Vong-Hoa-Kinh-Vieng-2-e1720663548619-400x500.webp', 'desc' => 'Vòng hoa kính viếng trang nghiêm, thành kính phân ưu.'],
                ['name' => 'Hoa Chia Buồn Cánh Diều Trắng','price' => 1390000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Hoa-Chia-Buon-TH-CB141-2-400x500.webp', 'desc' => 'Tone trắng nhẹ nhàng, tiễn biệt người thân yêu.'],
            ],

            'hoa-khai-truong' => [
                ['name' => 'Kệ Hoa Khai Trương Hồng Phát', 'price' => 1490000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt106-khai-truong-hong-phat-400x500.webp', 'desc' => 'Kệ hoa khai trương tone đỏ vàng – chúc làm ăn hồng phát, tài lộc đầy nhà.'],
                ['name' => 'Kệ Hoa Khai Trương Tấn Tài',    'price' => 1590000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt-105-400x500.webp', 'desc' => 'Kệ hoa sang trọng, chúc khai trương tấn tài tấn lộc.'],
                ['name' => 'Kệ Hoa Đỏ Thắm',                'price' => 1380000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/ke-hoa-khai-truong-kt006-do-tham-400x500.webp', 'desc' => 'Kệ hoa đỏ thắm – may mắn và phát đạt cho ngày khai trương.'],
                ['name' => 'Kệ Hoa Sang Trọng',             'price' => 1750000, 'image' => 'https://tramhoa.com/wp-content/uploads/2024/04/ke-hoa-khai-truong-kt056-sang-trong-400x500.jpg', 'desc' => 'Kệ hoa cao cấp, sang trọng cho doanh nghiệp lớn khai trương.'],
                ['name' => 'Kệ Hoa Hồng Phát',              'price' => 1450000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt-083-400x500.webp', 'desc' => 'Kệ hoa tone hồng đỏ rực rỡ – chúc khai trương hồng phát.'],
                ['name' => 'Kệ Hoa Vững Vàng',              'price' => 1380000, 'image' => 'https://tramhoa.com/wp-content/uploads/2020/01/ke-hoa-khai-truong-kt115-vung-vang-400x500.webp', 'desc' => 'Kệ hoa chúc doanh nghiệp vững vàng vượt qua mọi thử thách.'],
                ['name' => 'Kệ Hoa Làm Ăn Phát Đạt',        'price' => 1690000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/ke-hoa-khai-truong-kt015-lam-an-phat-dat-400x500.webp', 'desc' => 'Kệ hoa kết hợp tone đỏ và vàng – chúc làm ăn phát đạt.'],
                ['name' => 'Kệ Hoa Đơm Hoa Kết Trái',       'price' => 1820000, 'image' => 'https://tramhoa.com/wp-content/uploads/2021/09/ke-hoa-khai-truong-kt001-400x500.webp', 'desc' => 'Kệ hoa cao cấp – chúc đơm hoa kết trái, công thành danh toại.'],
                ['name' => 'Kệ Hoa Bước Thành Công',        'price' => 1990000, 'image' => 'https://tramhoa.com/wp-content/uploads/2022/10/ke-hoa-khai-truong-kt143-400x500.webp', 'desc' => 'Kệ hoa hoành tráng – chúc bước đường thành công luôn rộng mở.'],
                ['name' => 'Kệ Hoa Sắc Xanh',               'price' => 1490000, 'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt079-400x500.webp', 'desc' => 'Kệ hoa tone xanh – tươi mới, hợp phong thủy cho ngành dịch vụ.'],
            ],
        ];

        $sizePresets = [
            'hoa-sinh-nhat' => [
                ['size' => 'Bó vừa', 'price' => 0],
                ['size' => 'Bó lớn', 'price' => 150000],
                ['size' => 'Bó đại', 'price' => 320000],
            ],
            'hoa-tinh-yeu' => [
                ['size' => '20 bông', 'price' => 0],
                ['size' => '50 bông', 'price' => 350000],
                ['size' => '100 bông', 'price' => 850000],
            ],
            'hoa-chuc-mung' => [
                ['size' => 'Cỡ vừa', 'price' => 0],
                ['size' => 'Cỡ lớn', 'price' => 250000],
            ],
            'hoa-tang-le' => [
                ['size' => 'Tiêu chuẩn', 'price' => 0],
                ['size' => 'Cao cấp', 'price' => 400000],
            ],
            'hoa-khai-truong' => [
                ['size' => '1 tầng', 'price' => 0],
                ['size' => '2 tầng', 'price' => 300000],
                ['size' => '3 tầng', 'price' => 700000],
            ],
        ];

        // Pool màu sắc & nguyên liệu (chuẩn để filter trên trang shop).
        $colorPool = [
            'hoa-sinh-nhat' => [['Đỏ','Hồng','Trắng'], ['Pastel','Hồng'], ['Hồng','Trắng'], ['Đỏ','Hồng'], ['Đỏ'], ['Mix','Vàng','Cam'], ['Đỏ','Trắng'], ['Vàng','Cam']],
            'hoa-tinh-yeu' => [['Đỏ'], ['Đỏ'], ['Hồng','Pastel'], ['Tím','Hồng'], ['Đỏ','Hồng'], ['Đỏ','Hồng'], ['Trắng','Kem'], ['Pastel','Hồng']],
            'hoa-chuc-mung' => [['Vàng','Cam','Đỏ'], ['Vàng','Trắng'], ['Vàng','Đỏ'], ['Trắng'], ['Vàng','Hồng'], ['Mix','Vàng'], ['Vàng','Cam'], ['Mix','Đỏ','Vàng']],
            'hoa-tang-le' => [['Trắng'], ['Trắng','Vàng'], ['Trắng'], ['Trắng','Vàng'], ['Trắng'], ['Trắng'], ['Trắng','Vàng'], ['Trắng']],
            'hoa-khai-truong' => [['Đỏ','Vàng'], ['Vàng','Đỏ'], ['Đỏ'], ['Vàng','Trắng'], ['Hồng','Đỏ'], ['Đỏ','Vàng'], ['Đỏ','Vàng'], ['Mix','Vàng'], ['Đỏ','Vàng','Hồng'], ['Xanh']],
        ];
        $materialPool = [
            'hoa-sinh-nhat' => [['Hoa hồng','Baby'], ['Hoa cát tường','Baby'], ['Hoa hồng','Lá xanh'], ['Hoa hồng','Hoa cẩm chướng'], ['Hoa hồng','Hoa lan'], ['Hoa cẩm chướng','Hoa hồng'], ['Hoa hồng','Hoa lan hồ điệp'], ['Hoa hướng dương','Hoa cúc']],
            'hoa-tinh-yeu' => [['Hoa hồng','Lá xanh'], ['Hoa hồng'], ['Hoa hồng','Baby'], ['Hoa cẩm tú cầu','Hoa hồng'], ['Hoa hồng','Lá xanh'], ['Hoa hồng','Baby'], ['Hoa hồng kem','Lá xanh','Baby'], ['Hoa cát tường','Hoa hồng']],
            'hoa-chuc-mung' => [['Hoa cát tường','Hoa hồng','Lá xanh'], ['Hoa lan','Hoa cẩm chướng'], ['Hoa hồng','Hoa lay ơn'], ['Baby','Hoa hồng'], ['Hoa cẩm chướng','Hoa hồng'], ['Hoa cúc','Hoa hồng'], ['Hoa lan','Hoa hồng môn'], ['Hoa lan','Hoa cát tường']],
            'hoa-tang-le' => [['Hoa cúc trắng','Hoa lay ơn'], ['Hoa cúc trắng','Hoa hồng'], ['Hoa cúc trắng','Hoa lan'], ['Hoa cúc trắng','Hoa hồng'], ['Hoa cúc trắng','Lá xanh'], ['Hoa cúc trắng','Hoa lan'], ['Hoa cúc trắng','Hoa lay ơn'], ['Hoa cúc trắng','Hoa cát tường']],
            'hoa-khai-truong' => [['Hoa cát tường','Hoa hồng môn','Hoa lan'], ['Hoa lan','Hoa hồng môn'], ['Hoa hồng môn','Hoa cát tường'], ['Hoa lan hồ điệp','Hoa hồng'], ['Hoa hồng','Hoa cẩm chướng'], ['Hoa cát tường','Hoa lay ơn'], ['Hoa cát tường','Hoa đồng tiền'], ['Hoa lan','Hoa hồng môn','Hoa cát tường'], ['Hoa lan','Hoa hồng','Hoa cát tường'], ['Hoa cẩm tú cầu','Hoa lan','Lá xanh']],
        ];

        $allSlugs = [];

        foreach ($catalog as $catSlug => $items) {
            $category = Category::where('slug', $catSlug)->first();
            if (! $category) {
                continue;
            }

            $sizes = $sizePresets[$catSlug] ?? [
                ['size' => 'Tiêu chuẩn', 'price' => 0],
                ['size' => 'Nâng cấp', 'price' => 200000],
            ];

            foreach ($items as $i => $item) {
                $slug = Str::slug($catSlug.'-'.$item['name']);
                $allSlugs[] = $slug;

                Product::updateOrCreate(
                    ['slug' => $slug],
                    [
                        'name' => $item['name'],
                        'description' => $item['desc'],
                        'price' => $item['price'],
                        'stock' => random_int(15, 80),
                        'category_id' => $category->id,
                        'is_featured' => $i < 3,
                        'is_active' => true,
                        'image' => $item['image'],
                        'sizes' => $sizes,
                        'colors' => $colorPool[$catSlug][$i] ?? ['Mix'],
                        'materials' => $materialPool[$catSlug][$i] ?? ['Hoa hồng'],
                    ]
                );
            }
        }

        // Dọn các sản phẩm seed cũ (vd: ảnh Picsum của bản trước) để DB chỉ còn dữ liệu hiện hành.
        Product::query()
            ->whereNotIn('slug', $allSlugs)
            ->delete();
    }
}
