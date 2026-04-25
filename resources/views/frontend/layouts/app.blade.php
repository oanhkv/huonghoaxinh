<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php
        $siteName = $siteSettings['site_name'] ?? 'Hương Hoa Xinh';
        $hotline = $siteSettings['hotline'] ?? '0859 773 086';
        $shippingNote = $siteSettings['free_shipping_note'] ?? 'Giao khu vực Hà Nội — trong vòng 10 km tính từ cửa hàng được miễn phí ship';
        $supportEmail = $siteSettings['support_email'] ?? 'support@huonghoaxinh.vn';
        $address = $siteSettings['address'] ?? config('shop.address_line');
        $logo = $siteSettings['logo'] ?? '';
        $facebookUrl = $siteSettings['facebook_url'] ?? '#';
        $instagramUrl = $siteSettings['instagram_url'] ?? '#';
        $youtubeUrl = $siteSettings['youtube_url'] ?? '#';
        $zaloUrl = $siteSettings['zalo_url'] ?? '#';
    @endphp

    <title>@yield('title', $siteSettings['meta_title'] ?? $siteName) - {{ $siteName }}</title>
    <meta name="description" content="{{ $siteSettings['meta_description'] ?? '' }}">
    <meta name="keywords" content="{{ $siteSettings['meta_keywords'] ?? '' }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    
    <style>
        :root {
            --hh-primary: #198754;
            --hh-primary-dark: #146c43;
            --hh-accent: #e91e8c;
            --hh-dark: #0f172a;
            --hh-muted: #64748b;
            --hh-radius: 16px;
            --hh-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
            --hh-glass: rgba(255, 255, 255, 0.72);
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: var(--hh-dark);
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 38%, #fff 100%);
            min-height: 100vh;
        }

        .site-topbar {
            background: linear-gradient(90deg, #0f172a 0%, #1e293b 45%, #0f766e 100%);
            color: rgba(255,255,255,0.92);
            font-size: 0.875rem;
        }

        .site-topbar a {
            color: rgba(255,255,255,0.95);
        }

        .site-header-main {
            background: var(--hh-glass);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            box-shadow: 0 8px 30px rgba(15, 23, 42, 0.06);
            position: relative;
            z-index: 10;
        }

        .site-brand {
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, var(--hh-primary) 0%, #0d9488 50%, var(--hh-accent) 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .site-search .form-control {
            border-radius: 999px;
            border: 1px solid rgba(15, 23, 42, 0.1);
            padding-left: 1.1rem;
        }

        .site-search .btn {
            border-radius: 999px;
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .site-nav-wrap {
            background: rgba(255,255,255,0.55);
            border-bottom: 1px solid rgba(15, 23, 42, 0.06);
            position: relative;
            z-index: 5;
        }

        header.sticky-top {
            overflow: visible;
        }

        .navbar-nav .dropdown-menu {
            border: none;
            border-radius: var(--hh-radius);
            box-shadow: var(--hh-shadow);
            padding: 0.5rem;
            z-index: 2000;
        }

        .site-header-main .dropdown-menu {
            z-index: 2000;
        }

        /* ========== MEGA DROPDOWN cho Danh mục ========== */
        .mega-dropdown-menu {
            width: min(960px, calc(100vw - 32px));
            margin-top: 0.5rem !important;
            background: #fff;
            animation: megaSlideIn 0.25s ease;
        }
        @keyframes megaSlideIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .mega-dropdown .dropdown-menu { left: 50% !important; transform: translateX(-50%) !important; }
        .mega-cat-grid { background: #fff; }
        .mega-cat-block {
            padding: 14px 16px;
            border-radius: 14px;
            transition: background 0.2s ease, transform 0.2s ease;
            border: 1px solid transparent;
        }
        .mega-cat-block:hover {
            background: linear-gradient(135deg, #f0fdf4 0%, #fff5f8 100%);
            border-color: rgba(25, 135, 84, 0.15);
            transform: translateY(-2px);
        }
        .mega-cat-head {
            color: #0f172a;
            padding: 4px 0;
            transition: color 0.2s ease;
        }
        .mega-cat-head:hover { color: var(--hh-primary, #198754); }
        .mega-cat-head .fa-arrow-right {
            opacity: 0;
            transform: translateX(-5px);
            transition: all 0.25s ease;
            color: var(--hh-primary, #198754);
        }
        .mega-cat-block:hover .mega-cat-head .fa-arrow-right {
            opacity: 1;
            transform: translateX(0);
        }
        .mega-cat-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, rgba(25,135,84,0.15), rgba(214,51,132,0.12));
            color: var(--hh-primary, #198754);
            display: grid; place-items: center;
            font-size: 0.95rem;
        }
        .mega-cat-sublist li { margin-bottom: 4px; }
        .mega-cat-sublink {
            color: #64748b;
            font-size: 0.85rem;
            text-decoration: none;
            transition: color 0.2s ease, padding 0.2s ease;
            display: inline-block;
            padding: 2px 0;
        }
        .mega-cat-sublink:hover {
            color: var(--hh-primary, #198754);
            padding-left: 4px;
        }
        .mega-cat-feature {
            background: linear-gradient(135deg, #d63384 0%, #f06595 100%);
            color: #fff;
            min-height: 100%;
        }
        @media (max-width: 991.98px) {
            .mega-dropdown-menu { width: 100%; }
            .mega-dropdown .dropdown-menu { transform: none !important; left: 0 !important; }
        }

        .site-nav .nav-link {
            font-weight: 600;
            letter-spacing: 0.02em;
            font-size: 0.8rem;
            color: #334155 !important;
            padding: 0.65rem 0.9rem !important;
            border-radius: 999px;
            transition: color 0.2s ease, background 0.2s ease, transform 0.2s ease;
        }

        .site-nav .nav-link:hover,
        .site-nav .nav-link.active {
            color: var(--hh-primary) !important;
            background: rgba(25, 135, 84, 0.1);
            transform: translateY(-1px);
        }

        .icon-chip {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(25,135,84,0.12), rgba(14,165,233,0.12));
        }

        /* ==================== HHX FOOTER REDESIGN ==================== */
        .site-footer {
            position: relative;
            background:
                radial-gradient(900px circle at 12% 0%, rgba(214, 51, 132, 0.18), transparent 60%),
                radial-gradient(800px circle at 92% 100%, rgba(25, 135, 84, 0.18), transparent 60%),
                linear-gradient(180deg, #0f172a 0%, #020617 100%);
            color: rgba(248, 250, 252, 0.85);
            margin-top: 4rem;
            overflow: hidden;
        }
        .site-footer::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #198754 0%, #d63384 50%, #ffc107 100%);
            z-index: 2;
        }
        .site-footer a {
            color: rgba(248, 250, 252, 0.7);
            text-decoration: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .site-footer a:hover {
            color: #fff;
        }

        /* === Newsletter strip === */
        .footer-newsletter {
            background: linear-gradient(135deg, rgba(214, 51, 132, 0.92), rgba(25, 135, 84, 0.92));
            border-radius: 24px;
            padding: 28px 32px;
            margin-bottom: 56px;
            margin-top: -64px;
            position: relative;
            z-index: 5;
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.4);
            overflow: hidden;
        }
        .footer-newsletter::before {
            content: '🌸';
            position: absolute;
            font-size: 200px;
            opacity: 0.1;
            top: -40px; right: -20px;
            transform: rotate(-15deg);
            pointer-events: none;
        }
        .footer-newsletter h3 {
            color: #fff;
            font-weight: 800;
            font-size: 1.4rem;
            margin: 0 0 4px;
        }
        .footer-newsletter p {
            color: rgba(255, 255, 255, 0.85);
            font-size: 0.92rem;
            margin: 0;
        }
        .footer-newsletter-form {
            display: flex; gap: 8px;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            padding: 6px;
            border-radius: 999px;
            border: 1.5px solid rgba(255, 255, 255, 0.25);
        }
        .footer-newsletter-form input {
            flex-grow: 1;
            background: transparent;
            border: 0;
            padding: 10px 18px;
            color: #fff;
            outline: none;
            font-size: 0.9rem;
        }
        .footer-newsletter-form input::placeholder { color: rgba(255, 255, 255, 0.7); }
        .footer-newsletter-form button {
            background: #fff;
            color: #0f172a;
            border: 0;
            padding: 10px 22px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 0.88rem;
            white-space: nowrap;
            transition: transform 0.2s ease;
        }
        .footer-newsletter-form button:hover { transform: translateX(2px); }

        /* === Brand block === */
        .footer-brand {
            display: flex; align-items: center; gap: 12px;
            margin-bottom: 16px;
        }
        .footer-brand-icon {
            width: 52px; height: 52px;
            border-radius: 16px;
            background: linear-gradient(135deg, #d63384, #f06595);
            display: grid; place-items: center;
            font-size: 1.6rem;
            box-shadow: 0 12px 24px rgba(214, 51, 132, 0.35);
        }
        .footer-brand-name {
            font-weight: 800; font-size: 1.25rem;
            color: #fff; line-height: 1.2;
        }
        .footer-brand-tag {
            font-size: 0.78rem;
            color: rgba(248, 250, 252, 0.6);
            letter-spacing: 0.04em;
        }

        .footer-trust-row {
            display: flex; flex-wrap: wrap; gap: 8px;
            margin-top: 16px;
        }
        .footer-trust-chip {
            display: inline-flex; align-items: center; gap: 6px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 5px 12px;
            border-radius: 999px;
            font-size: 0.74rem;
            color: rgba(248, 250, 252, 0.85);
        }
        .footer-trust-chip i { font-size: 0.78rem; }

        /* === Headings === */
        .footer-heading {
            font-size: 0.78rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #fff;
            font-weight: 700;
            margin-bottom: 1.1rem;
            padding-bottom: 0.7rem;
            position: relative;
        }
        .footer-heading::after {
            content: '';
            position: absolute;
            left: 0; bottom: 0;
            width: 32px; height: 2px;
            background: linear-gradient(90deg, #d63384, #198754);
            border-radius: 2px;
        }

        /* === Link list === */
        .footer-links { padding: 0; list-style: none; margin: 0; }
        .footer-links li { margin-bottom: 10px; }
        .footer-links a {
            font-size: 0.88rem;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .footer-links a::before {
            content: '\f054'; /* angle-right */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            font-size: 0.65rem;
            opacity: 0; margin-left: -14px;
            transition: opacity 0.2s ease, margin 0.2s ease;
            color: #d63384;
        }
        .footer-links a:hover { color: #fff; }
        .footer-links a:hover::before { opacity: 1; margin-left: 0; }

        /* === Contact card === */
        .footer-contact-item {
            display: flex; align-items: flex-start; gap: 12px;
            margin-bottom: 14px;
        }
        .footer-contact-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: grid; place-items: center;
            flex-shrink: 0;
            font-size: 0.92rem;
        }
        .footer-contact-icon-phone { color: #20a464; }
        .footer-contact-icon-mail { color: #38bdf8; }
        .footer-contact-icon-pin { color: #f06595; }
        .footer-contact-text { font-size: 0.88rem; line-height: 1.45; flex: 1; min-width: 0; }
        .footer-contact-text small {
            display: block;
            font-size: 0.72rem;
            color: rgba(248, 250, 252, 0.55);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 2px;
        }

        /* === Social row === */
        .footer-social {
            display: flex; gap: 10px;
            margin-top: 18px;
        }
        .footer-social a {
            width: 38px; height: 38px;
            border-radius: 12px;
            display: grid; place-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(248, 250, 252, 0.85);
            transition: all 0.25s ease;
            font-size: 0.95rem;
        }
        .footer-social a:hover {
            background: linear-gradient(135deg, #d63384, #f06595);
            border-color: transparent;
            color: #fff;
            transform: translateY(-3px);
            box-shadow: 0 8px 18px rgba(214, 51, 132, 0.35);
        }
        .footer-social a.fb-link:hover { background: linear-gradient(135deg, #1877f2, #4267b2); box-shadow: 0 8px 18px rgba(24, 119, 242, 0.35); }
        .footer-social a.ig-link:hover { background: linear-gradient(135deg, #f58529, #dd2a7b, #8134af); box-shadow: 0 8px 18px rgba(221, 42, 123, 0.35); }
        .footer-social a.yt-link:hover { background: linear-gradient(135deg, #ff0000, #cc0000); box-shadow: 0 8px 18px rgba(255, 0, 0, 0.35); }
        .footer-social a.zalo-link:hover { background: linear-gradient(135deg, #0068ff, #008fe5); box-shadow: 0 8px 18px rgba(0, 104, 255, 0.35); }

        /* === Bottom bar === */
        .footer-bottom {
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 0.82rem;
            color: rgba(248, 250, 252, 0.55);
        }
        .footer-payment {
            display: flex; flex-wrap: wrap; gap: 8px;
            align-items: center;
        }
        .footer-payment-item {
            background: rgba(255, 255, 255, 0.95);
            color: #0f172a;
            padding: 5px 12px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.72rem;
            letter-spacing: 0.03em;
        }
        .footer-payment-item.cod { background: linear-gradient(135deg, #ffc107, #ff9800); color: #fff; }
        .footer-payment-item.zalo { background: linear-gradient(135deg, #0068ff, #008fe5); color: #fff; }
        .footer-payment-item.momo { background: linear-gradient(135deg, #d63384, #a02466); color: #fff; }

        /* === Back to top === */
        .footer-back-top {
            position: fixed;
            bottom: 24px; right: 24px;
            width: 48px; height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, #198754, #20a464);
            color: #fff;
            display: grid; place-items: center;
            border: 0;
            box-shadow: 0 12px 24px rgba(25, 135, 84, 0.35);
            z-index: 1040;
            opacity: 0; pointer-events: none;
            transform: translateY(20px);
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .footer-back-top.show {
            opacity: 1; pointer-events: auto;
            transform: translateY(0);
        }
        .footer-back-top:hover { transform: translateY(-4px); box-shadow: 0 18px 32px rgba(25, 135, 84, 0.45); }

        @media (max-width: 767.98px) {
            .footer-newsletter { padding: 22px; margin-top: -40px; }
            .footer-newsletter h3 { font-size: 1.1rem; }
            .footer-newsletter-form { flex-direction: column; border-radius: 16px; gap: 6px; }
            .footer-newsletter-form button { width: 100%; }
            .footer-back-top { bottom: 16px; right: 16px; }
        }

        /* Overlay sản phẩm (trang chủ / shop) */
        .product-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.35s ease, box-shadow 0.35s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.12);
        }

        .product-overlay {
            position: absolute;
            inset: 0;
            background: rgba(15, 23, 42, 0.45);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
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
            transform: scale(1.08);
            background: var(--hh-primary);
            color: white;
        }

        @keyframes hh-shimmer {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        .hh-shimmer-bar {
            height: 3px;
            background: linear-gradient(90deg, var(--hh-primary), #0ea5e9, var(--hh-accent), var(--hh-primary));
            background-size: 200% auto;
            animation: hh-shimmer 4s linear infinite;
        }
    </style>

</head>
<body>

    <div class="hh-shimmer-bar"></div>

    <!-- ==================== HEADER & NAVBAR ==================== -->
    <header class="sticky-top" style="z-index: 1030;">
        <div class="site-topbar py-2">
            <div class="container">
                <div class="row align-items-center small g-2">
                    <div class="col-md-6 d-flex align-items-center gap-3 flex-wrap">
                        <span><i class="fas fa-headset me-1 text-info"></i><strong>Hotline</strong> {{ $hotline }}</span>
                        <span class="d-none d-sm-inline opacity-75">|</span>
                        <span class="d-none d-sm-inline"><i class="fas fa-truck me-1"></i>{{ $shippingNote }}</span>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <span class="me-3"><i class="fas fa-shield-halved me-1 text-success"></i>Thanh toán an toàn</span>
                        <span><i class="fas fa-clock me-1"></i>Hỗ trợ nhanh</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="site-header-main py-3">
            <div class="container">
                <div class="row align-items-center g-3">
                    <div class="col-lg-3 col-6">
                        <a href="{{ route('home') }}" class="text-decoration-none d-flex align-items-center gap-2">
                            @if($logo)
                                <img src="{{ asset('storage/' . $logo) }}" alt="{{ $siteName }}" style="height: 48px; object-fit: contain;">
                            @else
                                <span class="fs-2" aria-hidden="true">🌸</span>
                            @endif
                            <span class="site-brand fs-4 mb-0">{{ $siteName }}</span>
                        </a>
                    </div>

                    <div class="col-lg-5 col-12">
                        <form class="d-flex site-search gap-2" action="{{ route('shop') }}">
                            <input type="text" name="search" class="form-control shadow-none" placeholder="Tìm bó hoa, giỏ hoa, quà tặng...">
                            <button class="btn btn-success shadow-sm" type="submit" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button>
                        </form>
                    </div>

                    <div class="col-lg-4 col-6 text-end">
                        @auth
                            <a href="{{ route('wishlist.index') }}" class="text-decoration-none me-4 position-relative">
                                <i class="fas fa-heart fa-lg text-danger"></i>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="wishlistCount">0</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-decoration-none me-4">
                                <i class="fas fa-heart fa-lg text-danger"></i>
                            </a>
                        @endauth
                        
                        <a href="{{ route('cart.index') }}" class="text-decoration-none me-4 position-relative">
                            <i class="fas fa-shopping-cart fa-lg text-success"></i>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
                        </a>

                        @if(Auth::check())
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-success btn-sm dropdown-toggle rounded-pill px-3"
                                        data-bs-toggle="dropdown">
                                    <i class="fas fa-user"></i> {{ Auth::user()->name }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Tài khoản của tôi</a></li>
                                    <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Sản phẩm yêu thích</a></li>
                                    <li><a class="dropdown-item" href="{{ route('cart.index') }}">Giỏ hàng của tôi</a></li>
                                    <li><a class="dropdown-item" href="{{ route('orders.history') }}">Lịch sử đơn hàng</a></li>
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
                            <a href="{{ route('login') }}" class="btn btn-outline-success btn-sm me-2 rounded-pill px-3">
                                <i class="fas fa-user"></i> Đăng nhập
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-success btn-sm rounded-pill px-3">
                                Đăng ký
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

<nav class="navbar navbar-expand-lg site-nav-wrap site-nav py-0">
    <div class="container">
        <button class="navbar-toggler border-0 shadow-none my-2" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse py-lg-2" id="mainNav">
            <ul class="navbar-nav mx-auto align-items-lg-center gap-lg-1">

                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link @if(request()->routeIs('home')) active @endif">
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item dropdown mega-dropdown position-static">
                    <a class="nav-link dropdown-toggle d-inline-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                        <i class="fas fa-th-large small me-1"></i> Danh mục
                    </a>
                    <div class="dropdown-menu mega-dropdown-menu shadow-lg border-0 p-0 rounded-4 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-9 mega-cat-grid p-4">
                                <div class="row g-3">
                                    @foreach(($mainCategories ?? collect())->take(6) as $category)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="mega-cat-block h-100">
                                                <a href="{{ route('shop') }}?category={{ $category->slug }}" class="mega-cat-head d-flex align-items-center gap-2 text-decoration-none">
                                                    <span class="mega-cat-icon"><i class="fas fa-spa"></i></span>
                                                    <span class="fw-bold">{{ $category->name }}</span>
                                                    <i class="fas fa-arrow-right ms-auto small"></i>
                                                </a>
                                                @if($category->children->count() > 0)
                                                    <ul class="mega-cat-sublist list-unstyled mt-2 mb-0">
                                                        @foreach($category->children->take(5) as $sub)
                                                            <li>
                                                                <a href="{{ route('shop') }}?category={{ $sub->slug }}" class="mega-cat-sublink">
                                                                    <i class="fas fa-leaf me-1 small"></i> {{ $sub->name }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @else
                                                    <p class="text-muted small mb-0 mt-2">Khám phá bộ sưu tập</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-lg-3 d-none d-lg-block mega-cat-feature p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <span class="badge bg-white text-danger rounded-pill px-3 py-2 mb-3"><i class="fas fa-fire me-1"></i> Hot</span>
                                    <h5 class="text-white fw-bold mb-2">Hoa Valentine 2026</h5>
                                    <p class="text-white-50 small mb-3">Bộ sưu tập tone đỏ - hồng - pastel hot nhất mùa lễ tình nhân.</p>
                                </div>
                                <a href="{{ route('shop') }}?category=hoa-tinh-yeu" class="btn btn-light btn-sm rounded-pill align-self-start">
                                    Khám phá ngay <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li class="nav-item">
                    <a href="{{ route('about') }}" class="nav-link @if(request()->routeIs('about')) active @endif">
                        Giới thiệu
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('blog.index') }}" class="nav-link @if(request()->routeIs('blog.*')) active @endif">
                        Blog
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('vouchers') }}" class="nav-link @if(request()->routeIs('vouchers')) active @endif">
                        <i class="fas fa-tag me-1"></i>Ưu đãi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('contact') }}" class="nav-link @if(request()->routeIs('contact')) active @endif">
                        Liên hệ
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

    <footer class="site-footer pt-5 pb-4">
        <div class="container position-relative" style="z-index: 1;">

            {{-- ===== Newsletter Strip ===== --}}
            <div class="footer-newsletter">
                <div class="row align-items-center g-3">
                    <div class="col-lg-5">
                        <h3><i class="fas fa-envelope-open-text me-2"></i>Đăng ký nhận ưu đãi</h3>
                        <p>Nhận voucher độc quyền và mẹo chọn hoa hằng tuần qua email.</p>
                    </div>
                    <div class="col-lg-7">
                        <form class="footer-newsletter-form" onsubmit="event.preventDefault(); this.querySelector('button').innerHTML='<i class=\'fas fa-check me-1\'></i> Đã đăng ký';">
                            <input type="email" placeholder="email-cua-ban@gmail.com" required>
                            <button type="submit"><i class="fas fa-paper-plane me-1"></i> Đăng ký ngay</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ===== Footer Main 4 cột ===== --}}
            <div class="row g-4">
                {{-- Brand --}}
                <div class="col-lg-4 col-md-12">
                    <div class="footer-brand">
                        <div class="footer-brand-icon">🌸</div>
                        <div>
                            <div class="footer-brand-name">{{ $siteName }}</div>
                            <div class="footer-brand-tag">Hoa tươi · Quà tặng · Giao nhanh</div>
                        </div>
                    </div>
                    <p class="small mb-2" style="color: rgba(248,250,252,0.7); line-height: 1.65;">
                        Thiết kế tinh tế, giao đúng hẹn. Mỗi bó hoa được bó thủ công bởi đội ngũ florists giàu kinh nghiệm — gửi gắm trọn vẹn cảm xúc đến người nhận.
                    </p>

                    <div class="footer-trust-row">
                        <span class="footer-trust-chip"><i class="fas fa-truck-fast text-info"></i> Giao 2H Hà Nội</span>
                        <span class="footer-trust-chip"><i class="fas fa-shield-heart text-danger"></i> Hoa tươi 100%</span>
                        <span class="footer-trust-chip"><i class="fas fa-rotate-left text-warning"></i> Đổi trả 24h</span>
                    </div>

                    <div class="footer-social">
                        <a href="{{ $facebookUrl ?: '#' }}" target="_blank" rel="noopener" class="fb-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $instagramUrl ?: '#' }}" target="_blank" rel="noopener" class="ig-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $youtubeUrl ?: '#' }}" target="_blank" rel="noopener" class="yt-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $zaloUrl ?: '#' }}" target="_blank" rel="noopener" class="zalo-link" aria-label="Zalo"><i class="fas fa-comment-dots"></i></a>
                    </div>
                </div>

                {{-- Khám phá --}}
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="footer-heading">Khám phá</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li><a href="{{ route('shop') }}">Cửa hàng</a></li>
                        <li><a href="{{ route('about') }}">Giới thiệu</a></li>
                        <li><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li><a href="{{ route('vouchers') }}">Mã giảm giá</a></li>
                    </ul>
                </div>

                {{-- Hỗ trợ + Danh mục --}}
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="footer-heading">Hỗ trợ</div>
                    <ul class="footer-links">
                        <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                        <li><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                        @auth
                            <li><a href="{{ route('orders.history') }}">Đơn của tôi</a></li>
                            <li><a href="{{ route('profile.edit') }}">Tài khoản</a></li>
                            <li><a href="{{ route('wishlist.index') }}">Yêu thích</a></li>
                        @else
                            <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                            <li><a href="{{ route('register') }}">Đăng ký</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Liên hệ --}}
                <div class="col-lg-4 col-md-4">
                    <div class="footer-heading">Liên hệ ngay</div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon footer-contact-icon-phone"><i class="fas fa-phone"></i></div>
                        <div class="footer-contact-text">
                            <small>Hotline 24/7</small>
                            <a href="tel:{{ preg_replace('/\s+/', '', $hotline) }}" class="fw-bold">{{ $hotline }}</a>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon footer-contact-icon-mail"><i class="fas fa-envelope"></i></div>
                        <div class="footer-contact-text">
                            <small>Email hỗ trợ</small>
                            <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon footer-contact-icon-pin"><i class="fas fa-location-dot"></i></div>
                        <div class="footer-contact-text">
                            <small>Cửa hàng</small>
                            <span style="color: rgba(248,250,252,0.85);">{{ $address }}</span>
                        </div>
                    </div>

                    <div class="footer-contact-item">
                        <div class="footer-contact-icon" style="color: #fbbf24;"><i class="far fa-clock"></i></div>
                        <div class="footer-contact-text">
                            <small>Giờ làm việc</small>
                            <span style="color: rgba(248,250,252,0.85);">7:30 – 22:00 (Tất cả các ngày)</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== Footer Bottom ===== --}}
            <div class="footer-bottom">
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <div>{{ $siteSettings['copyright_text'] ?? ('© ' . now()->year . ' ' . $siteName . '. All rights reserved.') }}</div>
                    </div>
                    <div class="col-md-4 text-md-center">
                        <div class="footer-payment justify-content-md-center">
                            <small style="color: rgba(248,250,252,0.45); margin-right: 4px;">Thanh toán:</small>
                            <span class="footer-payment-item cod">COD</span>
                            <span class="footer-payment-item zalo">ZaloPay</span>
                            <span class="footer-payment-item momo">MoMo</span>
                            <span class="footer-payment-item">VISA</span>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <img src="https://cdn4793.cdn4s2.com/media/logo/1_1.webp" alt="Đã thông báo Bộ Công Thương" height="40" style="opacity: 0.7;">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    {{-- Back to top button --}}
    <button class="footer-back-top" id="backToTopBtn" type="button" aria-label="Lên đầu trang">
        <i class="fas fa-chevron-up"></i>
    </button>
    <script>
        (function () {
            const btn = document.getElementById('backToTopBtn');
            if (!btn) return;
            const onScroll = () => btn.classList.toggle('show', window.scrollY > 360);
            window.addEventListener('scroll', onScroll, { passive: true });
            btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
            onScroll();
        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Khởi tạo cart count
        updateCartCount();
        @auth
            updateWishlistCount();
        @endauth

        document.addEventListener('visibilitychange', function () {
            if (document.visibilityState === 'visible') {
                updateCartCount();
                @auth
                    updateWishlistCount();
                @endauth
            }
        });
        window.addEventListener('pageshow', function (ev) {
            if (ev.persisted) {
                updateCartCount();
                @auth
                    updateWishlistCount();
                @endauth
            }
        });

        // Cập nhật số lượng giỏ hàng
        function updateCartCount() {
            const cartCountBadge = document.getElementById('cartCount');
            fetch('{{ url('/cart/get') }}', {
                credentials: 'same-origin',
                cache: 'no-store',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(function (response) {
                if (!response.ok) throw new Error('cart');
                return response.json();
            })
            .then(function (data) {
                if (!cartCountBadge) return;
                let qty = 0;
                if (data.quantity_sum !== undefined && data.quantity_sum !== null) {
                    qty = parseInt(data.quantity_sum, 10) || 0;
                } else if (Array.isArray(data.items)) {
                    qty = data.items.reduce(function (sum, item) {
                        return sum + (parseInt(item.quantity, 10) || 0);
                    }, 0);
                }
                cartCountBadge.textContent = qty > 0 ? String(qty) : '0';
            })
            .catch(function () {
                if (cartCountBadge) cartCountBadge.textContent = '0';
            });
        }

        // Cập nhật số lượng wishlist
        function updateWishlistCount() {
            @auth
                fetch('{{ route("wishlist.index") }}', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Count wishlist items from DOM
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const count = doc.querySelectorAll('.product-card').length;
                    
                    const wishlistCountBadge = document.getElementById('wishlistCount');
                    if (wishlistCountBadge) {
                        wishlistCountBadge.textContent = count > 0 ? count : '0';
                    }
                });
            @endauth
        }

        // Add to cart function
        function addToCart(productId, quantity = 1, unitPrice = null, variant = null) {
            const payload = {
                product_id: productId,
                quantity: parseInt(quantity),
            };

            if (unitPrice !== null) {
                payload.unit_price = parseFloat(unitPrice);
            }

            if (variant) {
                payload.variant = variant;
            }

            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateCartCount();
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            })
            .catch(error => {
                showNotification('Lỗi: ' + error.message, 'error');
            });
        }

        // Toggle wishlist
        function toggleWishlist(productId, event = null) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }

            @auth
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        updateWishlistCount();
                        
                        // Update heart icon if on product card
                        const wishlistBtn = document.querySelector(`button[onclick*="toggleWishlist(${productId}"]`);
                        if (wishlistBtn) {
                            const icon = wishlistBtn.querySelector('i');
                            if (icon) {
                                if (data.action === 'added') {
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                } else {
                                    icon.classList.remove('fas');
                                    icon.classList.add('far');
                                }
                            }
                        }
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Lỗi: ' + error.message, 'error');
                });
            @else
                window.location.href = '{{ route("login") }}';
            @endauth
        }

        // Hiển thị notification
        function showNotification(message, type = 'info') {
            const alertClass = type === 'success' ? 'alert-success' : (type === 'error' ? 'alert-danger' : 'alert-info');
            const id = 'hh-toast-' + Date.now();
            const alertHtml = `
                <div id="${id}" class="hh-toast alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" role="alert" style="z-index: 9999; max-width: 400px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            setTimeout(function () {
                const el = document.getElementById(id);
                if (el) el.remove();
            }, 3500);
        }
    </script>
</body>
</html>