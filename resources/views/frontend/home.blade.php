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

    <!-- Banner Carousel -->
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <!-- Slide 1: Main Banner -->
            <div class="carousel-item active">
                <div class="position-relative">
                    <img src="{{ $heroImage }}" class="d-block w-100" style="height: 550px; object-fit: cover;" alt="Banner 1">
                    <div class="position-absolute top-50 start-50 translate-middle text-center text-white w-100 px-4">
                        <h1 class="display-3 fw-bold mb-3" style="text-shadow: 0 3px 10px rgba(0,0,0,0.6); animation: slideInDown 0.8s ease-in-out;">
                            {{ $heroTitle }}
                        </h1>
                        <p class="lead mb-4" style="text-shadow: 0 2px 5px rgba(0,0,0,0.6); animation: slideInUp 0.8s ease-in-out;">
                            {{ $heroSubtitle }}
                        </p>
                        <a href="{{ route('shop') }}" class="btn btn-light btn-lg px-5 py-3 fw-bold" style="animation: fadeIn 1s ease-in-out 0.4s both;">
                            {{ $heroButtonText }} <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Slide 2: Promotional Banner -->
            <div class="carousel-item">
                <div class="position-relative overflow-hidden">
                    <div style="height: 550px; background: linear-gradient(135deg, #FFE5E5 0%, #FFF5E5 100%); display: flex; align-items: center; justify-content: center; position: relative;">
                        <!-- Floral decoration elements -->
                        <div style="position: absolute; left: -50px; top: -50px; font-size: 200px; opacity: 0.15; transform: rotate(-25deg);">🌹</div>
                        <div style="position: absolute; right: -30px; bottom: -30px; font-size: 180px; opacity: 0.12; transform: rotate(45deg);">🌺</div>
                        <div style="position: absolute; left: 10%; bottom: 5%; font-size: 140px; opacity: 0.1;">🌸</div>
                        
                        <div class="row align-items-center w-100 h-100 px-4 z-1">
                            <div class="col-lg-6 text-center text-lg-start">
                                <div class="mb-3" style="font-size: 80px; animation: bounce 2s infinite;">🎁</div>
                                <h2 class="display-4 fw-bold mb-3" style="color: #d63384; animation: slideInDown 0.8s ease-in-out;">
                                    KHUYẾN MÃI ĐẶC BIỆT
                                </h2>
                                <p class="lead mb-4" style="color: #666; animation: slideInUp 0.8s ease-in-out;">
                                    Giảm tới 40% cho đơn hàng đầu tiên của bạn
                                </p>
                                <a href="{{ route('vouchers') }}" class="btn btn-danger btn-lg px-5 py-3 fw-bold" style="animation: fadeIn 1s ease-in-out 0.4s both;">
                                    Xem Mã Giảm Giá <i class="fas fa-tag ms-2"></i>
                                </a>
                            </div>
                            <div class="col-lg-6 text-center" style="font-size: 150px; animation: float 3s ease-in-out infinite;">
                                🌷
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 3: Service Banner -->
            <div class="carousel-item">
                <div class="position-relative overflow-hidden">
                    <div style="height: 550px; background: linear-gradient(135deg, #E5F5E5 0%, #E5F0FF 100%); display: flex; align-items: center; justify-content: center; position: relative;">
                        <!-- Floral decoration elements -->
                        <div style="position: absolute; right: -60px; top: -40px; font-size: 220px; opacity: 0.15; transform: rotate(30deg);">🌻</div>
                        <div style="position: absolute; left: -40px; bottom: -50px; font-size: 180px; opacity: 0.12; transform: rotate(-20deg);">🌼</div>
                        <div style="position: absolute; right: 8%; top: 10%; font-size: 130px; opacity: 0.1;">💐</div>
                        
                        <div class="row align-items-center w-100 h-100 px-4 z-1">
                            <div class="col-lg-6 text-center" style="font-size: 140px; animation: bounce 2s infinite;">
                                🚚
                            </div>
                            <div class="col-lg-6 text-center text-lg-end">
                                <h2 class="display-4 fw-bold mb-3" style="color: #28a745; animation: slideInDown 0.8s ease-in-out;">
                                    GIAO HÀNG NHANH & MIỄN PHÍ
                                </h2>
                                <p class="lead mb-4" style="color: #666; animation: slideInUp 0.8s ease-in-out;">
                                    Giao hàng cùng ngày cho các đơn hàng trước 12h trưa
                                </p>
                                <a href="{{ route('shop') }}" class="btn btn-success btn-lg px-5 py-3 fw-bold" style="animation: fadeIn 1s ease-in-out 0.4s both;">
                                    Đặt Hàng Ngay <i class="fas fa-shopping-cart ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Carousel Controls -->
        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <style>
        @keyframes slideInDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideInUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-30px);
            }
        }

        #bannerCarousel .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.8);
            background-color: rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }

        #bannerCarousel .carousel-indicators button.active {
            background-color: #fff;
            border-color: #fff;
            width: 30px;
            border-radius: 5px;
        }

        #bannerCarousel .carousel-control-prev-icon,
        #bannerCarousel .carousel-control-next-icon {
            filter: drop-shadow(0 2px 5px rgba(0,0,0,0.3));
        }
    </style>

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
                <a href="{{ route('about') }}" class="btn btn-success">Tìm hiểu thêm về chúng tôi →</a>
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
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" title="Yêu thích" onclick="toggleWishlist({{ $product->id }})">
                                            <i class="fas fa-heart text-danger"></i>
                                        </button>
                                        <button class="btn btn-light btn-sm rounded-circle shadow-sm" title="Thêm vào giỏ hàng" onclick="addToCart({{ $product->id }})">
                                            <i class="fas fa-shopping-cart text-success"></i>
                                        </button>
                                        <a href="{{ route('product.show', $product->slug) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Xem chi tiết">
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
@auth
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold"><i class="fas fa-heart text-danger me-2"></i>SẢN PHẨM YÊU THÍCH CỦA BẠN</h2>
        <p class="text-muted">Những sản phẩm bạn đã yêu thích</p>
    </div>

    @if($userWishlists->count() > 0)
        <div class="row g-4">
            @foreach($userWishlists as $wishlist)
            @php $product = $wishlist->product; @endphp
            <div class="col-lg-3 col-md-4 col-6">
                <div class="product-card">
                    <div class="card h-100 border-0 shadow-sm overflow-hidden">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="card-img-top" style="height: 260px; object-fit: cover;" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x260" class="card-img-top" alt="{{ $product->name }}">
                        @endif

                        <!-- Overlay khi hover -->
                        <div class="product-overlay">
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm" onclick="toggleWishlist({{ $product->id }})" title="Xóa khỏi yêu thích">
                                    <i class="fas fa-heart-broken"></i>
                                </button>
                                <button class="btn btn-sm" onclick="addToCart({{ $product->id }})" title="Thêm vào giỏ hàng">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body text-center">
                            <h6 class="card-title mb-2">{{ Str::limit($product->name, 40) }}</h6>
                            <p class="text-success fw-bold mb-0">{{ number_format($product->price, 0, ',', '.') }} ₫</p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('wishlist.index') }}" class="btn btn-success px-5">
                Xem tất cả sản phẩm yêu thích →
            </a>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-heart fa-4x text-muted mb-3" style="opacity: 0.3;"></i>
            <p class="text-muted">Bạn chưa có sản phẩm yêu thích nào</p>
            <a href="{{ route('shop') }}" class="btn btn-success mt-3">
                Khám phá sản phẩm →
            </a>
        </div>
    @endif
