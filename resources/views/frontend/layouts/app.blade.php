<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName = $siteSettings['site_name'] ?? 'Hương Hoa Xinh';
        $hotline = $siteSettings['hotline'] ?? '0859 773 086';
        $shippingNote = $siteSettings['free_shipping_note'] ?? 'Giao hoa nhanh nội thành TP.HCM trong 2 giờ';
        $supportEmail = $siteSettings['support_email'] ?? 'support@huonghoaxinh.vn';
        $address = $siteSettings['address'] ?? 'Quận Gò Vấp, TP.HCM';
        $logo = $siteSettings['logo'] ?? '';
        $facebookUrl = $siteSettings['facebook_url'] ?? '#';
        $instagramUrl = $siteSettings['instagram_url'] ?? '#';
        $youtubeUrl = $siteSettings['youtube_url'] ?? '#';
        $zaloUrl = $siteSettings['zalo_url'] ?? '#';
    @endphp

    <title>@yield('title', $siteSettings['meta_title'] ?? $siteName) - {{ $siteName }}</title>
    <meta name="description" content="{{ $siteSettings['meta_description'] ?? '' }}">
    <meta name="keywords" content="{{ $siteSettings['meta_keywords'] ?? '' }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        .navbar-nav .dropdown-menu { 
            box-shadow: 0 5px 15px rgba(0,0,0,0.1); 
        }
        .nav-link { 
            font-weight: 500; 
            color: #333 !important; 
        }
        .nav-link:hover, .nav-link.active { 
            color: #198754 !important; 
        }
        footer a { 
            color: #ddd; 
            text-decoration: none; 
        }
        footer a:hover { 
            color: #fff; 
        }
        /* Hiệu ứng Overlay cho sản phẩm */
.product-card {
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.product-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 2;
}

.product-card:hover .product-overlay {
    opacity: 1;
}

.product-overlay .btn {
    width: 48px;
    height: 48px;
    padding: 0;
    margin: 0 6px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    color: #333;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    transition: all 0.2s ease;
}

.product-overlay .btn:hover {
    transform: scale(1.1);
    background: #198754;
    color: white;
}

.banner-slider {
    position: relative;
    overflow: hidden;
    min-height: 560px;
}

.banner-slider .slider-track {
    display: flex;
    transition: transform 0.6s ease;
}

.banner-slider .slider-item {
    min-width: 100%;
    height: 560px;
    background-size: cover;
    background-position: center;
    position: relative;
}

.banner-slider .slider-item::before {
    content: '';
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.35);
}

.banner-slider .slider-content {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 0 24px;
}

.banner-slider .slider-content h1 {
    color: white;
    font-size: 3.5rem;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.banner-slider .slider-content p {
    color: white;
    font-size: 1.25rem;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
}

.slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,0.9);
    border: none;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #333;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 10;
}

.slider-nav:hover {
    background: white;
    transform: translateY(-50%) scale(1.1);
}

.slider-nav.prev {
    left: 20px;
}

.slider-nav.next {
    right: 20px;
}

.slider-dots {
    position: absolute;
    bottom: 30px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 10;
}

.slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: all 0.3s ease;
}

.slider-dot.active {
    background: white;
}
    color: #fff;
    z-index: 1;
}

.banner-slider .slider-content h1 {
    font-size: clamp(2.6rem, 4vw, 4.8rem);
    line-height: 1.05;
}

.banner-slider .slider-content p {
    max-width: 700px;
    margin: 1rem auto 1.8rem;
    font-size: 1.15rem;
}

.banner-slider .slider-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 54px;
    height: 54px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.85);
    color: #198754;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 2;
}

.banner-slider .slider-nav:hover {
    background: #fff;
}

.banner-slider .slider-nav.prev {
    left: 16px;
}

.banner-slider .slider-nav.next {
    right: 16px;
}

.banner-slider .slider-dots {
    position: absolute;
    left: 50%;
    bottom: 18px;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
    z-index: 2;
}

.banner-slider .slider-dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.7);
    cursor: pointer;
    border: none;
    outline: none;
    transition: transform 0.2s ease, background 0.2s ease;
}

.banner-slider .slider-dot:focus {
    outline: none;
}

.banner-slider .slider-dot.active {
    transform: scale(1.2);
    background: #198754;
}

@media (max-width: 768px) {
    .banner-slider {
        min-height: 420px;
    }
    .banner-slider .slider-content h1 {
        font-size: 2.4rem;
    }
    .banner-slider .slider-content p {
        font-size: 1rem;
    }
    .banner-slider .slider-nav {
        width: 44px;
        height: 44px;
    }
}
    </style>

