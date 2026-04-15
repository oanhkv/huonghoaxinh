@extends('frontend.layouts.app')

@section('title', 'Hương Hoa Xinh - Hoa Tươi Đẹp & Giao Nhanh')

@section('content')

    @php
        $heroImage = !empty($siteSettings['hero_image']) ? asset('storage/' . $siteSettings['hero_image']) : 'https://cdn4793.cdn4s2.com/media/logo/2.webp';
        $heroTitle = $siteSettings['hero_title'] ?? 'HOA TƯƠI - ĐẸP - SANG TRỌNG';
        $heroSubtitle = $siteSettings['hero_subtitle'] ?? 'Giao hoa tận nơi • Tươi lâu • Thiết kế theo yêu cầu';
        $heroButtonText = $siteSettings['hero_button_text'] ?? 'MUA HOA NGAY';
        $catalogMode = ($siteSettings['enable_catalog_mode'] ?? '0') === '1';
    @endphp

    <!-- Hero Banner -->
    <div class="position-relative">
        <img src="{{ $heroImage }}" class="w-100" style="height: 550px; object-fit: cover;" alt="Hero">
        <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
            <h1 class="display-3 fw-bold mb-3" style="text-shadow: 0 3px 10px rgba(0,0,0,0.6)">
                {{ $heroTitle }}
            </h1>
            <p class="lead mb-4">{{ $heroSubtitle }}</p>
            <a href="{{ route('shop') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold">
                {{ $heroButtonText }} <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>

    <!-- About Section -->
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">SHOP HOA TƯƠI UY TÍN VÀ SÁNG TẠO</h2>
                <p class="lead">
                    Hương Hoa Xinh - Cửa hàng hoa tươi nghệ thuật, uy tín và sáng tạo.
                    Hoa được tuyển chọn tươi từng cánh từ những vườn hoa uy tín.
                </p>
                <p>
                    Chúng tôi mang đến những bó hoa, giỏ hoa đẹp nhất dành tặng người thân,
                    bạn bè và người yêu với dịch vụ giao hàng nhanh chóng tại TP.HCM.
                </p>
                <a href="#" class="btn btn-success">Tìm hiểu thêm về chúng tôi →</a>
            </div>
            <div class="col-lg-6">
                <img src="https://cdn4793.cdn4s2.com/media/logo/2.webp" class="img-fluid rounded shadow" alt="Shop">
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">SẢN PHẨM NỔI BẬT</h2>
            <div class="row">
                @foreach($featuredProducts as $product)
                    <!-- Card sản phẩm với hiệu ứng hover -->
                    <div class="col-lg-3 col-md-4 col-6 mb-4">
                        <div class="product-card position-relative">
                            <div class="card h-100 border-0 shadow-sm overflow-hidden">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                        style="height: 260px; object-fit: cover;" alt="{{ $product->name }}">
                                @else
                                    <img src="https://via.placeholder.com/300x260" class="card-img-top" alt="{{ $product->name }}">
                                @endif

                                <!-- Overlay khi hover -->
                                <div class="product-overlay">
                                    <div class="d-flex justify-content-center gap-3">
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" title="Yêu thích">
                                            <i class="fas fa-heart text-danger"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" title="Thêm vào giỏ hàng">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </button>
                                        <a href="#" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>

                                <div class="card-body text-center">
                                    <h6 class="card-title mb-2">{{ $product->name }}</h6>
                                    <p class="text-danger fw-bold mb-0">{{ number_format($product->price) }} đ</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!-- Sản phẩm yêu thích / Nổi bật -->
    <!-- Sản phẩm yêu thích -->
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">SẢN PHẨM YÊU THÍCH</h2>
        <p class="text-muted">Những bó hoa được khách hàng yêu thích nhất</p>
    </div>

    <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-lg-3 col-md-4 col-6">
            <div class="product-card">
                <div class="card h-100 border-0 shadow-sm">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top" style="height: 260px; object-fit: cover;" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x260" class="card-img-top" alt="{{ $product->name }}">
                    @endif

                    <!-- Overlay khi hover -->
                    <div class="product-overlay">
                        <div class="d-flex">
                            <button class="btn" title="Yêu thích">
                                <i class="fas fa-heart"></i>
                            </button>
                            <button class="btn" title="Thêm vào giỏ hàng">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <a href="#" class="btn" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body text-center">
                        <h6 class="card-title mb-2">{{ $product->name }}</h6>
                        <p class="text-danger fw-bold mb-0">{{ number_format($product->price) }} đ</p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

        <div class="text-center mt-4">
            <a href="{{ route('shop') }}" class="btn btn-success px-5">
                Xem tất cả sản phẩm →
            </a>
        </div>
    </div>
    <!-- ==================== TẠI SAO CHỌN HƯƠNG HOA XINH ==================== -->
    <div class="bg-light py-5 mt-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="fw-bold">TẠI SAO CHỌN SẢN PHẨM CỦA CHÚNG TÔI?</h2>
                <p class="lead text-muted">
                    Không chỉ đơn thuần là cửa hàng hoa tươi, Hương Hoa Xinh còn là nơi kết nối cảm xúc -
                    mỗi bó hoa đều được cắm với cả trái tim và sự tỉ mỉ của người thợ hoa.
                </p>
            </div>

            <div class="row g-4">
                <!-- Lý do 1 -->
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-seedling fa-3x text-success"></i>
                        </div>
                        <h5 class="fw-bold">Hoa tươi 100% mỗi ngày</h5>
                        <p class="text-muted small">
                            Được tuyển chọn kỹ lưỡng từ những vườn hoa uy tín tại Đà Lạt và nhập khẩu trực tiếp.
                        </p>
                    </div>
                </div>

                <!-- Lý do 2 -->
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-crown fa-3x text-warning"></i>
                        </div>
                        <h5 class="fw-bold">Thiết kế theo yêu cầu</h5>
                        <p class="text-muted small">
                            Mỗi sản phẩm hoa là một tác phẩm nghệ thuật được chăm chút bởi đội ngũ florists chuyên nghiệp.
                        </p>
                    </div>
                </div>

                <!-- Lý do 3 -->
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-gift fa-3x text-primary"></i>
                        </div>
                        <h5 class="fw-bold">Tư vấn tận tâm - Tặng thiệp</h5>
                        <p class="text-muted small">
                            Giúp bạn chọn được hoa ý nghĩa nhất cho người thân, bạn bè, đối tác...
                        </p>
                    </div>
                </div>

                <!-- Lý do 4 -->
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="mb-3">
                            <i class="fas fa-clock fa-3x text-info"></i>
                        </div>
                        <h5 class="fw-bold">Giao hoa tận nơi</h5>
                        <p class="text-muted small">
                            Nhanh chóng, đúng hẹn, bảo quản lạnh trong suốt quá trình vận chuyển.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Lời kết -->
            <div class="text-center mt-5">
                <p class="fst-italic text-danger fw-medium">
                    Hãy để Hương Hoa Xinh giúp bạn kể câu chuyện của riêng mình bằng ngôn ngữ của sắc màu, hương thơm và
                    tình yêu.
                </p>
                <a href="{{ route('about') }}" class="btn btn-outline-success mt-3">
                    Tìm hiểu thêm về chúng tôi →
                </a>
            </div>
        </div>
    </div>

@endsection