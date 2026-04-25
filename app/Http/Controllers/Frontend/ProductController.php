<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'visibleReviews.user'])
            ->firstOrFail();

        // Lấy sản phẩm liên quan (cùng danh mục)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        // Tự sinh "ý nghĩa" và "dịp phù hợp" theo danh mục để hiển thị tab Mô tả/Ý nghĩa.
        $meaningByCategory = [
            'hoa-sinh-nhat' => 'Bó hoa sinh nhật là lời chúc trọn vẹn cho một tuổi mới rạng rỡ. Mỗi cánh hoa gửi gắm ước mong sức khoẻ, niềm vui và những điều tốt đẹp đang chờ đón phía trước.',
            'hoa-tinh-yeu' => 'Hoa tình yêu là ngôn ngữ của trái tim. Tone đỏ thắm tượng trưng cho tình yêu nồng nàn, hồng phấn cho sự ngọt ngào tinh tế, còn pastel mang vẻ lãng mạn dịu dàng cho những đôi tình nhân.',
            'hoa-chuc-mung' => 'Lẵng hoa chúc mừng truyền tải sự chân thành và niềm tin vào thành công. Đây là món quà sang trọng dành cho những cột mốc đáng nhớ – lễ kỷ niệm, tốt nghiệp, thăng chức.',
            'hoa-tang-le' => 'Hoa kính viếng – tone trắng thanh khiết – là lời tiễn biệt trang nghiêm và đầy thành kính, gửi đến gia quyến sự đồng cảm và sẻ chia trong giờ phút khó khăn.',
            'hoa-khai-truong' => 'Kệ hoa khai trương đại diện cho lời chúc tài lộc, vạn sự hanh thông. Tone đỏ vàng kết hợp hoa cát tường, hoa hồng môn mang tới năng lượng phát đạt cho ngày mở hàng.',
        ];
        $occasionByCategory = [
            'hoa-sinh-nhat' => ['Sinh nhật', 'Tặng bạn bè', 'Tặng người yêu', 'Tặng đồng nghiệp'],
            'hoa-tinh-yeu' => ['Valentine 14/02', 'Kỷ niệm yêu nhau', 'Tỏ tình', '20/10 - 8/3'],
            'hoa-chuc-mung' => ['Lễ kỷ niệm', 'Tốt nghiệp', 'Thăng chức', 'Sự kiện công ty'],
            'hoa-tang-le' => ['Lễ tang', 'Viếng người đã khuất', 'Đám giỗ', 'Tiễn biệt'],
            'hoa-khai-truong' => ['Khai trương', 'Khánh thành', 'Mở chi nhánh', 'Ra mắt sản phẩm'],
        ];

        $meaning = $meaningByCategory[$product->category->slug] ?? 'Bó hoa được tuyển chọn từ những bông hoa tươi nhất, kết hợp tinh tế để mang đến vẻ đẹp trọn vẹn cho mọi dịp.';
        $occasions = $occasionByCategory[$product->category->slug] ?? ['Tặng quà', 'Trang trí', 'Sự kiện'];

        // Bảng dot màu hiển thị (đồng bộ với ShopController).
        $colorDots = [
            'Đỏ' => '#dc3545', 'Hồng' => '#f06595', 'Trắng' => '#f8f9fa',
            'Vàng' => '#ffc107', 'Cam' => '#fd7e14', 'Tím' => '#9d4edd',
            'Xanh' => '#198754', 'Pastel' => '#fbcfe8', 'Kem' => '#fde68a',
            'Mix' => 'linear-gradient(135deg, #dc3545, #ffc107, #198754, #9d4edd)',
        ];

        $canReview = false;
        $userReview = null;
        if (Auth::check()) {
            $userReview = Review::where('user_id', Auth::id())
                ->where('product_id', $product->id)
                ->first();

            if (! $userReview) {
                $canReview = Order::where('user_id', Auth::id())
                    ->whereIn('status', ['delivered', 'paid'])
                    ->whereHas('orderItems', function ($q) use ($product) {
                        $q->where('product_id', $product->id);
                    })
                    ->exists();
            }
        }

        return view('frontend.product.show', compact(
            'product', 'relatedProducts', 'canReview', 'userReview',
            'meaning', 'occasions', 'colorDots'
        ));
    }
}
