<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $blogs = [
            [
                'id' => 1,
                'slug' => 'huong-hoa-la-gi',
                'title' => 'Hương Hoa là gì?',
                'excerpt' => 'Khám phá về các loại hương hoa phổ biến và công dụng của chúng trong đời sống hàng ngày.',
                'content' => 'Hương hoa là những loài hoa có mùi thơm đặc trưng, được sử dụng trong nhiều lĩnh vực như trang trí, làm đẹp, hoặc y học cổ truyền. Các loại hoa này không chỉ đẹp về hình dáng mà còn mang lại những lợi ích tuyệt vời cho sức khỏe và tinh thần của con người.',
                'image' => 'images/blog1.jpg',
                'category' => 'Kiến thức',
                'author' => 'Admin',
                'date' => '2026-04-10',
                'read_time' => '5 phút'
            ],
            [
                'id' => 2,
                'slug' => 'cach-chon-hoa-tuoi',
                'title' => 'Cách chọn hoa tươi',
                'excerpt' => 'Hướng dẫn chi tiết cách chọn hoa tươi để đảm bảo chất lượng và độ bền lâu nhất.',
                'content' => 'Khi chọn hoa tươi, bạn nên chú ý đến màu sắc của các cánh hoa, độ cứng của thân hoa, và mùi thơm tự nhiên. Hoa tươi sẽ có cánh hoa mềm mại, màu sắc tươi sáng, không bị úa hay héo.',
                'image' => 'images/blog2.jpg',
                'category' => 'Mẹo & Kinh nghiệm',
                'author' => 'Admin',
                'date' => '2026-04-08',
                'read_time' => '4 phút'
            ],
            [
                'id' => 3,
                'slug' => 'cach-bao-quan-hoa',
                'title' => 'Cách bảo quản hoa lâu hơn',
                'excerpt' => 'Những bí quyết để giữ cho hoa luôn tươi sáng, đẹp đẽ trong thời gian dài.',
                'content' => 'Để hoa tươi lâu, bạn cần cắt thân hoa ở góc 45 độ, thay nước mỗi 2 ngày, và để hoa ở nơi mát mẻ, tránh ánh nắng trực tiếp. Bạn cũng có thể thêm chút đường hoặc muối vào nước để hoa tươi lâu hơn.',
                'image' => 'images/blog3.jpg',
                'category' => 'Mẹo & Kinh nghiệm',
                'author' => 'Admin',
                'date' => '2026-04-05',
                'read_time' => '6 phút'
            ],
            [
                'id' => 4,
                'slug' => 'rau-muong-loai-hoa-co-loi-ich-suc-khoe',
                'title' => 'Rau muống - Loài hoa có lợi ích sức khỏe',
                'excerpt' => 'Tìm hiểu thêm về những lợi ích sức khỏe tuyệt vời từ các loại hoa thơm.',
                'content' => 'Rau muống không chỉ là một loại rau mà còn có nhiều công dụng thuốc tính quý báu được sử dụng trong y học cổ truyền.',
                'image' => 'images/blog4.jpg',
                'category' => 'Kiến thức',
                'author' => 'Admin',
                'date' => '2026-04-03',
                'read_time' => '5 phút'
            ],
            [
                'id' => 5,
                'slug' => 'tin-tuc-khai-trương',
                'title' => 'Tin tức: Cửa hàng mới khai trương',
                'excerpt' => 'Chúng tôi vừa mở cửa hàng chi nhánh mới tại Quận 7, TP.HCM.',
                'content' => 'Với mục tiêu mang những bông hoa đẹp nhất đến gần hơn với bạn, chúng tôi vừa khai trương chi nhánh mới.',
                'image' => 'images/blog5.jpg',
                'category' => 'Tin tức',
                'author' => 'Admin',
                'date' => '2026-04-01',
                'read_time' => '3 phút'
            ],
        ];

        $categories = ['Kiến thức', 'Mẹo & Kinh nghiệm', 'Tin tức'];
        $selectedCategory = $request->get('category', null);

        if ($selectedCategory && in_array($selectedCategory, $categories)) {
            $blogs = array_filter($blogs, function($blog) use ($selectedCategory) {
                return $blog['category'] === $selectedCategory;
            });
        }

        return view('frontend.blog.index', compact('blogs', 'categories', 'selectedCategory'));
    }

    public function show($slug)
    {
        $blogs = [
            [
                'id' => 1,
                'slug' => 'huong-hoa-la-gi',
                'title' => 'Hương Hoa là gì?',
                'excerpt' => 'Khám phá về các loại hương hoa phổ biến và công dụng của chúng trong đời sống hàng ngày.',
                'content' => 'Hương hoa là những loài hoa có mùi thơm đặc trưng, được sử dụng trong nhiều lĩnh vực như trang trí, làm đẹp, hoặc y học cổ truyền. Các loại hoa này không chỉ đẹp về hình dáng mà còn mang lại những lợi ích tuyệt vời cho sức khỏe và tinh thần của con người. Từ những bông hồng thơm ngát đến những cánh huệ tinh tế, các loại hương hoa đều có những đặc điểm riêng biệt mà chúng ta có thể khám phá.',
                'image' => 'images/blog1.jpg',
                'category' => 'Kiến thức',
                'author' => 'Admin',
                'date' => '2026-04-10',
                'read_time' => '5 phút'
            ],
            [
                'id' => 2,
                'slug' => 'cach-chon-hoa-tuoi',
                'title' => 'Cách chọn hoa tươi',
                'excerpt' => 'Hướng dẫn chi tiết cách chọn hoa tươi để đảm bảo chất lượng và độ bền lâu nhất.',
                'content' => 'Khi chọn hoa tươi, bạn nên chú ý đến màu sắc của các cánh hoa, độ cứng của thân hoa, và mùi thơm tự nhiên. Hoa tươi sẽ có cánh hoa mềm mại, màu sắc tươi sáng, không bị úa hay héo. Hãy tìm kiếm những cánh hoa không có vết bẹp hay tổn thương. Thân hoa cũng nên xanh tươi và không có dấu hiệu thối rữa.',
                'image' => 'images/blog2.jpg',
                'category' => 'Mẹo & Kinh nghiệm',
                'author' => 'Admin',
                'date' => '2026-04-08',
                'read_time' => '4 phút'
            ],
            [
                'id' => 3,
                'slug' => 'cach-bao-quan-hoa',
                'title' => 'Cách bảo quản hoa lâu hơn',
                'excerpt' => 'Những bí quyết để giữ cho hoa luôn tươi sáng, đẹp đẽ trong thời gian dài.',
                'content' => 'Để hoa tươi lâu, bạn cần cắt thân hoa ở góc 45 độ, thay nước mỗi 2 ngày, và để hoa ở nơi mát mẻ, tránh ánh nắng trực tiếp. Bạn cũng có thể thêm chút đường hoặc muối vào nước để hoa tươi lâu hơn. Ngoài ra, hãy loại bỏ những cánh hoa héo hoặc lá rụng để tránh vi khuẩn phát triển. Hoa nên được để ở độ ẩm từ 60-80% và nhiệt độ từ 15-20 độ C.',
                'image' => 'images/blog3.jpg',
                'category' => 'Mẹo & Kinh nghiệm',
                'author' => 'Admin',
                'date' => '2026-04-05',
                'read_time' => '6 phút'
            ],
        ];

        $blog = collect($blogs)->firstWhere('slug', $slug);

        if (!$blog) {
            abort(404, 'Bài viết không tồn tại');
        }

        return view('frontend.blog.show', compact('blog', 'blogs'));
    }
}
