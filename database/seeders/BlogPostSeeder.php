<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mẹo chọn hoa', 'slug' => 'meo-chon-hoa', 'description' => 'Kinh nghiệm chọn hoa cho mọi dịp.'],
            ['name' => 'Hoa sinh nhật', 'slug' => 'blog-hoa-sinh-nhat', 'description' => 'Bộ sưu tập hoa đẹp dành tặng người thân.'],
            ['name' => 'Quà tặng', 'slug' => 'qua-tang', 'description' => 'Gợi ý quà tặng kèm hoa, thiệp và set đẹp.'],
            ['name' => 'Lễ tình nhân', 'slug' => 'le-tinh-nhan', 'description' => 'Hoa và quà cho những khoảnh khắc lãng mạn.'],
            ['name' => 'Trang trí nhà', 'slug' => 'trang-tri-nha', 'description' => 'Ý tưởng hoa & cây xanh cho không gian sống.'],
        ];

        foreach ($categories as $category) {
            BlogCategory::updateOrCreate(['slug' => $category['slug']], $category);
        }

        $posts = $this->postsData();

        foreach ($posts as $post) {
            $categoryId = BlogCategory::where('slug', $post['category_slug'])->value('id');

            BlogPost::updateOrCreate(
                ['slug' => $post['slug']],
                [
                    'title' => $post['title'],
                    'blog_category_id' => $categoryId,
                    'excerpt' => $post['excerpt'],
                    'content' => $post['content'],
                    'image' => $post['image'],
                    'is_active' => true,
                    'published_at' => now()->subDays($post['days_ago']),
                ]
            );
        }
    }

    private function postsData(): array
    {
        return [
            [
                'title' => 'Gợi ý tone màu hoa Valentine được yêu thích nhất',
                'slug' => 'goi-y-tone-mau-hoa-valentine-duoc-yeu-thich-nhat',
                'category_slug' => 'le-tinh-nhan',
                'excerpt' => 'Mỗi tone màu hoa Valentine đều mang một thông điệp riêng. Cùng khám phá 6 tone màu hot nhất giúp bạn ghi điểm tuyệt đối với người ấy.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2024/11/Bo-Hoa-Tuoi-TH-B012-Forever-Love-F-400x500.jpg',
                'days_ago' => 1,
                'content' => <<<'HTML'
<p>Valentine không chỉ là dịp để trao nhau những món quà, mà còn là cơ hội để ngôn ngữ của hoa <strong>nói thay tiếng lòng</strong>. Tone màu hoa bạn chọn sẽ quyết định cảm xúc người nhận – nồng nàn, dịu dàng hay sang trọng. Hãy cùng <em>Hương Hoa Xinh</em> điểm qua 6 tone màu hoa Valentine được yêu thích nhất năm nay.</p>

<h2>Vì sao tone màu hoa lại quan trọng trong Valentine?</h2>
<p>Khác với những dịp tặng hoa thông thường, Valentine đề cao yếu tố <strong>cảm xúc và cá tính</strong>. Mỗi tone màu mang một tầng nghĩa riêng:</p>
<ul>
    <li><strong>Tone đỏ</strong> – đam mê và cháy bỏng</li>
    <li><strong>Tone hồng</strong> – ngọt ngào và lãng mạn</li>
    <li><strong>Tone trắng</strong> – tinh khôi và trong trẻo</li>
    <li><strong>Tone pastel</strong> – nhẹ nhàng, hợp gen Z</li>
    <li><strong>Tone tím</strong> – sâu lắng và chung thuỷ</li>
    <li><strong>Tone vàng</strong> – ấm áp và tươi mới</li>
</ul>

<h2>Top 6 tone màu hoa Valentine được yêu thích nhất</h2>

<h3>1. Tone đỏ – biểu tượng vĩnh cửu của tình yêu</h3>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B014-Bo-Hoa-Do-Tham-400x500.webp" alt="Bó hoa Valentine tone đỏ">
    <figcaption>Bó hoa hồng đỏ thắm – lựa chọn kinh điển cho ngày Valentine.</figcaption>
</figure>
<p>Hoa hồng đỏ luôn đứng vị trí số 1 trong các dịp tỏ tình. Sắc đỏ rực rỡ tượng trưng cho <strong>tình yêu mãnh liệt và sự chiếm hữu</strong>. Bó hoa đỏ thường được kết hợp cùng baby trắng hoặc lá xanh để tăng độ tương phản, tạo cảm giác sang trọng và lãng mạn.</p>
<blockquote>"Một bó hoa hồng đỏ vào ngày Valentine không bao giờ là sai – chỉ là người tặng có đủ chân thành hay không."</blockquote>

<h3>2. Tone hồng – dịu dàng, lãng mạn và đầy cảm xúc</h3>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B016-Tinh-Hong-400x500.webp" alt="Bó hoa Valentine tone hồng">
    <figcaption>Tone hồng dành cho những cô gái yêu sự nhẹ nhàng và tinh tế.</figcaption>
</figure>
<p>Tone hồng phù hợp cho các cô gái yêu sự dịu dàng. Bạn có thể chọn hoa hồng phấn, hồng cánh sen hoặc hồng đào kết hợp với hoa cát tường, mẫu đơn để tạo nên bouquet ngọt ngào.</p>

<h3>3. Tone pastel – xu hướng được giới trẻ yêu thích</h3>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2023/02/Bo-Hoa-Tuoi-TH-B051-400x500.webp" alt="Bó hoa Valentine tone pastel">
    <figcaption>Pastel – tone màu trẻ trung, hợp xu hướng Hàn Quốc.</figcaption>
</figure>
<p>Pastel là tone màu lên ngôi mạnh mẽ vài năm gần đây nhờ sự ảnh hưởng của phong cách Hàn Quốc. Bouquets pastel thường mix nhiều loài hoa: hồng phấn, baby trắng, lavender, lá bạc – tạo cảm giác <strong>nhẹ tựa mây</strong>.</p>

<h3>4. Tone tím – lãng mạn, thuỷ chung và sâu sắc</h3>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B024-3-e1720603830156-400x500.webp" alt="Bó hoa Valentine tone tím">
</figure>
<p>Hoa tone tím – đặc biệt là hoa tử đinh hương, lavender và lan tím – mang ý nghĩa <strong>tình yêu chung thuỷ và sự say mê dài lâu</strong>. Đây là lựa chọn tuyệt vời cho các cặp đôi đã bên nhau lâu năm.</p>

<h3>5. Tone trắng – tinh khôi và trong trẻo</h3>
<p>Hoa trắng tưởng đơn giản nhưng lại rất khó "chơi". Khi được kết hợp đúng cách – baby trắng + hồng trắng + lan hồ điệp – chúng tạo ra vẻ đẹp <strong>thanh lịch và tinh khiết</strong> hiếm có.</p>

<h3>6. Tone vàng – tươi mới và ấm áp</h3>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B032-Anh-Duong-1-400x500.webp" alt="Bó hoa Valentine tone vàng">
</figure>
<p>Hướng dương và hoa cẩm chướng vàng mang đến cảm giác tươi sáng. Tone vàng phù hợp cho người yêu vui tính, năng động và ưa sự khác biệt.</p>

<h2>Nên chọn tone màu hoa Valentine theo phong cách nào?</h2>
<ul>
    <li><strong>Cô gái cá tính:</strong> tone đỏ hoặc tím đậm</li>
    <li><strong>Cô gái dịu dàng:</strong> tone hồng phấn, pastel</li>
    <li><strong>Cô gái sang trọng:</strong> tone trắng kết hợp lan hồ điệp</li>
    <li><strong>Cô gái năng động:</strong> tone vàng hoặc mix nhiều màu</li>
</ul>

<p>Đừng quên kèm theo <strong>thiệp viết tay</strong> – chính chữ viết của bạn mới là điểm nhấn cảm xúc lớn nhất. Hãy biến Valentine năm nay thành một kỷ niệm khó quên!</p>
HTML,
            ],

            [
                'title' => 'Cách chọn hoa hồng tươi cho người thương',
                'slug' => 'cach-chon-hoa-hong-tuoi-cho-nguoi-thuong',
                'category_slug' => 'meo-chon-hoa',
                'excerpt' => 'Bí quyết chọn hoa hồng tươi từng cánh, để mỗi món quà tặng đều mang trọn vẹn tình cảm và sự chỉn chu.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B025-I-Love-You-3-400x500.webp',
                'days_ago' => 5,
                'content' => <<<'HTML'
<p>Hoa hồng từ lâu đã là <strong>biểu tượng kinh điển</strong> của tình yêu. Nhưng để chọn được một bó hoa hồng vừa tươi, vừa đẹp lại đúng tâm trạng người nhận, không phải ai cũng làm được. Bài viết này sẽ giúp bạn nắm vững những tiêu chí quan trọng nhất.</p>

<h2>1. Quan sát cánh hoa và đài hoa</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2024/09/Bo-Hoa-Tuoi-TH-B008-F-400x500.jpg" alt="Cánh hoa hồng tươi">
    <figcaption>Cánh hoa hồng tươi luôn căng mọng, không có vết thâm.</figcaption>
</figure>
<p>Cánh hoa hồng tươi có đặc điểm:</p>
<ul>
    <li>Cánh <strong>căng, dày</strong>, không cong vênh</li>
    <li>Không có vết thâm, chấm đen hoặc cánh úa</li>
    <li>Đài hoa xanh đậm, ôm chặt nụ hoa – không bung quá rộng</li>
</ul>

<h2>2. Kiểm tra thân và lá</h2>
<p>Thân hoa hồng tươi cần <strong>thẳng, cứng cáp</strong>, không có dấu hiệu héo hoặc đen ở phần gốc. Lá xanh đậm, không vàng úa và còn đủ độ giòn khi sờ vào.</p>

<h2>3. Mùi hương – chỉ dấu của hoa thật sự tươi</h2>
<blockquote>Một bó hoa hồng tươi luôn có mùi hương dịu nhẹ, thoang thoảng. Nếu hoa không có mùi, có thể chúng đã được nhúng hoá chất bảo quản dài ngày.</blockquote>

<h2>4. Chọn màu phù hợp với người nhận</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2023/09/Bo-Hoa-Tuoi-TH-B010-400x500.webp" alt="Hoa hồng đỏ">
</figure>
<ul>
    <li><strong>Hồng đỏ:</strong> tỏ tình, tình yêu mãnh liệt</li>
    <li><strong>Hồng phấn:</strong> tình cảm dịu dàng, mới chớm</li>
    <li><strong>Hồng trắng:</strong> sự tinh khiết, lời cảm ơn</li>
    <li><strong>Hồng vàng:</strong> tình bạn, sự biết ơn</li>
    <li><strong>Hồng cam:</strong> đam mê, sự ngưỡng mộ</li>
</ul>

<h2>5. Kết hợp với phụ kiện</h2>
<p>Một bó hoa hồng đẹp luôn cần phụ kiện đi kèm: <strong>baby trắng, lá xanh, ruy băng và giấy gói cao cấp</strong>. Đừng tiếc tiền cho phần gói – đó là điều khách hàng nhìn thấy đầu tiên.</p>

<p>Chúc bạn chọn được bó hoa hồng ưng ý nhất. Nếu cần tư vấn, đội ngũ Hương Hoa Xinh luôn sẵn sàng đồng hành cùng bạn!</p>
HTML,
            ],

            [
                'title' => '5 kiểu bó hoa sinh nhật đẹp cho cô ấy',
                'slug' => '5-kieu-bo-hoa-sinh-nhat-dep-cho-co-ay',
                'category_slug' => 'blog-hoa-sinh-nhat',
                'excerpt' => 'Khám phá 5 kiểu bó hoa sinh nhật vừa sang trọng vừa ngọt ngào, dành tặng người phụ nữ đặc biệt trong đời bạn.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2024/09/Bo-Hoa-Tuoi-TH-B008-F-400x500.jpg',
                'days_ago' => 3,
                'content' => <<<'HTML'
<p>Sinh nhật là dịp đặc biệt để ta gửi gắm yêu thương qua những bó hoa tươi. Dưới đây là <strong>5 kiểu bó hoa sinh nhật</strong> được nhiều khách hàng yêu thích nhất tại Hương Hoa Xinh.</p>

<h2>1. Bó hoa hồng phấn pastel – ngọt ngào và nữ tính</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2023/02/Bo-Hoa-Tuoi-TH-B051-400x500.webp" alt="Bó hoa hồng phấn pastel">
</figure>
<p>Tone pastel hồng phấn phối cùng baby trắng và lá xanh tạo nên cảm giác <strong>nhẹ tựa mây</strong>. Phù hợp tặng các cô gái dịu dàng, nữ tính.</p>

<h2>2. Bó hoa hồng đỏ rượu – sang trọng và đẳng cấp</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B016-Tinh-Hong-400x500.webp" alt="Hoa hồng đỏ rượu">
</figure>
<p>Đỏ rượu là tone trầm sang trọng. Khi kết hợp với giấy gói nâu hoặc kraft, bó hoa toát lên <strong>vẻ đẹp cổ điển và quý phái</strong>.</p>

<h2>3. Bó hoa hướng dương – năng lượng tích cực</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B032-Anh-Duong-1-400x500.webp" alt="Hoa hướng dương">
</figure>
<p>Hướng dương đại diện cho <strong>niềm vui và sự lạc quan</strong>. Đây là lựa chọn hoàn hảo cho cô bạn năng động, vui vẻ.</p>

<h2>4. Bó hoa lan hồ điệp – đẳng cấp và tinh tế</h2>
<p>Lan hồ điệp mang vẻ đẹp <strong>thanh thoát, sang trọng</strong>. Phù hợp tặng các quý cô thành đạt hoặc dịp sinh nhật quan trọng.</p>

<h2>5. Bó hoa Forever Love – cho người ấy</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2024/11/Bo-Hoa-Tuoi-TH-B012-Forever-Love-F-400x500.jpg" alt="Bó hoa Forever Love">
</figure>
<p>Bó hoa hồng đỏ tone Forever Love là minh chứng cho <strong>tình yêu vĩnh cửu</strong>. Đặc biệt ý nghĩa khi tặng nửa kia trong ngày sinh nhật.</p>

<blockquote>"Hoa đẹp là chưa đủ – một bó hoa sinh nhật trọn vẹn phải đến đúng giờ và kèm thiệp tay."</blockquote>

<h2>Một vài lưu ý nhỏ khi đặt hoa sinh nhật</h2>
<ul>
    <li>Đặt trước ít nhất <strong>4-6 tiếng</strong> để florists có thời gian chuẩn bị</li>
    <li>Cung cấp đúng địa chỉ và khung giờ giao</li>
    <li>Không quên thiệp tay – chính chữ viết của bạn là điểm nhấn cảm xúc</li>
    <li>Có thể kèm thêm bánh sinh nhật, gấu bông cho trọn ý nghĩa</li>
</ul>
HTML,
            ],

            [
                'title' => 'Gợi ý quà tặng 20/10 cho mẹ và vợ',
                'slug' => 'goi-y-qua-tang-20-10-cho-me-va-vo',
                'category_slug' => 'qua-tang',
                'excerpt' => 'Những gợi ý hoa và quà tặng 20/10 ý nghĩa, giúp bạn ghi điểm trọn vẹn với hai người phụ nữ quan trọng nhất.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B016-Tinh-Hong-400x500.webp',
                'days_ago' => 2,
                'content' => <<<'HTML'
<p>Ngày 20/10 – Ngày Phụ nữ Việt Nam – là dịp tuyệt vời để bày tỏ lòng biết ơn tới <strong>mẹ, vợ và những người phụ nữ thân yêu</strong>. Bài viết này gợi ý cho bạn những món quà phù hợp nhất theo từng độ tuổi và mối quan hệ.</p>

<h2>Quà tặng 20/10 cho mẹ</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Lang-Hoa-De-Ban-TH-H020-e1720662707697-400x500.webp" alt="Lẵng hoa tặng mẹ">
    <figcaption>Lẵng hoa tone vàng – phù hợp tặng mẹ.</figcaption>
</figure>
<p>Mẹ thường yêu sự bình dị và ấm áp. Một số gợi ý:</p>
<ul>
    <li><strong>Lẵng hoa cẩm chướng + hồng vàng</strong> – mang ý nghĩa biết ơn</li>
    <li><strong>Hộp quà sức khoẻ</strong> – kết hợp hoa tươi và sản phẩm chăm sóc da</li>
    <li><strong>Bó hoa hướng dương</strong> – tươi vui, nhiều năng lượng</li>
</ul>

<h2>Quà tặng 20/10 cho vợ</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2024/11/Bo-Hoa-Tuoi-TH-B012-Forever-Love-F-400x500.jpg" alt="Bó hoa tặng vợ">
</figure>
<p>Với vợ, một bó hoa thôi đôi khi là chưa đủ. Hãy thêm một chút bất ngờ:</p>
<ul>
    <li>Bó hoa hồng đỏ + hộp socola hand-made</li>
    <li>Set quà gồm hoa tươi + nến thơm + thiệp tay</li>
    <li>Đặt bàn tại nhà hàng yêu thích kèm bó hoa nhỏ trên bàn</li>
</ul>

<blockquote>"Phụ nữ không cần quà đắt tiền – họ cần được nhớ đến đúng lúc."</blockquote>

<h2>Quà tặng 20/10 cho đồng nghiệp / sếp nữ</h2>
<p>Một <strong>bó hoa nhỏ</strong> tone hồng phấn hoặc tím nhạt kèm thiệp công ty là lựa chọn lịch sự và chuyên nghiệp.</p>

<h2>Vài mẹo nhỏ giúp ghi điểm</h2>
<ul>
    <li>Gửi hoa đến <strong>nơi làm việc</strong> trước giờ tan tầm – yếu tố bất ngờ rất quan trọng</li>
    <li>Viết thiệp bằng tay, dù chỉ vài chữ – vẫn ý nghĩa hơn thiệp in sẵn</li>
    <li>Kèm theo voucher spa, coffee đôi – tăng giá trị trải nghiệm</li>
</ul>

<p>Chúc bạn có một ngày 20/10 thật ấm áp và trọn vẹn ý nghĩa!</p>
HTML,
            ],

            [
                'title' => '4 cách giữ hoa tươi lâu sau khi nhận',
                'slug' => '4-cach-giu-hoa-tuoi-lau-sau-khi-nhan',
                'category_slug' => 'meo-chon-hoa',
                'excerpt' => 'Mẹo chăm sóc hoa đơn giản giúp mỗi bó hoa luôn tươi tắn và rực rỡ trên bàn của bạn lâu hơn 7-10 ngày.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/11/Lang-Hoa-De-Ban-TH-H077-Lang-Hoa-Baby-1-400x500.webp',
                'days_ago' => 4,
                'content' => <<<'HTML'
<p>Bạn vừa nhận được một bó hoa đẹp và muốn giữ chúng tươi lâu nhất có thể? Cùng tham khảo <strong>4 bí quyết đơn giản</strong> đã được kiểm chứng từ các florists chuyên nghiệp.</p>

<h2>1. Cắt gốc hoa theo góc 45 độ</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/11/Lang-Hoa-De-Ban-TH-H077-Lang-Hoa-Baby-1-400x500.webp" alt="Bình hoa baby">
</figure>
<p>Khi cắt gốc theo góc 45 độ, <strong>tiết diện hấp thụ nước tăng gấp đôi</strong> so với cắt thẳng. Hãy dùng kéo sắc, cắt dứt khoát để tránh dập thân.</p>

<h2>2. Đổi nước mỗi ngày + thêm chất bảo quản tự nhiên</h2>
<p>Đổi nước sạch vào bình mỗi ngày. Để tăng tuổi thọ:</p>
<ul>
    <li>Thêm <strong>1 thìa đường</strong> – cung cấp năng lượng cho hoa</li>
    <li>Hoặc <strong>1 viên aspirin</strong> – làm chậm quá trình thối rễ</li>
    <li>Hoặc <strong>vài giọt thuốc tẩy</strong> (rất ít) – kháng khuẩn nước</li>
</ul>

<blockquote>Mẹo nhỏ: pha nước với 1 lon Sprite (đường + acid citric) cũng giúp hoa tươi lâu đáng kinh ngạc.</blockquote>

<h2>3. Đặt hoa nơi mát, tránh ánh nắng trực tiếp</h2>
<p>Hoa rất nhạy cảm với <strong>nhiệt độ và độ ẩm</strong>. Hãy đặt bình hoa:</p>
<ul>
    <li>Tránh ánh nắng trực tiếp</li>
    <li>Tránh gần điều hoà thổi mạnh</li>
    <li>Tránh để cạnh trái cây chín (ethylene làm hoa nhanh tàn)</li>
</ul>

<h2>4. Tỉa lá ngập nước và những cánh hoa héo</h2>
<p>Lá ngập trong nước sẽ <strong>thối nhanh</strong> và lây sang phần còn lại. Mỗi ngày, hãy:</p>
<ul>
    <li>Cắt bỏ lá thấp ngập nước</li>
    <li>Loại bỏ cánh hoa héo, ngả vàng</li>
    <li>Cắt ngắn thân hoa khoảng 1-2 cm</li>
</ul>

<p>Áp dụng đủ 4 bước này, bó hoa của bạn có thể tươi <strong>7-10 ngày</strong> – thậm chí lâu hơn với một số loài như cát tường, baby và cẩm chướng.</p>
HTML,
            ],

            [
                'title' => 'Trang trí phòng khách bằng hoa tươi 4 mùa',
                'slug' => 'trang-tri-phong-khach-bang-hoa-tuoi-4-mua',
                'category_slug' => 'trang-tri-nha',
                'excerpt' => 'Bí quyết chọn hoa theo mùa để phòng khách luôn tươi mới, mang lại năng lượng tích cực cho không gian sống.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/09/Lang-Hoa-De-Ban-TH-H020-e1720662707697-400x500.webp',
                'days_ago' => 4,
                'content' => <<<'HTML'
<p>Một bình hoa tươi trên bàn phòng khách không chỉ làm <strong>đẹp không gian</strong>, mà còn nâng tinh thần và tạo ấn tượng tốt với khách. Dưới đây là gợi ý chọn hoa theo từng mùa.</p>

<h2>Mùa Xuân – tươi mới và tràn đầy hy vọng</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2024/06/Gio-Hoa-TH-G057-Chan-Phuong-1-400x500.jpg" alt="Hoa mùa xuân">
</figure>
<p>Mùa xuân – chọn các loài có <strong>màu sắc tươi sáng</strong>:</p>
<ul>
    <li>Hoa tulip – sang trọng và quyến rũ</li>
    <li>Hoa lan ý – thanh khiết</li>
    <li>Hoa cát tường – tươi vui, nhiều màu</li>
</ul>

<h2>Mùa Hạ – rực rỡ và năng động</h2>
<p>Mùa hạ hợp với những loài hoa <strong>chịu nắng tốt</strong>:</p>
<ul>
    <li>Hoa hướng dương – năng lượng tích cực</li>
    <li>Hoa cúc đại đoá – bền và đẹp</li>
    <li>Hoa baby trắng – làm dịu không khí nóng</li>
</ul>

<h2>Mùa Thu – ấm áp và lãng mạn</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Lang-Hoa-De-Ban-TH-H020-e1720662707697-400x500.webp" alt="Hoa mùa thu">
</figure>
<p>Mùa thu là mùa của tone <strong>cam, vàng và đỏ rượu</strong>:</p>
<ul>
    <li>Hoa cúc vàng</li>
    <li>Hoa mẫu đơn đỏ rượu</li>
    <li>Hoa cẩm chướng cam</li>
</ul>

<h2>Mùa Đông – ấm cúng và tinh tế</h2>
<p>Mùa đông cần những bình hoa mang lại <strong>cảm giác ấm</strong>:</p>
<ul>
    <li>Hoa hồng đỏ kết hợp lá thông</li>
    <li>Cành tuyết tùng + hoa baby trắng</li>
    <li>Hoa lan hồ điệp trắng</li>
</ul>

<blockquote>Mẹo phối bình hoa: chọn bình thuỷ tinh trong suốt cho không gian hiện đại, bình gốm sứ cho không gian cổ điển.</blockquote>

<h2>Một vài quy tắc khi cắm hoa phòng khách</h2>
<ul>
    <li>Bình hoa cao bằng <strong>1/3 chiều dài bàn</strong></li>
    <li>Số bông hoa nên là <strong>số lẻ</strong> (3, 5, 7…)</li>
    <li>Không che mất tầm nhìn khi ngồi đối diện</li>
    <li>Đặt bình hoa cách xa TV và thiết bị điện</li>
</ul>
HTML,
            ],

            [
                'title' => 'Hoa khai trương: chọn sao cho hợp phong thuỷ',
                'slug' => 'hoa-khai-truong-chon-sao-cho-hop-phong-thuy',
                'category_slug' => 'qua-tang',
                'excerpt' => 'Hướng dẫn chọn kệ hoa khai trương theo mệnh và phong thuỷ để mang lại tài lộc, may mắn cho ngày đại sự.',
                'image' => 'https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt106-khai-truong-hong-phat-400x500.webp',
                'days_ago' => 6,
                'content' => <<<'HTML'
<p>Kệ hoa khai trương không chỉ là <strong>vật trang trí</strong> – nó còn mang ý nghĩa phong thuỷ, đại diện cho lời chúc thành công và phát đạt. Dưới đây là cách chọn hoa khai trương theo mệnh và ngành nghề.</p>

<h2>Chọn hoa khai trương theo ngũ hành</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt106-khai-truong-hong-phat-400x500.webp" alt="Kệ hoa khai trương">
</figure>
<ul>
    <li><strong>Mệnh Hỏa:</strong> tone đỏ, cam, hồng đậm</li>
    <li><strong>Mệnh Thổ:</strong> tone vàng, nâu, cam đất</li>
    <li><strong>Mệnh Mộc:</strong> tone xanh lá, xanh ngọc</li>
    <li><strong>Mệnh Thuỷ:</strong> tone xanh dương, đen, tím</li>
    <li><strong>Mệnh Kim:</strong> tone trắng, bạc, vàng nhạt</li>
</ul>

<h2>Chọn loài hoa theo ý nghĩa</h2>
<figure>
    <img src="https://tramhoa.com/wp-content/uploads/2024/04/ke-hoa-khai-truong-kt056-sang-trong-400x500.jpg" alt="Hoa khai trương sang trọng">
</figure>
<ul>
    <li><strong>Hoa cát tường:</strong> mang lại điều tốt lành, sự may mắn</li>
    <li><strong>Hoa đồng tiền:</strong> tài lộc, làm ăn phát đạt</li>
    <li><strong>Hoa lay ơn:</strong> kiên định, vững chắc trong công việc</li>
    <li><strong>Hoa hồng môn:</strong> đỏ rực rỡ, hút tài lộc</li>
    <li><strong>Hoa lan hồ điệp:</strong> sang trọng, chiêu hiền đãi sĩ</li>
</ul>

<blockquote>Theo phong thuỷ, kệ hoa khai trương lý tưởng có chiều cao 1,6-1,8m – tượng trưng cho sự "phát triển vươn lên".</blockquote>

<h2>Số lượng hoa và tầng kệ – đừng xem nhẹ!</h2>
<p>Người Việt thường chọn số lẻ vì mang ý nghĩa <strong>phát triển</strong>:</p>
<ul>
    <li><strong>1 tầng:</strong> phù hợp khai trương quy mô nhỏ, tone đơn giản</li>
    <li><strong>2 tầng:</strong> sang trọng vừa phải, hợp văn phòng</li>
    <li><strong>3 tầng:</strong> đại diện cho "Tam Tài" – Thiên, Địa, Nhân</li>
</ul>

<h2>Kèm theo lời chúc – đừng quên!</h2>
<p>Một kệ hoa khai trương không thể thiếu <strong>băng rôn lời chúc</strong>. Một số mẫu phổ biến:</p>
<ul>
    <li>"Khai trương đại cát – Vạn sự hanh thông"</li>
    <li>"Chúc mừng khai trương – Phát tài phát lộc"</li>
    <li>"Đơm hoa kết trái – Buôn may bán đắt"</li>
</ul>

<p>Chúc bạn và đối tác có một ngày khai trương thuận lợi, vạn sự như ý!</p>
HTML,
            ],
        ];
    }
}
