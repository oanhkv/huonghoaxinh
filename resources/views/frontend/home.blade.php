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

    <!-- ==================== HERO BANNER CAROUSEL ==================== -->
    <div id="bannerCarousel" class="carousel slide hhx-hero" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-indicators hhx-hero-indicators">
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <!-- ============ SLIDE 1: WELCOME ============ -->
            <div class="carousel-item active">
                <div class="hhx-hero-slide hhx-hero-slide-1">
                    <div class="hhx-hero-decor hhx-decor-tl">🌸</div>
                    <div class="hhx-hero-decor hhx-decor-br">🌹</div>
                    <div class="container h-100">
                        <div class="row align-items-center h-100 g-4">
                            <div class="col-lg-6 hhx-hero-text">
                                <span class="hhx-hero-eyebrow"><i class="fas fa-spa me-1"></i> Chào mừng đến Hương Hoa Xinh</span>
                                <h1 class="hhx-hero-title">Hoa tươi cho<br>mọi khoảnh khắc<br><span>đáng nhớ</span></h1>
                                <p class="hhx-hero-subtitle">Giao hoa tận nơi • Tươi lâu • Thiết kế theo yêu cầu — gửi trao yêu thương qua từng cánh hoa.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('shop') }}" class="btn hhx-hero-btn-primary"><i class="fas fa-bag-shopping me-2"></i>Mua hoa ngay</a>
                                    <a href="{{ route('vouchers') }}" class="btn hhx-hero-btn-ghost"><i class="fas fa-tag me-2"></i>Mã giảm giá</a>
                                </div>
                                <div class="hhx-hero-trust">
                                    <div><strong>500+</strong><span>Đơn / tháng</span></div>
                                    <div><strong>4.9★</strong><span>Đánh giá</span></div>
                                    <div><strong>2H</strong><span>Giao nội thành</span></div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                                <div class="hhx-hero-photo">
                                    <img src="https://tramhoa.com/wp-content/uploads/2019/09/Bo-Hoa-Tuoi-TH-B025-I-Love-You-3-400x500.webp" alt="Bó hoa nổi bật">
                                    <div class="hhx-hero-tag hhx-hero-tag-tr"><i class="fas fa-check-circle text-success me-1"></i> Tươi 100%</div>
                                    <div class="hhx-hero-tag hhx-hero-tag-bl"><i class="fas fa-truck-fast text-info me-1"></i> Giao 2H</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ SLIDE 2: VALENTINE ============ -->
            <div class="carousel-item">
                <div class="hhx-hero-slide hhx-hero-slide-2">
                    <div class="hhx-hero-decor hhx-decor-tl">💖</div>
                    <div class="hhx-hero-decor hhx-decor-br">🌷</div>
                    <div class="container h-100">
                        <div class="row align-items-center h-100 g-4 flex-lg-row-reverse">
                            <div class="col-lg-6 hhx-hero-text">
                                <span class="hhx-hero-eyebrow"><i class="fas fa-heart me-1"></i> Valentine 2026</span>
                                <h1 class="hhx-hero-title">Mỗi cánh hoa<br>là một<br><span>lời tỏ tình</span></h1>
                                <p class="hhx-hero-subtitle">Bộ sưu tập hoa Valentine tone đỏ – hồng – pastel hot trend, giảm đến 40% cho đơn đầu tiên.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('shop') }}?category=hoa-tinh-yeu" class="btn hhx-hero-btn-primary"><i class="fas fa-fire me-2"></i>Khám phá BST</a>
                                    <a href="{{ route('vouchers') }}" class="btn hhx-hero-btn-ghost"><i class="fas fa-gift me-2"></i>Lấy mã -40%</a>
                                </div>
                                <div class="hhx-hero-promo">
                                    <span class="hhx-hero-promo-code">VALENTINE50</span>
                                    <span class="hhx-hero-promo-text">Giảm ngay <strong>50.000₫</strong> đơn từ 500K</span>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                                <div class="hhx-hero-photo">
                                    <img src="https://tramhoa.com/wp-content/uploads/2024/11/Bo-Hoa-Tuoi-TH-B012-Forever-Love-F-400x500.jpg" alt="Hoa Valentine Forever Love">
                                    <div class="hhx-hero-tag hhx-hero-tag-tr"><i class="fas fa-percent text-danger me-1"></i> -40%</div>
                                    <div class="hhx-hero-tag hhx-hero-tag-bl"><i class="fas fa-heart text-danger me-1"></i> Bestseller</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ============ SLIDE 3: KHAI TRƯƠNG ============ -->
            <div class="carousel-item">
                <div class="hhx-hero-slide hhx-hero-slide-3">
                    <div class="hhx-hero-decor hhx-decor-tl">🌻</div>
                    <div class="hhx-hero-decor hhx-decor-br">💐</div>
                    <div class="container h-100">
                        <div class="row align-items-center h-100 g-4">
                            <div class="col-lg-6 hhx-hero-text">
                                <span class="hhx-hero-eyebrow"><i class="fas fa-trophy me-1"></i> Hoa khai trương</span>
                                <h1 class="hhx-hero-title">Khai trương<br>Hồng phát<br><span>Tài lộc đầy nhà</span></h1>
                                <p class="hhx-hero-subtitle">Kệ hoa khai trương 2-3 tầng đầy đủ kích cỡ – đặt trước 24h, giao đúng giờ khai mạc.</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('shop') }}?category=hoa-khai-truong" class="btn hhx-hero-btn-primary"><i class="fas fa-store me-2"></i>Đặt kệ hoa</a>
                                    <a href="{{ route('contact') }}" class="btn hhx-hero-btn-ghost"><i class="fas fa-phone me-2"></i>Tư vấn miễn phí</a>
                                </div>
                                <div class="hhx-hero-trust">
                                    <div><strong>1-3 tầng</strong><span>Tuỳ chọn</span></div>
                                    <div><strong>Băng rôn</strong><span>Tặng kèm</span></div>
                                    <div><strong>Đúng giờ</strong><span>Cam kết</span></div>
                                </div>
                            </div>
                            <div class="col-lg-6 d-none d-lg-flex justify-content-center">
                                <div class="hhx-hero-photo">
                                    <img src="https://tramhoa.com/wp-content/uploads/2019/12/ke-hoa-khai-truong-kt106-khai-truong-hong-phat-400x500.webp" alt="Kệ hoa khai trương">
                                    <div class="hhx-hero-tag hhx-hero-tag-tr"><i class="fas fa-star text-warning me-1"></i> Phong thuỷ</div>
                                    <div class="hhx-hero-tag hhx-hero-tag-bl"><i class="fas fa-clock text-primary me-1"></i> Đúng giờ</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev hhx-hero-ctrl" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
        </button>
        <button class="carousel-control-next hhx-hero-ctrl" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
        </button>
    </div>

    <style>
        /* ============ HHX HERO BANNER ============ */
        .hhx-hero { overflow: hidden; }
        .hhx-hero-slide {
            min-height: 560px;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .hhx-hero-slide-1 { background: linear-gradient(135deg, #fff5f8 0%, #fff 50%, #f0fdf4 100%); }
        .hhx-hero-slide-2 { background: linear-gradient(135deg, #ffe5ef 0%, #fff5f8 50%, #ffe5e5 100%); }
        .hhx-hero-slide-3 { background: linear-gradient(135deg, #fff7e0 0%, #fff5f8 50%, #ffe8d6 100%); }

        .hhx-hero-decor {
            position: absolute;
            font-size: 280px;
            opacity: 0.07;
            user-select: none;
            pointer-events: none;
            z-index: 0;
        }
        .hhx-decor-tl { top: -50px; left: -40px; transform: rotate(-15deg); }
        .hhx-decor-br { bottom: -60px; right: -30px; transform: rotate(20deg); }

        .hhx-hero-text {
            animation: hhxHeroFadeIn 0.8s ease-out;
            position: relative;
            z-index: 2;
        }
        @keyframes hhxHeroFadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .hhx-hero-eyebrow {
            display: inline-flex;
            align-items: center;
            background: rgba(214, 51, 132, 0.1);
            color: #d63384;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-bottom: 18px;
            border: 1px solid rgba(214, 51, 132, 0.2);
        }
        .hhx-hero-slide-3 .hhx-hero-eyebrow { background: rgba(255, 152, 0, 0.12); color: #ff7a00; border-color: rgba(255, 152, 0, 0.25); }

        .hhx-hero-title {
            font-size: clamp(2rem, 4.2vw, 3.6rem);
            font-weight: 900;
            line-height: 1.1;
            color: #0f172a;
            margin-bottom: 18px;
            letter-spacing: -0.02em;
        }
        .hhx-hero-title span {
            background: linear-gradient(135deg, #d63384 0%, #f06595 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hhx-hero-slide-1 .hhx-hero-title span,
        .hhx-hero-slide-3 .hhx-hero-title span {
            background: linear-gradient(135deg, #198754 0%, #20a464 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hhx-hero-subtitle {
            font-size: 1.05rem;
            color: #475569;
            line-height: 1.65;
            margin-bottom: 24px;
            max-width: 480px;
        }

        .hhx-hero-btn-primary, .hhx-hero-btn-ghost {
            padding: 12px 26px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.25s ease;
            border: 0;
        }
        .hhx-hero-btn-primary {
            background: linear-gradient(135deg, #198754 0%, #20a464 100%);
            color: #fff;
            box-shadow: 0 12px 24px rgba(25, 135, 84, 0.25);
        }
        .hhx-hero-slide-2 .hhx-hero-btn-primary {
            background: linear-gradient(135deg, #d63384 0%, #f06595 100%);
            box-shadow: 0 12px 24px rgba(214, 51, 132, 0.28);
        }
        .hhx-hero-slide-3 .hhx-hero-btn-primary {
            background: linear-gradient(135deg, #ff7a00 0%, #ffa726 100%);
            box-shadow: 0 12px 24px rgba(255, 122, 0, 0.28);
        }
        .hhx-hero-btn-primary:hover { transform: translateY(-3px); color: #fff; box-shadow: 0 18px 32px rgba(25, 135, 84, 0.35); }
        .hhx-hero-slide-2 .hhx-hero-btn-primary:hover { box-shadow: 0 18px 32px rgba(214, 51, 132, 0.4); }
        .hhx-hero-slide-3 .hhx-hero-btn-primary:hover { box-shadow: 0 18px 32px rgba(255, 122, 0, 0.4); }

        .hhx-hero-btn-ghost {
            background: #fff;
            color: #0f172a;
            border: 1.5px solid rgba(15, 23, 42, 0.1);
        }
        .hhx-hero-btn-ghost:hover {
            background: #0f172a;
            color: #fff;
            transform: translateY(-3px);
        }

        .hhx-hero-trust {
            display: flex;
            gap: 28px;
            margin-top: 28px;
            padding-top: 22px;
            border-top: 1px dashed rgba(15, 23, 42, 0.1);
        }
        .hhx-hero-trust > div { display: flex; flex-direction: column; }
        .hhx-hero-trust strong { font-size: 1.4rem; color: #0f172a; font-weight: 800; }
        .hhx-hero-trust span { font-size: 0.78rem; color: #64748b; }

        .hhx-hero-promo {
            margin-top: 24px;
            padding: 14px 18px;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(8px);
            border-radius: 14px;
            border: 1px dashed rgba(214, 51, 132, 0.4);
            display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
        }
        .hhx-hero-promo-code {
            background: linear-gradient(135deg, #d63384, #f06595);
            color: #fff;
            font-family: 'Courier New', monospace;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 8px;
            letter-spacing: 0.08em;
        }
        .hhx-hero-promo-text { font-size: 0.92rem; color: #475569; }

        /* Hero Photo - khung tròn lớn với ảnh hoa */
        .hhx-hero-photo {
            position: relative;
            width: 460px;
            height: 460px;
            max-width: 100%;
            animation: hhxHeroPhoto 1s ease-out;
        }
        @keyframes hhxHeroPhoto {
            from { opacity: 0; transform: scale(0.85) rotate(-5deg); }
            to { opacity: 1; transform: scale(1) rotate(0); }
        }
        .hhx-hero-photo::before {
            content: '';
            position: absolute;
            inset: -25px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(214, 51, 132, 0.15) 0%, rgba(25, 135, 84, 0.1) 100%);
            z-index: 0;
            animation: hhxRotate 18s linear infinite;
        }
        .hhx-hero-photo::after {
            content: '';
            position: absolute;
            inset: -50px;
            border-radius: 50%;
            border: 2px dashed rgba(214, 51, 132, 0.25);
            z-index: 0;
            animation: hhxRotate 30s linear infinite reverse;
        }
        @keyframes hhxRotate { to { transform: rotate(360deg); } }

        .hhx-hero-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;
            position: relative;
            z-index: 1;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.18);
            border: 8px solid #fff;
        }
        .hhx-hero-tag {
            position: absolute;
            background: #fff;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 0.85rem;
            font-weight: 700;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.12);
            z-index: 2;
            white-space: nowrap;
            animation: hhxFloat 3s ease-in-out infinite;
        }
        .hhx-hero-tag-tr { top: 30px; right: -20px; animation-delay: 0s; }
        .hhx-hero-tag-bl { bottom: 50px; left: -30px; animation-delay: 1.5s; }
        @keyframes hhxFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Indicators & controls */
        .hhx-hero-indicators {
            bottom: 22px;
            margin: 0;
        }
        .hhx-hero-indicators button {
            width: 12px !important;
            height: 12px !important;
            border-radius: 50% !important;
            background: rgba(15, 23, 42, 0.2) !important;
            border: 0 !important;
            margin: 0 5px;
            opacity: 1 !important;
            transition: all 0.25s ease;
        }
        .hhx-hero-indicators button.active {
            background: #d63384 !important;
            width: 32px !important;
            border-radius: 6px !important;
        }

        .hhx-hero-ctrl {
            width: 48px;
            height: 48px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.12);
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
            color: #d63384;
            font-size: 1rem;
            margin: 0 16px;
            transition: all 0.25s ease;
        }
        .hhx-hero-ctrl:hover {
            background: #d63384;
            color: #fff;
            transform: translateY(-50%) scale(1.1);
        }
        .hhx-hero-ctrl.carousel-control-prev { left: 0; }
        .hhx-hero-ctrl.carousel-control-next { right: 0; }

        @media (max-width: 991.98px) {
            .hhx-hero-slide { min-height: 460px; padding: 50px 0; text-align: center; }
            .hhx-hero-eyebrow, .hhx-hero-trust, .hhx-hero-promo { justify-content: center; }
            .hhx-hero-trust { display: inline-flex; }
            .hhx-hero-subtitle { margin-left: auto; margin-right: auto; }
            .hhx-hero-text .d-flex.flex-wrap { justify-content: center; }
        }
        @media (max-width: 575.98px) {
            .hhx-hero-slide { min-height: 420px; }
            .hhx-hero-title { font-size: 1.8rem; line-height: 1.2; }
            .hhx-hero-subtitle { font-size: 0.92rem; }
            .hhx-hero-trust { gap: 16px; flex-wrap: wrap; }
            .hhx-hero-trust strong { font-size: 1.1rem; }
            .hhx-hero-ctrl { width: 38px; height: 38px; margin: 0 8px; }
        }
    </style>

    <!-- ==================== CHỌN THEO DỊP & PHONG CÁCH ==================== -->
    @php
        // Map icon + tone gradient riêng cho từng danh mục, chọn theo slug.
        $catThemes = [
            'hoa-sinh-nhat'    => ['icon' => 'fa-cake-candles',    'c1' => '#f06595', 'c2' => '#ff8fab', 'tag' => 'Vui & rực rỡ'],
            'hoa-tinh-yeu'     => ['icon' => 'fa-heart',           'c1' => '#dc3545', 'c2' => '#ff6b81', 'tag' => 'Ngọt ngào & lãng mạn'],
            'hoa-chuc-mung'    => ['icon' => 'fa-champagne-glasses','c1' => '#ffc107', 'c2' => '#ff9800', 'tag' => 'Sang trọng & ấm áp'],
            'hoa-tang-le'      => ['icon' => 'fa-dove',            'c1' => '#94a3b8', 'c2' => '#cbd5e1', 'tag' => 'Trang nghiêm & thành kính'],
            'hoa-khai-truong'  => ['icon' => 'fa-trophy',          'c1' => '#198754', 'c2' => '#20a464', 'tag' => 'Tài lộc & may mắn'],
        ];
        $featuredCats = $mainCategories->take(5);
        $heroCat = $featuredCats->first();
        $sideCats = $featuredCats->slice(1, 4);
    @endphp

    <section class="home-category-section py-5">
        <div class="container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4 reveal-up">
                <div>
                    <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 mb-2">
                        <i class="fas fa-spa me-1"></i> Bộ sưu tập
                    </span>
                    <h2 class="fw-bold mb-1">Chọn theo dịp &amp; phong cách</h2>
                    <p class="text-muted mb-0">Mỗi dịp đặc biệt xứng đáng có một bó hoa được chọn riêng cho khoảnh khắc đó.</p>
                </div>
                <a href="{{ route('shop') }}" class="btn btn-outline-success rounded-pill px-4 d-none d-md-inline-flex align-items-center">
                    Tất cả danh mục <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>

            @if($featuredCats->count() > 0)
                <div class="row g-3 g-lg-4">
                    {{-- HERO CARD (lớn, chiếm 2 hàng bên trái trên desktop) --}}
                    @if($heroCat)
                        @php $theme = $catThemes[$heroCat->slug] ?? ['icon' => 'fa-spa', 'c1' => '#198754', 'c2' => '#20a464', 'tag' => 'Hoa tươi tuyển chọn']; @endphp
                        <div class="col-lg-6 reveal-up">
                            <a href="{{ route('shop') }}?category={{ $heroCat->slug }}" class="hhx-cat-hero" style="--c1: {{ $theme['c1'] }}; --c2: {{ $theme['c2'] }};">
                                <div class="hhx-cat-hero-bg">
                                    @if($heroCat->image)
                                        <img src="{{ str_starts_with($heroCat->image, 'http') ? $heroCat->image : asset('storage/'.$heroCat->image) }}" alt="{{ $heroCat->name }}">
                                    @endif
                                </div>
                                <div class="hhx-cat-hero-overlay">
                                    <div class="hhx-cat-hero-content">
                                        <span class="hhx-cat-hero-tag"><i class="fas {{ $theme['icon'] }} me-1"></i> {{ $theme['tag'] }}</span>
                                        <h3 class="hhx-cat-hero-title">{{ $heroCat->name }}</h3>
                                        @if($heroCat->description)
                                            <p class="hhx-cat-hero-desc">{{ Str::limit($heroCat->description, 130) }}</p>
                                        @endif
                                        <div class="hhx-cat-hero-meta">
                                            <span><i class="fas fa-bag-shopping me-1"></i>{{ $heroCat->products_count ?? 0 }} sản phẩm</span>
                                            <span class="hhx-cat-hero-cta">Khám phá ngay <i class="fas fa-arrow-right ms-1"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endif

                    {{-- GRID 2x2 CARD NHỎ BÊN PHẢI --}}
                    <div class="col-lg-6">
                        <div class="row g-3 g-lg-4 h-100">
                            @foreach($sideCats as $cat)
                                @php $theme = $catThemes[$cat->slug] ?? ['icon' => 'fa-spa', 'c1' => '#198754', 'c2' => '#20a464', 'tag' => 'Hoa tươi']; @endphp
                                <div class="col-6 reveal-up">
                                    <a href="{{ route('shop') }}?category={{ $cat->slug }}" class="hhx-cat-card" style="--c1: {{ $theme['c1'] }}; --c2: {{ $theme['c2'] }};">
                                        <div class="hhx-cat-card-bg">
                                            @if($cat->image)
                                                <img src="{{ str_starts_with($cat->image, 'http') ? $cat->image : asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" loading="lazy">
                                            @endif
                                        </div>
                                        <div class="hhx-cat-card-overlay">
                                            <div class="hhx-cat-card-icon"><i class="fas {{ $theme['icon'] }}"></i></div>
                                            <div class="hhx-cat-card-info">
                                                <h4 class="hhx-cat-card-title">{{ $cat->name }}</h4>
                                                <small class="hhx-cat-card-count">{{ $cat->products_count ?? 0 }} sản phẩm <i class="fas fa-arrow-right ms-1"></i></small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4 d-md-none">
                    <a href="{{ route('shop') }}" class="btn btn-outline-success rounded-pill px-4">
                        Xem tất cả danh mục <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <style>
        .home-category-section {
            background: linear-gradient(180deg, #f8fafc 0%, #fff5f8 100%);
        }

        /* ===== Card lớn HERO ===== */
        .hhx-cat-hero {
            display: block;
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            text-decoration: none;
            min-height: 460px;
            height: 100%;
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.1);
            transition: transform 0.45s ease, box-shadow 0.45s ease;
        }
        .hhx-cat-hero:hover {
            transform: translateY(-6px);
            box-shadow: 0 28px 60px color-mix(in srgb, var(--c1) 25%, transparent);
        }
        .hhx-cat-hero-bg {
            position: absolute; inset: 0;
            overflow: hidden;
        }
        .hhx-cat-hero-bg img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center;
            transition: transform 0.8s ease;
        }
        .hhx-cat-hero:hover .hhx-cat-hero-bg img { transform: scale(1.07); }
        .hhx-cat-hero-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0) 35%, rgba(15, 23, 42, 0.4) 70%, color-mix(in srgb, var(--c1) 92%, transparent) 100%);
            display: flex; align-items: flex-end;
            padding: 32px;
            transition: background 0.4s ease;
        }
        .hhx-cat-hero:hover .hhx-cat-hero-overlay {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.2) 35%, rgba(15, 23, 42, 0.55) 70%, color-mix(in srgb, var(--c1) 96%, transparent) 100%);
        }
        .hhx-cat-hero-content { color: #fff; width: 100%; }
        .hhx-cat-hero-tag {
            display: inline-flex; align-items: center;
            background: rgba(255,255,255,0.95);
            color: var(--c1);
            font-size: 0.75rem; font-weight: 700;
            letter-spacing: 0.04em; text-transform: uppercase;
            padding: 6px 14px; border-radius: 999px;
            margin-bottom: 14px;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.12);
        }
        .hhx-cat-hero-title {
            font-size: clamp(1.6rem, 2.5vw, 2.2rem);
            font-weight: 800; margin-bottom: 8px;
            text-shadow: 0 2px 8px rgba(0,0,0,0.25);
            letter-spacing: -0.01em;
        }
        .hhx-cat-hero-desc {
            font-size: 0.95rem;
            opacity: 0.95;
            margin-bottom: 14px;
            line-height: 1.55;
            max-width: 460px;
            text-shadow: 0 1px 4px rgba(0,0,0,0.2);
        }
        .hhx-cat-hero-meta {
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 10px;
            font-size: 0.9rem;
            padding-top: 14px;
            border-top: 1px solid rgba(255,255,255,0.25);
        }
        .hhx-cat-hero-cta {
            background: #fff; color: var(--c1);
            padding: 8px 18px; border-radius: 999px;
            font-weight: 700; font-size: 0.85rem;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .hhx-cat-hero:hover .hhx-cat-hero-cta {
            transform: translateX(4px);
            box-shadow: 0 8px 18px rgba(255, 255, 255, 0.25);
        }

        /* ===== Card nhỏ ===== */
        .hhx-cat-card {
            display: block; position: relative;
            border-radius: 20px; overflow: hidden;
            text-decoration: none;
            aspect-ratio: 4 / 3;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }
        .hhx-cat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 44px color-mix(in srgb, var(--c1) 22%, transparent);
        }
        .hhx-cat-card-bg { position: absolute; inset: 0; overflow: hidden; }
        .hhx-cat-card-bg img {
            width: 100%; height: 100%;
            object-fit: cover; object-position: center;
            transition: transform 0.6s ease;
        }
        .hhx-cat-card:hover .hhx-cat-card-bg img { transform: scale(1.1); }
        .hhx-cat-card-overlay {
            position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.15) 50%, color-mix(in srgb, var(--c1) 90%, transparent) 100%);
            display: flex; align-items: flex-end;
            padding: 16px;
            transition: background 0.3s ease;
        }
        .hhx-cat-card:hover .hhx-cat-card-overlay {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0) 0%, rgba(15, 23, 42, 0.3) 40%, color-mix(in srgb, var(--c1) 95%, transparent) 100%);
        }
        .hhx-cat-card-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            color: var(--c1);
            display: grid; place-items: center;
            font-size: 1.1rem;
            margin-right: 12px;
            box-shadow: 0 6px 14px rgba(15, 23, 42, 0.18);
            flex-shrink: 0;
            transition: transform 0.3s ease, background 0.3s ease;
        }
        .hhx-cat-card:hover .hhx-cat-card-icon {
            transform: rotate(-8deg) scale(1.05);
            background: linear-gradient(135deg, var(--c1), var(--c2));
            color: #fff;
        }
        .hhx-cat-card-info { color: #fff; min-width: 0; }
        .hhx-cat-card-title {
            font-size: 1rem; font-weight: 700;
            margin: 0 0 2px;
            text-shadow: 0 2px 6px rgba(0,0,0,0.3);
            line-height: 1.25;
        }
        .hhx-cat-card-count {
            font-size: 0.78rem; opacity: 0.95;
            display: inline-flex; align-items: center;
        }
        .hhx-cat-card-count .fa-arrow-right {
            transition: transform 0.25s ease;
        }
        .hhx-cat-card:hover .hhx-cat-card-count .fa-arrow-right { transform: translateX(4px); }

        @media (max-width: 991.98px) {
            .hhx-cat-hero { min-height: 320px; margin-bottom: 0; }
            .hhx-cat-hero-overlay { padding: 22px; }
        }
        @media (max-width: 575.98px) {
            .hhx-cat-hero { min-height: 280px; }
            .hhx-cat-hero-title { font-size: 1.4rem; }
            .hhx-cat-card { aspect-ratio: 4 / 3; }
            .hhx-cat-card-icon { width: 38px; height: 38px; font-size: 0.9rem; margin-right: 10px; }
            .hhx-cat-card-title { font-size: 0.88rem; }
        }
    </style>

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

        .home-category-strip {
            background: linear-gradient(180deg, rgba(241, 245, 249, 0.9) 0%, #fff 100%);
        }
        .cat-pill-card .cat-pill-inner {
            border-radius: 20px;
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 14px 40px rgba(15, 23, 42, 0.06);
            transition: transform 0.35s ease, box-shadow 0.35s ease, border-color 0.35s ease;
        }
        .cat-pill-card:hover .cat-pill-inner {
            transform: translateY(-6px);
            box-shadow: 0 22px 50px rgba(25, 135, 84, 0.15);
            border-color: rgba(25, 135, 84, 0.35);
        }
        .cat-pill-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, rgba(25,135,84,0.15), rgba(14,165,233,0.12));
            color: #198754;
            font-size: 1.35rem;
        }
        .reveal-up {
            opacity: 0;
            transform: translateY(28px);
            transition: opacity 0.65s ease, transform 0.65s ease;
        }
        .reveal-up.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <!-- About Section -->
    <div class="container py-5">
        <div class="row align-items-center reveal-up">
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

    <!-- ==================== SẢN PHẨM NỔI BẬT ==================== -->
    <section class="featured-section py-5">
        <div class="container">
            <div class="text-center mb-4 reveal-up">
                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 mb-2">
                    <i class="fas fa-fire me-1"></i> Bestseller
                </span>
                <h2 class="fw-bold mb-2">Sản phẩm nổi bật</h2>
                <p class="text-muted mb-0">Những bó hoa được khách hàng đặt nhiều nhất tháng này.</p>
            </div>

            <div class="row g-3 g-md-4">
                @foreach($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3 reveal-up">
                        <article class="hhx-product-card h-100">
                            <div class="hhx-product-thumb">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="hhx-product-fallback"><i class="fas fa-spa"></i></div>
                                @endif
                                @if($product->is_featured)
                                    <span class="hhx-product-badge"><i class="fas fa-star me-1"></i>HOT</span>
                                @endif
                                <div class="hhx-product-actions">
                                    <button class="hhx-product-btn" title="Yêu thích" onclick="event.preventDefault(); toggleWishlist({{ $product->id }})">
                                        <i class="far fa-heart"></i>
                                    </button>
                                    <button class="hhx-product-btn" title="Thêm vào giỏ" onclick="event.preventDefault(); addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                    <a href="{{ route('product.show', $product->slug) }}" class="hhx-product-btn" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="hhx-product-body">
                                <div class="hhx-product-cat">{{ $product->category?->name ?? 'Hoa tươi' }}</div>
                                <h3 class="hhx-product-name">
                                    <a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <div>
                                        <span class="hhx-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                        <span class="hhx-product-price-old">{{ number_format($product->price * 1.25, 0, ',', '.') }}₫</span>
                                    </div>
                                    <a href="{{ route('product.show', $product->slug) }}" class="hhx-product-go" aria-label="Chi tiết">
                                        <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('shop') }}" class="btn btn-outline-success rounded-pill px-4">
                    Xem tất cả sản phẩm <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

    @auth
    @if($userWishlists->count() > 0)
    <!-- ==================== WISHLIST CỦA NGƯỜI DÙNG ==================== -->
    <section class="wishlist-section py-5">
        <div class="container">
            <div class="text-center mb-4 reveal-up">
                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 mb-2">
                    <i class="fas fa-heart me-1"></i> Yêu thích
                </span>
                <h2 class="fw-bold mb-2">Sản phẩm bạn quan tâm</h2>
                <p class="text-muted mb-0">Đừng bỏ lỡ những sản phẩm bạn đã thêm vào danh sách yêu thích.</p>
            </div>

            <div class="row g-3 g-md-4">
                @foreach($userWishlists as $wishlist)
                    @php $product = $wishlist->product; @endphp
                    @if($product)
                    <div class="col-6 col-md-4 col-lg-3 reveal-up">
                        <article class="hhx-product-card h-100">
                            <div class="hhx-product-thumb">
                                @if($product->image)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="hhx-product-fallback"><i class="fas fa-spa"></i></div>
                                @endif
                                <span class="hhx-product-badge hhx-product-badge-pink"><i class="fas fa-heart me-1"></i>YÊU THÍCH</span>
                                <div class="hhx-product-actions">
                                    <button class="hhx-product-btn" title="Bỏ yêu thích" onclick="event.preventDefault(); toggleWishlist({{ $product->id }})">
                                        <i class="fas fa-heart-broken"></i>
                                    </button>
                                    <button class="hhx-product-btn" title="Thêm vào giỏ" onclick="event.preventDefault(); addToCart({{ $product->id }})">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                    <a href="{{ route('product.show', $product->slug) }}" class="hhx-product-btn" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="hhx-product-body">
                                <div class="hhx-product-cat">{{ $product->category?->name ?? 'Hoa tươi' }}</div>
                                <h3 class="hhx-product-name">
                                    <a href="{{ route('product.show', $product->slug) }}">{{ Str::limit($product->name, 50) }}</a>
                                </h3>
                                <div class="d-flex align-items-center justify-content-between mt-2">
                                    <span class="hhx-product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                    <a href="{{ route('product.show', $product->slug) }}" class="hhx-product-go"><i class="fas fa-arrow-right"></i></a>
                                </div>
                            </div>
                        </article>
                    </div>
                    @endif
                @endforeach
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('wishlist.index') }}" class="btn btn-outline-danger rounded-pill px-4">
                    Xem tất cả yêu thích <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>
    @endif
    @endauth

    <!-- CSS chung cho product card kiểu mới + style banner & section -->
    <style>
        /* ===== Hero slide (banner) ===== */
        .hero-slide {
            height: 550px;
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .hero-slide-inner { max-width: 720px; }
        .hero-eyebrow {
            display: inline-block;
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(6px);
            color: #fff;
            padding: 6px 16px;
            border-radius: 999px;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            margin-bottom: 16px;
        }

        /* ===== Featured & wishlist sections ===== */
        .featured-section {
            background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
        }
        .wishlist-section {
            background: linear-gradient(180deg, #fff 0%, #fff5f8 100%);
        }

        /* ===== HHX Product Card (4:5 aspect) ===== */
        .hhx-product-card {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.06);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            border: 1px solid rgba(15, 23, 42, 0.04);
            display: flex;
            flex-direction: column;
        }
        .hhx-product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 44px rgba(25, 135, 84, 0.18);
        }
        .hhx-product-thumb {
            position: relative;
            aspect-ratio: 4 / 5;        /* khớp tỉ lệ ảnh tramhoa 400×500 — không bị crop */
            overflow: hidden;
            background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
        }
        .hhx-product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            transition: transform 0.6s ease;
        }
        .hhx-product-card:hover .hhx-product-thumb img { transform: scale(1.06); }
        .hhx-product-fallback {
            width: 100%; height: 100%;
            display: grid; place-items: center;
            font-size: 3rem;
            color: #d63384;
            background: linear-gradient(135deg, #ffe5ef, #fff5e5);
        }
        .hhx-product-badge {
            position: absolute; top: 12px; left: 12px;
            background: linear-gradient(135deg, #dc3545, #ff6b6b);
            color: #fff;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        }
        .hhx-product-badge-pink {
            background: linear-gradient(135deg, #d63384, #f06595);
            box-shadow: 0 4px 10px rgba(214, 51, 132, 0.3);
        }
        .hhx-product-actions {
            position: absolute;
            bottom: 12px; left: 50%;
            transform: translateX(-50%) translateY(20px);
            display: flex; gap: 8px;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        .hhx-product-card:hover .hhx-product-actions {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .hhx-product-btn {
            width: 38px; height: 38px;
            border-radius: 50%;
            border: 0;
            background: #fff;
            color: #475569;
            display: grid; place-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            font-size: 0.85rem;
        }
        .hhx-product-btn:hover {
            background: #198754;
            color: #fff;
            transform: translateY(-3px);
        }
        .hhx-product-body {
            padding: 14px 16px 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        .hhx-product-cat {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #94a3b8;
            margin-bottom: 4px;
            font-weight: 600;
        }
        .hhx-product-name {
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.4;
            margin: 0 0 4px;
            min-height: 2.65em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .hhx-product-name a {
            color: #0f172a;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .hhx-product-name a:hover { color: #198754; }
        .hhx-product-price {
            color: #198754;
            font-weight: 800;
            font-size: 1rem;
        }
        .hhx-product-price-old {
            color: #94a3b8;
            text-decoration: line-through;
            font-size: 0.78rem;
            margin-left: 4px;
        }
        .hhx-product-go {
            width: 30px; height: 30px;
            border-radius: 50%;
            background: #f1f5f9;
            color: #198754;
            display: grid; place-items: center;
            text-decoration: none;
            font-size: 0.72rem;
            transition: all 0.25s ease;
            flex-shrink: 0;
        }
        .hhx-product-card:hover .hhx-product-go {
            background: #198754;
            color: #fff;
            transform: rotate(-30deg);
        }
        @media (max-width: 575.98px) {
            .hhx-product-name { font-size: 0.85rem; min-height: 2.5em; }
            .hhx-product-actions { bottom: 8px; gap: 6px; }
            .hhx-product-btn { width: 34px; height: 34px; }
            .hero-slide { height: 380px; }
            .hero-slide .display-4 { font-size: 1.75rem; }
        }
    </style>

    @if($blogPosts->count() > 0)
    <!-- ==================== CHUYỆN VỀ HOA - BLOG SLIDER ==================== -->
    <section class="blog-stories-section py-5">
        <div class="container">
            <div class="text-center mb-5 reveal-up">
                <span class="badge rounded-pill bg-danger bg-opacity-10 text-danger px-3 py-2 mb-2">
                    <i class="fas fa-book-open me-1"></i> Blog
                </span>
                <h2 class="fw-bold mb-2">Chuyện về hoa</h2>
                <p class="text-muted mb-0">Mẹo chọn hoa, gợi ý quà tặng và những câu chuyện đẹp đằng sau mỗi bó hoa.</p>
            </div>

            <div id="blogStoriesCarousel" class="carousel slide blog-stories-carousel" data-bs-ride="carousel" data-bs-interval="6000">
                <div class="carousel-inner">
                    @php $chunks = $blogPosts->chunk(3); @endphp
                    @foreach($chunks as $chunkIndex => $chunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="row g-4">
                                @foreach($chunk as $bp)
                                    <div class="col-md-4">
                                        <article class="blog-story-card h-100">
                                            <a href="{{ route('blog.show', $bp->slug) }}" class="text-decoration-none">
                                                <div class="blog-story-thumb">
                                                    @if($bp->image)
                                                        <img src="{{ $bp->image_url }}" alt="{{ $bp->title }}" loading="lazy">
                                                    @else
                                                        <div class="blog-story-thumb-fallback">
                                                            <i class="fas fa-spa"></i>
                                                        </div>
                                                    @endif
                                                    @if($bp->category)
                                                        <span class="blog-story-cat">{{ $bp->category->name }}</span>
                                                    @endif
                                                </div>
                                                <div class="blog-story-body">
                                                    <div class="blog-story-meta">
                                                        <i class="far fa-calendar-alt me-1"></i>
                                                        {{ $bp->published_at?->format('d/m/Y') ?? $bp->created_at->format('d/m/Y') }}
                                                    </div>
                                                    <h3 class="blog-story-title">{{ Str::limit($bp->title, 70) }}</h3>
                                                    <p class="blog-story-excerpt">{{ Str::limit($bp->excerpt ?? strip_tags($bp->content), 120) }}</p>
                                                    <span class="blog-story-readmore">
                                                        Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                                    </span>
                                                </div>
                                            </a>
                                        </article>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($chunks->count() > 1)
                    <button class="carousel-control-prev blog-story-ctrl" type="button" data-bs-target="#blogStoriesCarousel" data-bs-slide="prev">
                        <span aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next blog-story-ctrl" type="button" data-bs-target="#blogStoriesCarousel" data-bs-slide="next">
                        <span aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                    <div class="carousel-indicators blog-story-indicators">
                        @foreach($chunks as $chunkIndex => $_)
                            <button type="button" data-bs-target="#blogStoriesCarousel" data-bs-slide-to="{{ $chunkIndex }}" {{ $chunkIndex === 0 ? 'class=active' : '' }} aria-current="{{ $chunkIndex === 0 ? 'true' : 'false' }}"></button>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('blog.index') }}" class="btn btn-outline-danger rounded-pill px-4">
                    Xem tất cả bài viết <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

    <style>
        .blog-stories-section {
            background: linear-gradient(180deg, #fff 0%, #fff5f8 100%);
        }
        .blog-stories-carousel {
            padding: 0 50px;
        }
        .blog-story-card {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            border: 1px solid rgba(15, 23, 42, 0.04);
        }
        .blog-story-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 48px rgba(214, 51, 132, 0.18);
        }
        .blog-story-thumb {
            position: relative;
            height: 220px;
            overflow: hidden;
        }
        .blog-story-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .blog-story-card:hover .blog-story-thumb img {
            transform: scale(1.08);
        }
        .blog-story-thumb-fallback {
            width: 100%;
            height: 100%;
            display: grid;
            place-items: center;
            font-size: 3rem;
            background: linear-gradient(135deg, #ffe5ef, #fff5e5);
            color: #d63384;
        }
        .blog-story-cat {
            position: absolute;
            top: 14px;
            left: 14px;
            background: rgba(214, 51, 132, 0.92);
            color: #fff;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
        }
        .blog-story-body {
            padding: 22px 22px 26px;
        }
        .blog-story-meta {
            font-size: 0.78rem;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .blog-story-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.4;
            margin-bottom: 10px;
            min-height: 2.8em;
        }
        .blog-story-excerpt {
            color: #64748b;
            font-size: 0.88rem;
            line-height: 1.55;
            margin-bottom: 14px;
            min-height: 4em;
        }
        .blog-story-readmore {
            color: #d63384;
            font-weight: 600;
            font-size: 0.88rem;
        }
        .blog-story-card:hover .blog-story-readmore {
            color: #b02a6c;
        }
        .blog-story-ctrl {
            width: 42px;
            height: 42px;
            background: #fff;
            border-radius: 50%;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.12);
            top: 50%;
            transform: translateY(-50%);
            opacity: 1;
            color: #d63384;
            font-size: 1rem;
        }
        .blog-story-ctrl:hover {
            background: #d63384;
            color: #fff;
        }
        .blog-story-ctrl.carousel-control-prev { left: 0; }
        .blog-story-ctrl.carousel-control-next { right: 0; }
        .blog-story-indicators {
            position: relative;
            bottom: -8px;
            margin: 18px 0 0;
        }
        .blog-story-indicators button {
            width: 10px !important;
            height: 10px !important;
            border-radius: 50% !important;
            background: #fcd1e1 !important;
            border: 0 !important;
            margin: 0 4px;
            opacity: 1 !important;
        }
        .blog-story-indicators button.active {
            background: #d63384 !important;
            width: 28px !important;
            border-radius: 5px !important;
        }
        @media (max-width: 767.98px) {
            .blog-stories-carousel { padding: 0; }
            .blog-story-ctrl { display: none; }
        }
    </style>
    @endif

    @if(isset($customerReviews) && $customerReviews->count() > 0)
    <section class="py-5 bg-white border-top border-bottom">
        <div class="container">
            <div class="text-center mb-5 reveal-up">
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 mb-2">Khách hàng nói gì</span>
                <h2 class="fw-bold mb-2">Feedback từ người đã mua</h2>
                <p class="text-muted mb-0">Đánh giá thật sau khi trải nghiệm sản phẩm — minh bạch và gần gũi.</p>
            </div>
            <div class="row g-4">
                @foreach($customerReviews as $rev)
                    <div class="col-md-6 col-lg-3 reveal-up">
                        <div class="card h-100 border-0 shadow-sm rounded-4 p-4 feedback-card">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center fw-bold" style="width:44px;height:44px;">
                                    {{ Str::substr($rev->user->name ?? 'K', 0, 1) }}
                                </div>
                                <div>
                                    <div class="fw-semibold small mb-0">{{ Str::limit($rev->user->name ?? 'Khách hàng', 24) }}</div>
                                    <div class="text-muted" style="font-size:0.75rem;">{{ Str::limit($rev->product->name ?? '', 28) }}</div>
                                </div>
                            </div>
                            <div class="mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= $rev->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                @endfor
                            </div>
                            <p class="small text-muted mb-0">{{ Str::limit($rev->comment, 140) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <style>
        .feedback-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .feedback-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(15,23,42,0.1) !important; }
    </style>
    @endif

    <!-- ==================== TẠI SAO CHỌN HƯƠNG HOA XINH ==================== -->
    <section class="why-choose-section py-5">
        <div class="container">
            <div class="text-center mb-5 reveal-up">
                <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 mb-2">
                    <i class="fas fa-award me-1"></i> Cam kết
                </span>
                <h2 class="fw-bold mb-2">Tại sao chọn Hương Hoa Xinh?</h2>
                <p class="text-muted mb-0 mx-auto" style="max-width: 640px;">
                    Không chỉ là shop hoa, chúng tôi là nơi kết nối cảm xúc — mỗi bó hoa đều được cắm bằng cả trái tim và sự tỉ mỉ.
                </p>
            </div>

            <div class="row g-3 g-md-4">
                <div class="col-lg-3 col-md-6 reveal-up">
                    <div class="why-card why-card-1">
                        <div class="why-icon"><i class="fas fa-seedling"></i></div>
                        <h5 class="fw-bold mb-2">Hoa tươi 100%</h5>
                        <p class="text-muted small mb-0">Tuyển chọn kỹ lưỡng từ vườn hoa Đà Lạt & nhập khẩu trực tiếp mỗi sáng.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 reveal-up">
                    <div class="why-card why-card-2">
                        <div class="why-icon"><i class="fas fa-crown"></i></div>
                        <h5 class="fw-bold mb-2">Thiết kế nghệ thuật</h5>
                        <p class="text-muted small mb-0">Đội ngũ florists chuyên nghiệp – mỗi tác phẩm là một câu chuyện riêng.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 reveal-up">
                    <div class="why-card why-card-3">
                        <div class="why-icon"><i class="fas fa-gift"></i></div>
                        <h5 class="fw-bold mb-2">Tặng thiệp viết tay</h5>
                        <p class="text-muted small mb-0">Miễn phí thiệp tay cho mọi đơn — gửi gắm trọn vẹn cảm xúc của bạn.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 reveal-up">
                    <div class="why-card why-card-4">
                        <div class="why-icon"><i class="fas fa-truck-fast"></i></div>
                        <h5 class="fw-bold mb-2">Giao hoa 2 giờ</h5>
                        <p class="text-muted small mb-0">Nội thành Hà Nội nhận hoa nhanh trong 2 giờ — tươi đến tận tay người nhận.</p>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5 reveal-up">
                <a href="{{ route('about') }}" class="btn btn-success rounded-pill px-5 py-2">
                    Tìm hiểu thêm về chúng tôi <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </section>

    <style>
        .why-choose-section {
            background: linear-gradient(180deg, #fff 0%, #f0fdf4 100%);
        }
        .why-card {
            position: relative;
            background: #fff;
            border-radius: 18px;
            padding: 28px 22px 26px;
            text-align: center;
            border: 1px solid rgba(15, 23, 42, 0.05);
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.05);
            transition: transform 0.35s ease, box-shadow 0.35s ease;
            height: 100%;
            overflow: hidden;
        }
        .why-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--c1, #198754), var(--c2, #20a464));
        }
        .why-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 44px rgba(15, 23, 42, 0.1);
        }
        .why-card-1 { --c1: #198754; --c2: #20a464; }
        .why-card-2 { --c1: #ffc107; --c2: #ff9800; }
        .why-card-3 { --c1: #d63384; --c2: #f06595; }
        .why-card-4 { --c1: #0ea5e9; --c2: #38bdf8; }
        .why-icon {
            width: 64px; height: 64px;
            margin: 0 auto 16px;
            border-radius: 18px;
            display: grid; place-items: center;
            font-size: 1.6rem;
            color: #fff;
            background: linear-gradient(135deg, var(--c1), var(--c2));
            box-shadow: 0 10px 22px color-mix(in srgb, var(--c1) 30%, transparent);
        }
    </style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var els = document.querySelectorAll('.reveal-up');
    if (!('IntersectionObserver' in window)) {
        els.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
    els.forEach(function (el) { io.observe(el); });
});
</script>
@endsection