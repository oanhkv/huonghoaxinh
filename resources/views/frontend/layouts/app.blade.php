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

        .site-footer {
            background: linear-gradient(180deg, #0f172a 0%, #020617 100%);
            color: rgba(248, 250, 252, 0.88);
            margin-top: 4rem;
        }

        .site-footer a {
            color: rgba(248, 250, 252, 0.75);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .site-footer a:hover {
            color: #fff;
        }

        .footer-heading {
            font-size: 0.75rem;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(248, 250, 252, 0.55);
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .footer-glow {
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(25,135,84,0.35) 0%, transparent 70%);
            filter: blur(8px);
            pointer-events: none;
            top: -120px;
            right: -80px;
            opacity: 0.5;
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

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button">
                        Danh mục
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

    <footer class="site-footer position-relative overflow-hidden pt-5 pb-4">
        <div class="footer-glow"></div>
        <div class="container position-relative" style="z-index: 1;">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="fs-3">🌸</span>
                        <div>
                            <div class="fw-bold fs-5 text-white">{{ $siteName }}</div>
                            <div class="small" style="color: rgba(248,250,252,0.65);">Hoa tươi — quà tặng — giao nhanh</div>
                        </div>
                    </div>
                    <p class="small mb-3" style="color: rgba(248,250,252,0.75);">
                        Thiết kế tinh tế, giao đúng hẹn. Cam kết hoa tươi mới mỗi ngày và chăm sóc khách hàng tận tâm.
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge rounded-pill text-bg-success bg-opacity-75">Giao nội thành</span>
                        <span class="badge rounded-pill text-bg-info bg-opacity-50">Ưu đãi mỗi tuần</span>
                        <span class="badge rounded-pill text-bg-light text-dark bg-opacity-10 border border-light border-opacity-25">Đổi trả trong 24h*</span>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6">
                    <div class="footer-heading">Khám phá</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="mb-2"><a href="{{ route('shop') }}">Cửa hàng</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}">Giới thiệu</a></li>
                        <li class="mb-2"><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="mb-2"><a href="{{ route('vouchers') }}">Mã giảm giá</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-heading">Hỗ trợ</div>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><a href="{{ route('contact') }}">Liên hệ</a></li>
                        <li class="mb-2"><a href="{{ route('cart.index') }}">Giỏ hàng</a></li>
                        @auth
                            <li class="mb-2"><a href="{{ route('orders.history') }}">Đơn hàng của tôi</a></li>
                            <li class="mb-2"><a href="{{ route('profile.edit') }}">Tài khoản</a></li>
                        @else
                            <li class="mb-2"><a href="{{ route('login') }}">Đăng nhập</a></li>
                        @endauth
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="footer-heading">Liên hệ</div>
                    <ul class="list-unstyled small mb-3">
                        <li class="mb-2"><i class="fas fa-phone me-2 text-success"></i>{{ $hotline }}</li>
                        <li class="mb-2"><i class="fas fa-envelope me-2 text-info"></i>{{ $supportEmail }}</li>
                        <li class="mb-2"><i class="fas fa-location-dot me-2 text-danger"></i>{{ $address }}</li>
                    </ul>
                    <div class="d-flex gap-3 fs-5">
                        <a href="{{ $facebookUrl ?: '#' }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
                        <a href="{{ $instagramUrl ?: '#' }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $youtubeUrl ?: '#' }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $zaloUrl ?: '#' }}" target="_blank" rel="noopener" aria-label="Zalo"><i class="fas fa-comment-dots"></i></a>
                    </div>
                </div>
            </div>

            <div class="row align-items-center mt-5 pt-4 border-top border-light border-opacity-10">
                <div class="col-md-6 small" style="color: rgba(248,250,252,0.55);">
                    {{ $siteSettings['copyright_text'] ?? ('© ' . now()->year . ' ' . $siteName . '. All rights reserved.') }}
                </div>
                <div class="col-md-6 text-md-end mt-3 mt-md-0">
                    <img src="https://cdn4793.cdn4s2.com/media/logo/1_1.webp" alt="Đã thông báo Bộ Công Thương" height="44" class="opacity-75">
                </div>
            </div>
        </div>
    </footer>

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