</div>
@else
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">SẢN PHẨM NỐI BẬT</h2>
        <p class="text-muted">Những bó hoa được khách hàng yêu thích nhất</p>
    </div>

    <div class="row g-4">
        @foreach($featuredProducts as $product)
        <div class="col-lg-3 col-md-4 col-6">
            <div class="product-card">
                <div class="card h-100 border-0 shadow-sm overflow-hidden">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top" style="height: 260px; object-fit: cover;" alt="{{ $product->name }}">
                    @else
                        <img src="https://via.placeholder.com/300x260" class="card-img-top" alt="{{ $product->name }}">
                    @endif

                    <!-- Overlay khi hover -->
                    <div class="product-overlay">
                        <div class="d-flex gap-2">
                            <button class="btn btn-sm" onclick="toggleWishlist({{ $product->id }})" title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                            </button>
                            <button class="btn btn-sm" onclick="addToCart({{ $product->id }})" title="Thêm vào giỏ hàng">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm" title="Xem chi tiết">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>

                    <div class="card-body text-center">
                        <h6 class="card-title mb-2">{{ Str::limit($product->name, 40) }}</h6>
                        <p class="text-success fw-bold mb-0">{{ number_format($product->price, 0, ',', '.') }} ₫</p>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('shop') }}" class="btn btn-success px-5">
            Xem tất cả sản phẩm →
        </a>
    </div>
</div>
@endauth
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