</head>
<body>

    <!-- ==================== HEADER & NAVBAR ==================== -->
    <header>
        <!-- Top Bar -->
        <div class="bg-light py-2 border-bottom">
            <div class="container">
                <div class="row align-items-center small">
                    <div class="col-md-6">
                        <i class="fas fa-phone text-success"></i> 
                        <strong>Hotline:</strong> {{ $hotline }} - Hỗ trợ 24/7
                    </div>
                    <div class="col-md-6 text-md-end">
                        {{ $shippingNote }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo + Search + Account -->
        <div class="bg-white shadow-sm py-3">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Logo -->
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('home') }}" class="text-decoration-none">
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteName }}" style="height: 44px; object-fit: contain;">
                            @else
                                <h2 class="fw-bold text-success mb-0">🌸 {{ $siteName }}</h2>
                            @endif
                        </a>
                    </div>

                    <!-- Search -->
                    <div class="col-lg-5 col-12 my-3 my-lg-0">
                        <form class="d-flex" action="{{ route('shop') }}">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Tìm kiếm hoa tươi, bó hoa...">
                            <button class="btn btn-success ms-2"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <!-- Icons + Login/Logout -->
                    <div class="col-lg-4 col-6 text-end">
                        @php $favoriteCount = collect(session('favorites', []))->count(); @endphp
                        <a href="{{ route('favorites.index') }}" class="text-decoration-none me-4 position-relative">
                            <i class="fas fa-heart fa-lg text-danger"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $favoriteCount }}</span>
                        </a>
                        
                        @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                        <a href="{{ route('cart.index') }}" class="text-decoration-none me-4 position-relative">
                            <i class="fas fa-shopping-cart fa-lg text-success"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $cartCount }}</span>
                        </a>

                        @if(Auth::check())
                            <!-- Đã đăng nhập -->
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-success btn-sm dropdown-toggle" 
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Tài khoản của tôi</a></li>
                                    <li><a class="dropdown-item" href="#">Lịch sử đơn hàng</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="#" 
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        @else
                            <!-- Chưa đăng nhập -->
                            <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm me-2">
                                <i class="fas fa-user"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-success btn-sm">
                                Đăng ký
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        
    <!-- Main Navigation -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav mx-auto fw-medium">

                <!-- Trang chủ -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">
                        TRANG CHỦ
                    </a>
                </li>

                <!-- Danh mục Sản phẩm (Dropdown từ Database - KHÔNG LẶP) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button">
                        DANH MỤC SẢN PHẨM
                    </a>
                    <ul class="dropdown-menu">
                        @foreach($mainCategories ?? [] as $category)
                            <!-- Danh mục cha -->
                            <li>
                                <a class="dropdown-item fw-bold text-dark" 
                                   href="{{ route('shop') }}?category={{ $category->slug }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                            
                            <!-- Danh mục con (nếu có) -->
                            @if($category->children->count() > 0)
                                @foreach($category->children as $sub)
                                    <li>
                                        <a class="dropdown-item" 
                                           href="{{ route('shop') }}?category={{ $sub->slug }}">
                                            — {{ $sub->name }}
                                        </a>
                                    </li>
                                @endforeach
                            @endif
                        @endforeach
                    </ul>
                </li>

                <!-- Giới thiệu -->
                <li class="nav-item">
                    <a href="{{ route('about') }}" class="nav-link @if(request()->routeIs('about')) active @endif">
                        GIỚI THIỆU
                    </a>
                </li>

                <!-- Blog -->
                <li class="nav-item">
                    <a href="{{ route('blog.index') }}" class="nav-link @if(request()->routeIs('blog.*')) active @endif">
                        BLOG
                    </a>
                </li>

                <!-- Liên hệ -->
                <li class="nav-item">
                    <a href="{{ route('contact.index') }}" class="nav-link @if(request()->routeIs('contact.*')) active @endif">
                        LIÊN HỆ
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
    </header>

    <!-- ==================== MAIN CONTENT ==================== -->
    <main>
        @yield('content')
    </main>

    <!-- ==================== FOOTER ==================== -->
    <footer class="bg-light pt-5 pb-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold">HOA TƯƠI</h5>
                    <a href="{{ route('home') }}">
                        <h4 class="text-success fw-bold">🌸 {{ $siteName }}</h4>
                    </a>
                    <p class="small mt-2">
                        Hoa tươi chất lượng cao<br>
                        Giao hàng nhanh tại Hà Nội
                    </p>
                    <p class="small">
                        <strong>Phone:</strong> {{ $hotline }}<br>
                        <strong>Email:</strong> {{ $supportEmail }}<br>
                        <strong>Địa chỉ:</strong> {{ $address }}
                    </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold">HƯƠNG HOA XINH</h5>
                    <ul class="list-unstyled small">
                        <li><a href="#">Giới thiệu</a></li>
                        <li><a href="#">Liên hệ</a></li>
                        <li><a href="#">Tin tức - Blog</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold">SẢN PHẨM</h5>
                    <ul class="list-unstyled small">
                        <li><a href="#">Bó hoa tươi</a></li>
                        <li><a href="#">Giỏ hoa</a></li>
                        <li><a href="#">Hoa cắm tay</a></li>
                        <li><a href="#">Hoa lan</a></li>
                        <li><a href="#">Hoa theo chủ đề</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h5 class="fw-bold">ĐỊA CHỈ CÁC SHOP</h5>
                    <ul class="list-unstyled small">
                        <li><strong>Chi nhánh 1:</strong> 123 Xuân Phương, Nam Từ Liêm</li>
                        <li><strong>Chi nhánh 2:</strong> 123 Di Trạch, Hoài Đức</li>
                        <li><strong>Chi nhánh 3:</strong> 789 Lê Đức Thọ, Bắc Từ Liêm</li>
                    </ul>
                    <p class="mt-3 text-danger small">
                        <strong>Đặt hoa trực tuyến:</strong><br>
                        0377786256 (Zalo/Phone)
                    </p>
                </div>
            </div>

            <div class="row mt-4 border-top pt-4">
                <div class="col-md-6">
                    <h6 class="fw-bold">LIÊN KẾT</h6>
                    <div class="d-flex gap-3">
                        <a href="{{ $facebookUrl ?: '#' }}" class="text-dark" target="_blank" rel="noopener"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="{{ $instagramUrl ?: '#' }}" class="text-dark" target="_blank" rel="noopener"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="{{ $youtubeUrl ?: '#' }}" class="text-dark" target="_blank" rel="noopener"><i class="fab fa-youtube fa-2x"></i></a>
                        <a href="{{ $zaloUrl ?: '#' }}" class="text-dark" target="_blank" rel="noopener"><i class="fas fa-comment-dots fa-2x"></i></a>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <img src="https://cdn4793.cdn4s2.com/media/logo/1_1.webp" alt="Bộ Công Thương" height="50">
                </div>
            </div>

            <div class="text-center small text-muted mt-4 border-top pt-3">
                {{ $siteSettings['copyright_text'] ?? ('Copyright © ' . now()->year . ' ' . $siteName . '. All rights reserved.') }}
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Banner Slider Auto-play
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.querySelector('.banner-slider');
            if (!slider) return;

            const track = slider.querySelector('.slider-track');
            const items = slider.querySelectorAll('.slider-item');
            const prevBtn = slider.querySelector('.slider-nav.prev');
            const nextBtn = slider.querySelector('.slider-nav.next');
            const dotsContainer = slider.querySelector('.slider-dots');

            let currentIndex = 0;
            let autoPlayInterval;

            // Create dots
            items.forEach((_, index) => {
                const dot = document.createElement('div');
                dot.className = 'slider-dot' + (index === 0 ? ' active' : '');
                dot.addEventListener('click', () => goToSlide(index));
                dotsContainer.appendChild(dot);
            });

            const dots = dotsContainer.querySelectorAll('.slider-dot');

            function updateDots() {
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            function goToSlide(index) {
                currentIndex = index;
                track.style.transform = `translateX(-${currentIndex * 100}%)`;
                updateDots();
            }

            function nextSlide() {
                currentIndex = (currentIndex + 1) % items.length;
                goToSlide(currentIndex);
            }

            function prevSlide() {
                currentIndex = (currentIndex - 1 + items.length) % items.length;
                goToSlide(currentIndex);
            }

            function startAutoPlay() {
                autoPlayInterval = setInterval(nextSlide, 5000); // Auto slide every 5 seconds
            }

            function stopAutoPlay() {
                clearInterval(autoPlayInterval);
            }

            // Event listeners
            prevBtn.addEventListener('click', () => {
                prevSlide();
                stopAutoPlay();
                startAutoPlay(); // Restart auto-play
            });

            nextBtn.addEventListener('click', () => {
                nextSlide();
                stopAutoPlay();
                startAutoPlay(); // Restart auto-play
            });

            // Pause on hover
            slider.addEventListener('mouseenter', stopAutoPlay);
            slider.addEventListener('mouseleave', startAutoPlay);

            // Start auto-play
            startAutoPlay();
        });
    </script>
</body>
</html>