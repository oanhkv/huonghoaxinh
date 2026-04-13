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
        <img src="{{ $heroImage }}" 
             class="w-100" style="height: 550px; object-fit: cover;" alt="Hero">
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
                <img src="https://cdn4793.cdn4s2.com/media/logo/2.webp" 
                     class="img-fluid rounded shadow" alt="Shop">
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">SẢN PHẨM NỔI BẬT</h2>
            <div class="row">
                @foreach($featuredProducts as $product)
                <div class="col-lg-3 col-md-4 col-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top" style="height: 220px; object-fit: cover;" 
                             alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h6 class="card-title">{{ $product->name }}</h6>
                            <p class="text-danger fw-bold fs-5">{{ number_format($product->price) }} đ</p>
                            @if($catalogMode)
                                <button class="btn btn-outline-secondary btn-sm w-100" disabled>Xem chi tiết</button>
                            @else
                                <button class="btn btn-outline-success btn-sm w-100">Thêm vào giỏ</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection