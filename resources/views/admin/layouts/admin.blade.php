<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin Hương Hoa Xinh</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --admin-bg: #f5faff;
            --admin-surface: #ffffff;
            --admin-border: #d7e6f5;
            --admin-text: #10233d;
            --admin-muted: #6b7a90;
            --admin-primary: #4a8df7;
            --admin-primary-strong: #3577e8;
            --admin-success: #16a34a;
            --admin-warning: #f59e0b;
            --admin-danger: #dc2626;
            --admin-sidebar: linear-gradient(180deg, #cfe6fb 0%, #bcd8f4 100%);
            --admin-sidebar-text: #24496f;
            --admin-sidebar-active: linear-gradient(135deg, #4a8df7 0%, #3577e8 100%);
        }

        body {
            font-family: 'Manrope', sans-serif;
            background:
                radial-gradient(circle at top right, rgba(47, 128, 237, 0.10), transparent 28%),
                linear-gradient(180deg, #f8fbff 0%, #f4f7fb 100%);
            color: var(--admin-text);
        }

        .admin-shell {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--admin-sidebar);
            color: var(--admin-sidebar-text);
            width: 260px;
            position: sticky;
            top: 0;
            box-shadow: 0 12px 30px rgba(79, 130, 216, 0.20);
        }

        .brand-mark {
            width: 58px;
            height: 58px;
            border-radius: 20px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #ffffff 0%, #f4f8ff 100%);
            border: 1px solid rgba(74, 141, 247, 0.24);
            box-shadow: 0 14px 28px rgba(74, 141, 247, 0.18);
            position: relative;
        }

        .brand-mark::after {
            content: '';
            position: absolute;
            inset: 6px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(74, 141, 247, 0.16), rgba(56, 189, 248, 0.08));
        }

        .sidebar-title {
            letter-spacing: 0.08em;
            font-size: 0.72rem;
            text-transform: uppercase;
            color: #35608d;
        }

        .brand-name {
            color: #173b63;
            letter-spacing: -0.04em;
            text-shadow: 0 1px 0 rgba(255,255,255,0.6);
        }

        .brand-subtitle {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            background: rgba(255,255,255,0.56);
            color: #3a5f85;
            font-size: 0.72rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .nav-link {
            color: var(--admin-sidebar-text);
            padding: 12px 16px;
            border-radius: 14px;
            margin: 4px 8px;
            transition: all 0.2s ease;
            font-weight: 600;
        }
        .nav-link:hover, .nav-link.active {
            background: var(--admin-sidebar-active);
            color: white;
            transform: translateX(2px);
            box-shadow: 0 10px 20px rgba(53, 119, 232, 0.22);
        }
        .sidebar-group .nav-link {
            margin-bottom: 0;
        }
        .submenu .nav-link {
            margin-left: 18px;
            font-size: 0.95rem;
            padding: 10px 14px;
        }
        @media (hover: hover) {
            .sidebar-group:hover .collapse {
                display: block;
            }
        }
        @media (hover: hover) {
            .account-group:hover .collapse {
                display: block;
            }
        }
        .navbar-admin {
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(230, 235, 242, 0.9);
        }
        .page-title {
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .admin-content {
            padding: 24px;
        }
        .card {
            border: 1px solid var(--admin-border);
            border-radius: 18px;
            box-shadow: 0 10px 28px rgba(16, 35, 61, 0.06);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid var(--admin-border);
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
        }
        .table {
            margin-bottom: 0;
        }
        .table thead th {
            font-size: 0.82rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #355071;
            background: linear-gradient(180deg, #eff6ff 0%, #e8f1ff 100%);
            border-bottom: 1px solid var(--admin-border);
            padding-top: 14px;
            padding-bottom: 14px;
        }
        .table tbody tr:hover {
            background: #f8fbff;
        }
        .admin-table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .admin-table thead th:first-child {
            border-top-left-radius: 16px;
        }
        .admin-table thead th:last-child {
            border-top-right-radius: 16px;
        }
        .admin-table tbody td {
            vertical-align: middle;
            border-color: #edf2f7;
            padding-top: 14px;
            padding-bottom: 14px;
        }
        .card-stat {
            border: none;
            border-radius: 18px;
            transition: all 0.25s ease;
            overflow: hidden;
        }
        .card-stat:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 36px rgba(0,0,0,0.12);
        }
        .btn {
            border-radius: 12px;
            font-weight: 700;
        }
        .btn-outline-success {
            border-color: #22c55e;
            color: #15803d;
        }
        .btn-outline-success:hover {
            background: #22c55e;
            border-color: #22c55e;
        }
        .btn-outline-primary {
            border-color: var(--admin-primary);
            color: var(--admin-primary);
        }
        .btn-outline-primary:hover {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
        }
        .btn-primary {
            background: linear-gradient(135deg, #2f80ed, #1d6fe3);
            border: 0;
            box-shadow: 0 10px 18px rgba(47, 128, 237, 0.18);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d6fe3, #1558c9);
        }
        .form-control, .form-select {
            border-radius: 12px;
            border-color: #d9e1ec;
            box-shadow: none !important;
        }
        .badge {
            border-radius: 999px;
            font-weight: 700;
        }
        .flash-wrap {
            position: fixed;
            top: 88px;
            left: 24px;
            z-index: 1090;
            width: min(420px, calc(100vw - 48px));
            pointer-events: none;
        }
        .flash-wrap .alert {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 16px 30px rgba(15, 23, 42, 0.12);
            pointer-events: auto;
        }
        .content-card {
            background: rgba(255,255,255,0.78);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(230,235,242,0.72);
            border-radius: 20px;
        }
        .admin-form-shell {
            max-width: 1120px;
            margin: 0 auto;
        }
        .admin-form-card {
            border-radius: 24px;
            overflow: hidden;
        }
        .admin-form-hero {
            background: linear-gradient(135deg, #eff7ff 0%, #dfefff 100%);
            border-bottom: 1px solid rgba(215, 230, 245, 0.95);
        }
        .admin-form-icon {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: linear-gradient(135deg, #4a8df7 0%, #3577e8 100%);
            color: #fff;
            box-shadow: 0 12px 24px rgba(74, 141, 247, 0.22);
        }
        .admin-form-subtitle {
            color: var(--admin-muted);
            margin-bottom: 0;
        }
        .admin-section-title {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: #5c7ea8;
            font-weight: 800;
            margin-bottom: 0.75rem;
        }
        .admin-form-panel {
            background: #fff;
            border: 1px solid var(--admin-border);
            border-radius: 20px;
            padding: 22px;
            box-shadow: 0 10px 22px rgba(16, 35, 61, 0.04);
        }
        .form-label {
            color: #24496f;
            font-weight: 700;
        }
        .form-text {
            color: var(--admin-muted);
        }
        .submenu {
            padding-bottom: 4px;
        }
        .navbar-user-btn {
            border: 1px solid #dbe3ef;
            background: #fff;
        }
        .table-light {
            --bs-table-bg: transparent;
        }
        @media (max-width: 992px) {
            .sidebar {
                width: 240px;
            }
            .admin-content {
                padding: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-shell">
        <!-- Sidebar -->
        <div class="sidebar p-3">
            <div class="text-center mb-4 pt-2">
                <div class="brand-mark mx-auto mb-3">
                    <i class="fas fa-spa" style="position: relative; z-index: 1; color: #4a8df7; font-size: 1.45rem;"></i>
                </div>
                <h4 class="fw-bold brand-name mb-1">HƯƠNG HOA XINH</h4>
                <div class="sidebar-title">Admin Panel</div>
                <div class="brand-subtitle"><i class="fas fa-wand-magic-sparkles"></i> Floral commerce</div>
            </div>
            
            <ul class="nav flex-column">
                <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li class="sidebar-group">
                    <a href="#productMenu" class="nav-link {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'true' : 'false' }}" aria-controls="productMenu">
                        <i class="fas fa-box me-2"></i> Quản lý Sản phẩm
                    </a>
                    <div class="collapse submenu {{ request()->routeIs('admin.products.*') || request()->routeIs('admin.categories.*') ? 'show' : '' }}" id="productMenu">
                        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="fas fa-list-ul me-2"></i> Danh sách sản phẩm
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="fas fa-tags me-2"></i> Danh mục sản phẩm
                        </a>
                    </div>
                </li>
                <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="fas fa-shopping-bag me-2"></i> Quản lý Đơn hàng</a></li>
                <li><a href="{{ route('admin.contact-messages.index') }}" class="nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}"><i class="fas fa-inbox me-2"></i> Tin nhắn liên hệ</a></li>
                <li><a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><i class="fas fa-star-half-alt me-2"></i> Đánh giá</a></li>
                <li><a href="{{ route('admin.vouchers.index') }}" class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}"><i class="fas fa-ticket-alt me-2"></i> Mã giảm giá</a></li>
                <li class="account-group">
                    <a href="#accountMenu" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" data-bs-toggle="collapse" role="button" aria-expanded="{{ request()->routeIs('admin.users.*') ? 'true' : 'false' }}" aria-controls="accountMenu">
                        <i class="fas fa-users-cog me-2"></i> Quản lý Tài khoản
                    </a>
                    <div class="collapse submenu {{ request()->routeIs('admin.users.*') ? 'show' : '' }}" id="accountMenu">
                        <a href="{{ route('admin.users.admins') }}" class="nav-link {{ request()->routeIs('admin.users.admins') ? 'active' : '' }}">
                            <i class="fas fa-user-shield me-2"></i> Admin
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') || request()->routeIs('admin.users.show') ? 'active' : '' }}">
                            <i class="fas fa-user me-2"></i> Khách hàng
                        </a>
                    </div>
                </li>
                <li><a href="{{ route('admin.revenue.index') }}" class="nav-link {{ request()->routeIs('admin.revenue.*') ? 'active' : '' }}"><i class="fas fa-chart-bar me-2"></i> Thống kê Doanh thu</a></li>
                <li><a href="{{ route('admin.settings.edit') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><i class="fas fa-cog me-2"></i> Cài đặt Website</a></li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1">
            <!-- Topbar -->
            <nav class="navbar navbar-light navbar-admin px-4 py-3">
                <div class="container-fluid">
                    <div>
                        <div class="text-muted small mb-1">Trang quản trị</div>
                        <h5 class="mb-0 page-title">@yield('title')</h5>
                    </div>
                    
                    <div class="d-flex align-items-center gap-2 gap-lg-3 flex-wrap justify-content-end">
                        <a href="{{ route('home') }}" class="btn btn-outline-primary btn-sm px-3">
                            <i class="fas fa-store me-1"></i> Trang bán hàng
                        </a>
                        <span class="badge bg-success px-3 py-2">Online</span>
                        <div class="dropdown">
                            <button class="btn navbar-user-btn dropdown-toggle px-3" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::guard('admin')->user()->name ?? 'Admin' }}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('admin.profile.edit') }}">Thông tin cá nhân</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#" 
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Content -->
            <div class="admin-content">
                <div class="flash-wrap">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                </div>

                @yield('content')
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>