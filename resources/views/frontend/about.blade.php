@extends('frontend.layouts.app')

@section('title', 'Về Chúng Tôi - Hương Hoa Xinh')

@section('content')
@php
    $shopAddress = config('shop.address_line');
@endphp
<div class="border-bottom bg-white">
    <div class="container py-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-7">
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 mb-3">Hương Hoa Xinh — từ 2018</span>
                <h1 class="display-5 fw-bold mb-3" style="letter-spacing: -0.02em;">Hoa tươi &amp; quà tặng — gần gũi, rõ ràng, đúng hẹn</h1>
                <p class="lead text-muted mb-4">
                    Chúng tôi đặt cửa hàng tại <strong>{{ $shopAddress }}</strong>, phục vụ khách trong khu vực <strong>Hà Nội</strong> và lân cận.
                    Mỗi đơn hàng đều được chốt phí ship minh bạch theo khoảng cách từ cửa hàng đến địa chỉ bạn nhận (ưu đãi trong bán kính 10&nbsp;km).
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('shop') }}" class="btn btn-success btn-lg rounded-pill px-4">Xem cửa hàng</a>
                    <a href="{{ route('contact') }}" class="btn btn-outline-secondary btn-lg rounded-pill px-4">Liên hệ đặt hoa</a>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="ratio ratio-4x3 rounded-4 overflow-hidden shadow" style="background: linear-gradient(145deg, rgba(25,135,84,0.15), rgba(233,30,140,0.12));">
                    <img src="https://images.unsplash.com/photo-1490750967868-88aa4486c946?w=900&auto=format&fit=crop&q=80"
                         class="object-fit-cover" alt="Hoa tươi Hương Hoa Xinh">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                <div class="icon-chip text-success mb-3"><i class="fas fa-store"></i></div>
                <h3 class="h5 fw-bold">Cửa hàng thật, địa chỉ rõ</h3>
                <p class="text-muted small mb-0">
                    Bạn có thể đến trực tiếp hoặc đặt online. Địa chỉ hiển thị thống nhất trên toàn website và dùng làm điểm gốc khi tính khoảng cách giao hàng.
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                <div class="icon-chip text-success mb-3"><i class="fas fa-truck-fast"></i></div>
                <h3 class="h5 fw-bold">Giao hàng &amp; phí ship</h3>
                <p class="text-muted small mb-0">
                    Hệ thống ước lượng quãng đường từ cửa hàng đến địa chỉ nhận. Trong phạm vi <strong>10&nbsp;km</strong> được miễn phí vận chuyển; xa hơn cộng phí cố định theo chính sách hiện hành (được hiển thị rõ ở bước thanh toán).
                </p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                <div class="icon-chip text-success mb-3"><i class="fas fa-shield-heart"></i></div>
                <h3 class="h5 fw-bold">Cam kết chất lượng</h3>
                <p class="text-muted small mb-0">
                    Hoa được chọn lọc, đóng gói cẩn thận. Nếu có sự cố, đội ngũ hỗ trợ sẽ đồng hành xử lý nhanh và công bằng với khách.
                </p>
            </div>
        </div>
    </div>

    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6 order-lg-2">
            <h2 class="fw-bold mb-4">Câu chuyện của chúng tôi</h2>
            <p class="text-muted">
                Hương Hoa Xinh bắt đầu từ niềm yêu những bó hoa nhỏ mang lại niềm vui lớn: sinh nhật, lễ kỷ niệm, một lời xin lỗi chân thành hay chỉ là “nhớ bạn” giữa ngày bận rộn.
                Chúng tôi tin rằng trải nghiệm mua hoa phải <strong>đơn giản</strong>: giá minh bạch, ảnh sát thực tế, giao đúng khung giờ đã thống nhất.
            </p>
            <ul class="list-unstyled text-muted small mb-0">
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Tư vấn màu sắc — chủ đề — ngân sách phù hợp.</li>
                <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Thiết kế theo mùa và theo yêu cầu (khi còn nguyên liệu).</li>
                <li><i class="fas fa-check text-success me-2"></i>Cập nhật ưu đãi, blog mẹo chọn hoa thường xuyên trên website.</li>
            </ul>
        </div>
        <div class="col-lg-6 order-lg-1">
            <div class="rounded-4 overflow-hidden shadow-sm">
                <img src="https://images.unsplash.com/photo-1455659817273-f968077798a5?w=900&auto=format&fit=crop&q=80"
                     class="img-fluid w-100" alt="Đội ngũ hoa Hương Hoa Xinh">
            </div>
        </div>
    </div>

    <div class="bg-light rounded-4 p-4 p-md-5 mb-5">
        <h2 class="fw-bold text-center mb-4">Giá trị cốt lõi</h2>
        <div class="row g-4 text-center">
            <div class="col-md-4">
                <i class="fas fa-leaf fa-2x text-success mb-3"></i>
                <h3 class="h6 fw-bold">Tươi mới</h3>
                <p class="text-muted small mb-0">Ưu tiên nguồn hoa ổn định, kiểm tra trước khi giao.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-hand-holding-heart fa-2x text-danger mb-3"></i>
                <h3 class="h6 fw-bold">Tận tâm</h3>
                <p class="text-muted small mb-0">Lắng nghe mong muốn — gợi ý thực tế, không “bán cho xong”.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-scale-balanced fa-2x text-primary mb-3"></i>
                <h3 class="h6 fw-bold">Minh bạch</h3>
                <p class="text-muted small mb-0">Giá, phí ship, mã giảm giá hiển thị rõ trước khi bạn xác nhận đơn.</p>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10 text-center">
            <h2 class="fw-bold mb-3">Cảm ơn bạn đã đồng hành</h2>
            <p class="text-muted mb-4">
                Mỗi đánh giá sau khi mua hàng đều giúp chúng tôi phục vụ tốt hơn. Hãy ghé cửa hàng online, chọn món quà nhỏ cho người bạn quý.
            </p>
            <a href="{{ route('shop') }}" class="btn btn-success btn-lg rounded-pill px-5">Khám phá sản phẩm</a>
        </div>
    </div>
</div>
@endsection
