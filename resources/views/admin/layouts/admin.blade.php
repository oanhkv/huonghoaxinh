<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') · Admin Hương Hoa Xinh</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @php
        $unreadMsg = \Illuminate\Support\Facades\Schema::hasTable('contact_messages')
            ? \App\Models\ContactMessage::where('status', 'new')->count() : 0;
        $pendingOrd = \App\Models\Order::where('status', 'pending')->count();
        $lowStock = \App\Models\Product::where('is_active', true)->where('stock', '<=', 5)->count();
    @endphp

    <style>
        :root {
            --hhx-pink: #d63384;
            --hhx-pink-2: #f06595;
            --hhx-green: #198754;
            --hhx-green-2: #20a464;
            --hhx-text: #0f172a;
            --hhx-muted: #64748b;
            --hhx-border: #e2e8f0;
            --hhx-bg: #f8fafc;

            --sidebar-w: 264px;
            --topbar-h: 70px;
            /* Tone xanh dương pastel - sky 100 → 300 */
            --sidebar-bg: linear-gradient(180deg, #e0f2fe 0%, #bae6fd 50%, #93c5fd 100%);
            --sidebar-text: #0c4a6e;
            --sidebar-text-muted: #075985;
            --sidebar-border: rgba(7, 89, 133, 0.12);
        }

        * { box-sizing: border-box; }
        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(900px circle at 5% 0%, rgba(214, 51, 132, 0.05), transparent 40%),
                radial-gradient(900px circle at 95% 100%, rgba(25, 135, 84, 0.05), transparent 40%),
                var(--hhx-bg);
            color: var(--hhx-text);
            min-height: 100vh;
        }

        .admin-shell { display: flex; min-height: 100vh; }

        /* ============ SIDEBAR (xanh dương pastel) ============ */
        .admin-sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 6px 0 24px rgba(14, 116, 144, 0.12);
            z-index: 10;
        }
        .admin-sidebar::before {
            content: '';
            position: absolute;
            top: 0; right: 0;
            width: 3px; height: 100%;
            background: linear-gradient(180deg, var(--hhx-pink) 0%, var(--hhx-green) 100%);
        }

        .sidebar-brand {
            padding: 22px 20px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex; align-items: center; gap: 12px;
        }
        .sidebar-brand-icon {
            width: 44px; height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
            display: grid; place-items: center;
            font-size: 1.2rem;
            box-shadow: 0 8px 20px rgba(214, 51, 132, 0.35);
        }
        .sidebar-brand-name {
            font-weight: 800;
            color: #0c4a6e;
            font-size: 1.05rem;
            letter-spacing: -0.01em;
            line-height: 1.2;
            text-shadow: 0 1px 0 rgba(255, 255, 255, 0.5);
        }
        .sidebar-brand-tag {
            font-size: 0.7rem;
            color: #0369a1;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            font-weight: 700;
            opacity: 0.78;
        }

        .sidebar-section-label {
            padding: 16px 22px 8px;
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            color: #0369a1;
            font-weight: 800;
            opacity: 0.72;
        }

        .sidebar-nav {
            flex-grow: 1;
            padding: 0 12px 20px;
            overflow-y: auto;
            list-style: none;
            margin: 0;
        }
        .sidebar-nav::-webkit-scrollbar { width: 6px; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(2, 132, 199, 0.18); border-radius: 6px; }

        .sidebar-link {
            display: flex; align-items: center; gap: 12px;
            padding: 11px 14px;
            border-radius: 12px;
            color: #0c4a6e;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.92rem;
            margin-bottom: 4px;
            transition: all 0.2s ease;
            position: relative;
        }
        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.55);
            color: #075985;
            transform: translateX(2px);
            box-shadow: 0 4px 12px rgba(14, 116, 144, 0.12);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
            color: #fff;
            box-shadow: 0 10px 20px rgba(214, 51, 132, 0.35);
            transform: none;
        }
        .sidebar-link.active::before {
            content: '';
            position: absolute;
            left: -12px; top: 50%; transform: translateY(-50%);
            width: 4px; height: 24px; border-radius: 4px;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(255,255,255,0.4);
        }
        .sidebar-link i:first-child {
            width: 22px; text-align: center;
            font-size: 0.95rem;
            color: #0369a1;
            transition: color 0.2s ease;
        }
        .sidebar-link:hover i:first-child { color: #d63384; }
        .sidebar-link.active i:first-child { color: #fff; }

        .sidebar-link .sidebar-badge {
            margin-left: auto;
            background: var(--hhx-pink);
            color: #fff;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            min-width: 22px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(214, 51, 132, 0.3);
        }
        .sidebar-link.active .sidebar-badge { background: rgba(255, 255, 255, 0.3); box-shadow: none; }
        .sidebar-link .arrow {
            margin-left: auto;
            transition: transform 0.2s ease;
            font-size: 0.75rem;
            opacity: 0.6;
        }
        .sidebar-link[aria-expanded="true"] .arrow { transform: rotate(90deg); }

        .sidebar-submenu {
            padding-left: 30px;
            margin-bottom: 6px;
        }
        .sidebar-submenu .sidebar-link {
            font-size: 0.85rem;
            padding: 8px 12px;
            color: #0369a1;
            font-weight: 600;
        }
        .sidebar-submenu .sidebar-link:hover { color: #075985; }
        .sidebar-submenu .sidebar-link.active {
            background: rgba(214, 51, 132, 0.16);
            color: #b02a6c;
            box-shadow: none;
            font-weight: 700;
        }
        .sidebar-submenu .sidebar-link.active::before { display: none; }
        .sidebar-submenu .sidebar-link.active i:first-child { color: #d63384; }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--sidebar-border);
            font-size: 0.78rem;
            color: #0369a1;
        }
        .sidebar-footer a { color: #0c4a6e; text-decoration: none; font-weight: 700; }
        .sidebar-footer a:hover { color: #d63384; }

        /* ============ MAIN ============ */
        .admin-main { flex-grow: 1; min-width: 0; }

        .admin-topbar {
            position: sticky; top: 0; z-index: 20;
            height: var(--topbar-h);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(14px);
            border-bottom: 1px solid var(--hhx-border);
            padding: 0 24px;
            display: flex; align-items: center; justify-content: space-between;
            gap: 16px;
        }
        .topbar-left { display: flex; align-items: center; gap: 18px; min-width: 0; flex: 1; }
        .topbar-toggle {
            display: none;
            width: 38px; height: 38px;
            border-radius: 10px;
            background: var(--hhx-bg);
            border: 0; color: var(--hhx-text);
        }
        .topbar-title-wrap { min-width: 0; }
        .topbar-eyebrow {
            font-size: 0.72rem;
            color: var(--hhx-muted);
            letter-spacing: 0.04em;
            text-transform: uppercase;
            font-weight: 600;
        }
        .topbar-title {
            font-weight: 800;
            font-size: 1.2rem;
            margin: 0;
            letter-spacing: -0.02em;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
        }

        .topbar-search {
            position: relative;
            max-width: 320px;
            flex-grow: 1;
        }
        .topbar-search input {
            width: 100%;
            background: var(--hhx-bg);
            border: 1px solid transparent;
            border-radius: 999px;
            padding: 9px 16px 9px 40px;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }
        .topbar-search input:focus {
            outline: 0;
            border-color: var(--hhx-pink);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(214, 51, 132, 0.08);
        }
        .topbar-search i {
            position: absolute;
            left: 14px; top: 50%; transform: translateY(-50%);
            color: var(--hhx-muted);
            font-size: 0.85rem;
        }

        .topbar-actions { display: flex; align-items: center; gap: 8px; }
        .topbar-btn {
            position: relative;
            width: 40px; height: 40px;
            border-radius: 12px;
            background: var(--hhx-bg);
            border: 1px solid var(--hhx-border);
            color: var(--hhx-text);
            display: grid; place-items: center;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .topbar-btn:hover {
            background: #fff;
            color: var(--hhx-pink);
            border-color: var(--hhx-pink);
            transform: translateY(-1px);
        }
        .topbar-btn-dot {
            position: absolute;
            top: 7px; right: 8px;
            width: 8px; height: 8px;
            background: var(--hhx-pink);
            border: 2px solid #fff;
            border-radius: 50%;
        }
        .topbar-btn-count {
            position: absolute;
            top: -5px; right: -5px;
            background: var(--hhx-pink);
            color: #fff;
            font-size: 0.62rem;
            font-weight: 700;
            min-width: 18px; height: 18px;
            border-radius: 999px;
            padding: 0 5px;
            display: grid; place-items: center;
            border: 2px solid #fff;
        }

        .topbar-user {
            display: flex; align-items: center; gap: 10px;
            padding: 4px 14px 4px 4px;
            background: var(--hhx-bg);
            border: 1px solid var(--hhx-border);
            border-radius: 999px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        .topbar-user:hover {
            background: #fff;
            border-color: var(--hhx-pink);
        }
        .topbar-user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
            color: #fff;
            display: grid; place-items: center;
            font-weight: 800;
            font-size: 0.85rem;
        }
        .topbar-user-name {
            font-weight: 700;
            font-size: 0.85rem;
            line-height: 1;
        }
        .topbar-user-role {
            font-size: 0.7rem;
            color: var(--hhx-muted);
        }

        /* ============ CONTENT ============ */
        .admin-content { padding: 28px; }

        .admin-card {
            background: #fff;
            border: 1px solid var(--hhx-border);
            border-radius: 18px;
            box-shadow: 0 6px 22px rgba(15, 23, 42, 0.04);
            overflow: hidden;
        }
        .admin-card-header {
            padding: 18px 22px;
            border-bottom: 1px solid var(--hhx-border);
            background: #fff;
            display: flex; align-items: center; justify-content: space-between; gap: 12px;
            flex-wrap: wrap;
        }
        .admin-card-title {
            font-weight: 800;
            font-size: 1rem;
            margin: 0;
            display: flex; align-items: center; gap: 10px;
        }
        .admin-card-title i {
            width: 32px; height: 32px;
            border-radius: 10px;
            background: linear-gradient(135deg, rgba(214, 51, 132, 0.12), rgba(25, 135, 84, 0.1));
            color: var(--hhx-pink);
            display: grid; place-items: center;
            font-size: 0.88rem;
        }

        /* Override Bootstrap card to use our style */
        .card {
            border: 1px solid var(--hhx-border);
            border-radius: 18px;
            box-shadow: 0 6px 22px rgba(15, 23, 42, 0.04);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--hhx-border);
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            font-weight: 700;
        }

        /* Tables */
        .table { margin-bottom: 0; }
        .table thead th {
            font-size: 0.74rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--hhx-muted);
            background: var(--hhx-bg);
            border-bottom: 1px solid var(--hhx-border);
            padding: 14px 16px;
            font-weight: 700;
        }
        .table tbody tr { transition: background 0.15s ease; }
        .table tbody tr:hover { background: var(--hhx-bg); }
        .table tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        /* Forms */
        .form-control, .form-select {
            border-radius: 12px;
            border-color: var(--hhx-border);
            padding: 10px 14px;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--hhx-pink);
            box-shadow: 0 0 0 3px rgba(214, 51, 132, 0.12);
        }
        .form-label { font-weight: 700; color: var(--hhx-text); }

        /* Buttons */
        .btn { border-radius: 12px; font-weight: 700; padding: 9px 18px; }
        .btn-primary {
            background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
            border: 0;
            box-shadow: 0 8px 18px rgba(214, 51, 132, 0.25);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #b02a6c, #d63384);
            transform: translateY(-1px);
            box-shadow: 0 12px 22px rgba(214, 51, 132, 0.35);
        }
        .btn-success {
            background: linear-gradient(135deg, var(--hhx-green), var(--hhx-green-2));
            border: 0;
            box-shadow: 0 8px 18px rgba(25, 135, 84, 0.22);
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #14653f, #198754);
            transform: translateY(-1px);
        }
        .btn-outline-primary {
            border: 1.5px solid var(--hhx-pink);
            color: var(--hhx-pink);
        }
        .btn-outline-primary:hover {
            background: var(--hhx-pink);
            border-color: var(--hhx-pink);
            color: #fff;
        }
        .btn-outline-success {
            border: 1.5px solid var(--hhx-green);
            color: var(--hhx-green);
        }
        .btn-outline-success:hover {
            background: var(--hhx-green);
            border-color: var(--hhx-green);
            color: #fff;
        }

        .badge { border-radius: 999px; font-weight: 700; padding: 5px 10px; }

        /* Flash messages */
        .flash-wrap {
            position: fixed; top: 88px; right: 24px;
            z-index: 1090;
            width: min(380px, calc(100vw - 48px));
            pointer-events: none;
        }
        .flash-wrap .alert {
            border: 0; border-radius: 14px;
            box-shadow: 0 16px 36px rgba(15, 23, 42, 0.14);
            pointer-events: auto;
            animation: hhxFlashIn 0.3s ease;
        }
        @keyframes hhxFlashIn {
            from { opacity: 0; transform: translateY(-12px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Utilities */
        .admin-section-title {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--hhx-muted);
            font-weight: 800;
            margin-bottom: 0.75rem;
        }

        /* Responsive */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                position: fixed; left: -280px;
                width: 264px;
                transition: left 0.3s ease;
                z-index: 1050;
            }
            .admin-sidebar.show { left: 0; }
            .topbar-toggle { display: grid; }
            .admin-content { padding: 18px; }
            .topbar-search { display: none; }
        }

        .admin-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(15, 23, 42, 0.5);
            z-index: 1040;
        }
        .admin-overlay.show { display: block; }
    </style>
    @yield('head')
</head>
<body>
    @php
        $admin = Auth::guard('admin')->user();
        $adminInitial = strtoupper(mb_substr($admin->name ?? 'A', 0, 1));
    @endphp

    <div class="admin-shell">
        <!-- ============ SIDEBAR ============ -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">🌸</div>
                <div>
                    <div class="sidebar-brand-name">Hương Hoa Xinh</div>
                    <div class="sidebar-brand-tag">Admin Panel</div>
                </div>
            </div>

            <div class="sidebar-section-label">Tổng quan</div>
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-grip"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.revenue.index') }}" class="sidebar-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-column"></i> Doanh thu
                    </a>
                </li>

                <li><div class="sidebar-section-label" style="padding-left: 6px;">Bán hàng</div></li>
                <li>
                    <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-bag-shopping"></i> Đơn hàng
                        @if($pendingOrd > 0)<span class="sidebar-badge">{{ $pendingOrd }}</span>@endif
                    </a>
                </li>
                <li>
                    <a href="#smProducts" class="sidebar-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}" data-bs-toggle="collapse" role="button"
                       aria-expanded="{{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }}">
                        <i class="fas fa-box"></i> Sản phẩm
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="smProducts">
                        <div class="sidebar-submenu">
                            <a href="{{ route('admin.products.index') }}" class="sidebar-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> Danh sách sản phẩm
                                @if($lowStock > 0)<span class="sidebar-badge" title="{{ $lowStock }} sản phẩm sắp hết">{{ $lowStock }}</span>@endif
                            </a>
                            <a href="{{ route('admin.categories.index') }}" class="sidebar-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> Danh mục
                            </a>
                        </div>
                    </div>
                </li>
                <li>
                    <a href="{{ route('admin.vouchers.index') }}" class="sidebar-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}">
                        <i class="fas fa-ticket"></i> Mã giảm giá
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}" class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <i class="fas fa-star"></i> Đánh giá
                    </a>
                </li>

                <li><div class="sidebar-section-label" style="padding-left: 6px;">Quan hệ</div></li>
                <li>
                    <a href="{{ route('admin.contact-messages.index') }}" class="sidebar-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                        <i class="fas fa-envelope"></i> Tin nhắn liên hệ
                        @if($unreadMsg > 0)<span class="sidebar-badge">{{ $unreadMsg }}</span>@endif
                    </a>
                </li>
                <li>
                    <a href="#smUsers" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-bs-toggle="collapse" role="button"
                       aria-expanded="{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}">
                        <i class="fas fa-users"></i> Tài khoản
                        <i class="fas fa-chevron-right arrow"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="smUsers">
                        <div class="sidebar-submenu">
                            <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') ? 'active' : '' }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> Khách hàng
                            </a>
                            <a href="{{ route('admin.users.admins') }}" class="sidebar-link {{ request()->routeIs('admin.users.admins') ? 'active' : '' }}">
                                <i class="fas fa-circle" style="font-size: 6px;"></i> Admin
                            </a>
                        </div>
                    </div>
                </li>

                <li><div class="sidebar-section-label" style="padding-left: 6px;">Hệ thống</div></li>
                <li>
                    <a href="{{ route('admin.settings.edit') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-gear"></i> Cài đặt website
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.profile.edit') }}" class="sidebar-link {{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                        <i class="fas fa-user-gear"></i> Hồ sơ admin
                    </a>
                </li>
            </ul>

            <div class="sidebar-footer">
                <div class="d-flex align-items-center justify-content-between">
                    <span><i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i> Hệ thống ổn định</span>
                    <a href="{{ route('home') }}" target="_blank" title="Xem trang bán hàng"><i class="fas fa-up-right-from-square"></i></a>
                </div>
                <div class="mt-1" style="font-size: 0.7rem;">v1.0 · {{ now()->format('Y') }}</div>
            </div>
        </aside>
        <div class="admin-overlay" id="adminOverlay"></div>

        <!-- ============ MAIN ============ -->
        <main class="admin-main">
            <header class="admin-topbar">
                <div class="topbar-left">
                    <button class="topbar-toggle" id="sidebarToggle" type="button" aria-label="Menu"><i class="fas fa-bars"></i></button>
                    <div class="topbar-title-wrap">
                        <div class="topbar-eyebrow">Trang quản trị</div>
                        <h1 class="topbar-title">@yield('title')</h1>
                    </div>
                </div>

                <div class="topbar-search d-none d-md-block">
                    <i class="fas fa-magnifying-glass"></i>
                    <input type="search" placeholder="Tìm sản phẩm, đơn hàng...">
                </div>

                <div class="topbar-actions">
                    <a href="{{ route('home') }}" target="_blank" class="topbar-btn d-none d-sm-grid" title="Xem trang bán hàng"><i class="fas fa-store"></i></a>
                    <a href="{{ route('admin.contact-messages.index') }}" class="topbar-btn" title="Tin nhắn">
                        <i class="fas fa-envelope"></i>
                        @if($unreadMsg > 0)<span class="topbar-btn-count">{{ $unreadMsg > 99 ? '99+' : $unreadMsg }}</span>@endif
                    </a>
                    <a href="{{ route('admin.orders.index') }}?status=pending" class="topbar-btn" title="Đơn chờ xử lý">
                        <i class="fas fa-bell"></i>
                        @if($pendingOrd > 0)<span class="topbar-btn-dot"></span>@endif
                    </a>

                    <div class="dropdown">
                        <div class="topbar-user" data-bs-toggle="dropdown" role="button">
                            <div class="topbar-user-avatar">{{ $adminInitial }}</div>
                            <div class="d-none d-md-block">
                                <div class="topbar-user-name">{{ $admin->name ?? 'Admin' }}</div>
                                <div class="topbar-user-role">Quản trị viên</div>
                            </div>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="border: 1px solid var(--hhx-border); border-radius: 14px; padding: 8px;">
                            <li><h6 class="dropdown-header" style="color: var(--hhx-muted); font-size: 0.75rem;">Đăng nhập với</h6></li>
                            <li><span class="dropdown-item-text small fw-bold">{{ $admin->email ?? '' }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item rounded" href="{{ route('admin.profile.edit') }}"><i class="fas fa-user-gear me-2"></i>Hồ sơ cá nhân</a></li>
                            <li><a class="dropdown-item rounded" href="{{ route('admin.settings.edit') }}"><i class="fas fa-gear me-2"></i>Cài đặt</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item rounded text-danger" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-arrow-right-from-bracket me-2"></i>Đăng xuất
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <div class="admin-content">
                <div class="flash-wrap">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-circle-exclamation me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>

                @yield('content')
            </div>
        </main>
    </div>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">@csrf</form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const sb = document.getElementById('adminSidebar');
            const ov = document.getElementById('adminOverlay');
            const tb = document.getElementById('sidebarToggle');
            if (tb) tb.addEventListener('click', () => { sb.classList.toggle('show'); ov.classList.toggle('show'); });
            if (ov) ov.addEventListener('click', () => { sb.classList.remove('show'); ov.classList.remove('show'); });
        })();
    </script>
    @yield('scripts')
</body>
</html>
