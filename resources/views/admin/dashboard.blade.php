@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="dashboard-page">
    <!-- ============ WELCOME BAR ============ -->
    @php $admin = Auth::guard('admin')->user(); @endphp
    <div class="welcome-bar mb-4">
        <div class="row align-items-center g-3">
            <div class="col-lg-7">
                <span class="welcome-eyebrow"><i class="fas fa-hand-sparkles me-1"></i> Chào mừng trở lại</span>
                <h2 class="welcome-title">Xin chào, {{ $admin->name ?? 'Admin' }} 👋</h2>
                <p class="welcome-sub">
                    Đây là tổng quan hoạt động của Hương Hoa Xinh — cập nhật lúc <strong>{{ now()->format('H:i, d/m/Y') }}</strong>.
                </p>
            </div>
            <div class="col-lg-5">
                <div class="quick-actions">
                    <a href="{{ route('admin.products.create') }}" class="quick-action">
                        <i class="fas fa-plus"></i> Thêm sản phẩm
                    </a>
                    <a href="{{ route('admin.vouchers.create') }}" class="quick-action">
                        <i class="fas fa-tag"></i> Tạo mã giảm giá
                    </a>
                    <a href="{{ route('admin.orders.index') }}?status=pending" class="quick-action">
                        <i class="fas fa-bell"></i> Đơn chờ
                        @if($pendingOrders > 0)<span class="qa-badge">{{ $pendingOrders }}</span>@endif
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ STAT CARDS ============ -->
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-pink">
                <div class="stat-card-head">
                    <div class="stat-icon"><i class="fas fa-bag-shopping"></i></div>
                    <span class="stat-trend {{ $orderTrend['up'] ? 'up' : 'down' }}">
                        <i class="fas fa-arrow-{{ $orderTrend['up'] ? 'up' : 'down' }}"></i> {{ $orderTrend['value'] }}%
                    </span>
                </div>
                <div class="stat-label">Đơn hàng hôm nay</div>
                <div class="stat-value">{{ number_format($totalOrdersToday) }}</div>
                <div class="stat-foot"><i class="fas fa-circle-info me-1"></i> So với hôm qua</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-green">
                <div class="stat-card-head">
                    <div class="stat-icon"><i class="fas fa-money-bill-trend-up"></i></div>
                    <span class="stat-trend {{ $revenueTrend['up'] ? 'up' : 'down' }}">
                        <i class="fas fa-arrow-{{ $revenueTrend['up'] ? 'up' : 'down' }}"></i> {{ $revenueTrend['value'] }}%
                    </span>
                </div>
                <div class="stat-label">Doanh thu hôm nay</div>
                <div class="stat-value">{{ number_format($totalRevenueToday / 1000, 0, ',', '.') }}<small>k</small></div>
                <div class="stat-foot"><i class="fas fa-coins me-1"></i> Tháng này: {{ number_format($monthRevenue / 1000000, 1) }}tr</div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-amber">
                <div class="stat-card-head">
                    <div class="stat-icon"><i class="fas fa-box"></i></div>
                    <span class="stat-mini">{{ $totalProducts }} đang bán</span>
                </div>
                <div class="stat-label">Sản phẩm đã bán hôm nay</div>
                <div class="stat-value">{{ number_format($totalProductsSoldToday) }}</div>
                <div class="stat-foot">
                    @if($lowStockProducts > 0)
                        <i class="fas fa-triangle-exclamation me-1 text-danger"></i> <strong class="text-danger">{{ $lowStockProducts }} sản phẩm</strong> sắp hết
                    @else
                        <i class="fas fa-check-circle me-1 text-success"></i> Tồn kho ổn định
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3">
            <div class="stat-card stat-blue">
                <div class="stat-card-head">
                    <div class="stat-icon"><i class="fas fa-user-plus"></i></div>
                    <span class="stat-mini">Mới</span>
                </div>
                <div class="stat-label">Khách hàng mới hôm nay</div>
                <div class="stat-value">{{ number_format($newCustomersToday) }}</div>
                <div class="stat-foot">
                    @if($unreadMessages > 0)
                        <i class="fas fa-envelope me-1 text-info"></i> <strong>{{ $unreadMessages }}</strong> tin nhắn mới
                    @else
                        <i class="fas fa-circle-check me-1 text-success"></i> Không có tin nhắn mới
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ============ CHART + TOP PRODUCTS ============ -->
    <div class="row g-4 mb-4">
        <div class="col-xl-8">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title"><i class="fas fa-chart-line"></i> Doanh thu 7 ngày gần nhất</h3>
                    <span class="badge bg-success bg-opacity-10 text-success">7 ngày</span>
                </div>
                <div class="p-3">
                    <canvas id="revenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h3 class="admin-card-title"><i class="fas fa-fire"></i> Sản phẩm bán chạy</h3>
                    <a href="{{ route('admin.products.index') }}" class="small text-decoration-none" style="color: var(--hhx-pink); font-weight: 600;">Xem tất cả →</a>
                </div>
                <div class="top-products-list">
                    @forelse($topProducts as $i => $tp)
                        @if($tp->product)
                            <a href="{{ route('admin.products.edit', $tp->product->id) }}" class="top-product-item">
                                <span class="top-product-rank">{{ $i + 1 }}</span>
                                <div class="top-product-thumb">
                                    @if($tp->product->image)
                                        <img src="{{ $tp->product->image_url }}" alt="">
                                    @else
                                        <i class="fas fa-spa"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1 min-w-0">
                                    <div class="top-product-name">{{ Str::limit($tp->product->name, 36) }}</div>
                                    <div class="top-product-meta">
                                        <span class="text-success fw-bold">{{ number_format($tp->product->price, 0, ',', '.') }}₫</span>
                                        <span class="text-muted">·</span>
                                        <span>Còn {{ $tp->product->stock }}</span>
                                    </div>
                                </div>
                                <span class="top-product-sold">{{ $tp->sold }} đã bán</span>
                            </a>
                        @endif
                    @empty
                        <div class="text-center py-4 text-muted small">Chưa có dữ liệu bán hàng 30 ngày.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- ============ RECENT ORDERS ============ -->
    <div class="row g-4">
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title"><i class="fas fa-receipt"></i> Đơn hàng gần đây</h3>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i> Xem tất cả
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            @php
                                $statusMap = [
                                    'pending' => ['Chờ xử lý', 'warning'],
                                    'paid' => ['Đã thanh toán', 'info'],
                                    'cod' => ['COD', 'primary'],
                                    'delivered' => ['Đã giao', 'success'],
                                    'completed' => ['Hoàn tất', 'success'],
                                    'cancelled' => ['Đã hủy', 'danger'],
                                    'confirmed' => ['Đã xác nhận', 'info'],
                                    'shipping' => ['Đang giao', 'primary'],
                                ];
                                [$label, $color] = $statusMap[$order->status] ?? [ucfirst($order->status), 'secondary'];
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="fw-bold text-decoration-none" style="color: var(--hhx-pink);">
                                        #{{ $order->order_code ?? 'HH'.$order->id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar-sm">{{ strtoupper(mb_substr($order->user->name ?? 'K', 0, 1)) }}</div>
                                        <div>
                                            <div class="fw-semibold small">{{ $order->user->name ?? 'Khách vãng lai' }}</div>
                                            <div class="text-muted" style="font-size: 0.75rem;">{{ $order->user->email ?? '—' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small fw-semibold">{{ $order->created_at->format('d/m/Y') }}</div>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="fw-bold" style="color: var(--hhx-green);">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                <td><span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}" style="border: 1px solid currentColor;">{{ $label }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center py-5 text-muted">Chưa có đơn hàng nào</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Welcome bar */
    .welcome-bar {
        background: linear-gradient(135deg, rgba(214, 51, 132, 0.08) 0%, rgba(25, 135, 84, 0.06) 100%);
        border: 1px solid var(--hhx-border);
        border-radius: 20px;
        padding: 24px 26px;
        position: relative;
        overflow: hidden;
    }
    .welcome-bar::before {
        content: '🌸';
        position: absolute;
        font-size: 200px;
        opacity: 0.05;
        right: -30px; top: -50px;
        transform: rotate(-15deg);
        pointer-events: none;
    }
    .welcome-eyebrow {
        display: inline-flex; align-items: center;
        background: rgba(214, 51, 132, 0.12);
        color: var(--hhx-pink);
        font-size: 0.74rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 999px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .welcome-title { font-weight: 800; font-size: 1.7rem; margin: 0 0 4px; letter-spacing: -0.02em; }
    .welcome-sub { color: var(--hhx-muted); margin: 0; font-size: 0.92rem; }

    .quick-actions { display: flex; flex-wrap: wrap; gap: 8px; justify-content: flex-end; }
    .quick-action {
        position: relative;
        display: inline-flex; align-items: center; gap: 8px;
        padding: 10px 16px;
        background: #fff;
        color: var(--hhx-text);
        border: 1px solid var(--hhx-border);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.86rem;
        transition: all 0.2s ease;
    }
    .quick-action:hover {
        background: var(--hhx-pink); color: #fff; border-color: var(--hhx-pink);
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(214, 51, 132, 0.3);
    }
    .quick-action i { color: var(--hhx-pink); transition: color 0.2s ease; }
    .quick-action:hover i { color: #fff; }
    .qa-badge {
        background: var(--hhx-pink);
        color: #fff;
        font-size: 0.65rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 999px;
        margin-left: 4px;
    }
    .quick-action:hover .qa-badge { background: #fff; color: var(--hhx-pink); }

    /* Stat cards */
    .stat-card {
        position: relative;
        background: #fff;
        border-radius: 18px;
        padding: 22px;
        border: 1px solid var(--hhx-border);
        box-shadow: 0 6px 22px rgba(15, 23, 42, 0.04);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--c1), var(--c2));
    }
    .stat-card:hover { transform: translateY(-4px); box-shadow: 0 16px 36px rgba(15, 23, 42, 0.1); }
    .stat-pink { --c1: #d63384; --c2: #f06595; }
    .stat-green { --c1: #198754; --c2: #20a464; }
    .stat-amber { --c1: #ffc107; --c2: #ff9800; }
    .stat-blue { --c1: #0ea5e9; --c2: #38bdf8; }

    .stat-card-head {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 14px;
    }
    .stat-icon {
        width: 48px; height: 48px;
        border-radius: 14px;
        background: linear-gradient(135deg, var(--c1), var(--c2));
        color: #fff;
        display: grid; place-items: center;
        font-size: 1.15rem;
        box-shadow: 0 8px 18px color-mix(in srgb, var(--c1) 28%, transparent);
    }
    .stat-trend {
        font-size: 0.72rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
    }
    .stat-trend.up { background: rgba(25, 135, 84, 0.1); color: #198754; }
    .stat-trend.down { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
    .stat-mini {
        font-size: 0.7rem;
        color: var(--hhx-muted);
        background: var(--hhx-bg);
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 600;
    }

    .stat-label {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--hhx-muted);
        font-weight: 700;
        margin-bottom: 6px;
    }
    .stat-value {
        font-size: 1.95rem;
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 8px;
        color: var(--hhx-text);
        letter-spacing: -0.02em;
    }
    .stat-value small { font-size: 1.1rem; color: var(--hhx-muted); font-weight: 700; }
    .stat-foot {
        font-size: 0.78rem;
        color: var(--hhx-muted);
        padding-top: 10px;
        border-top: 1px dashed var(--hhx-border);
    }

    /* Top products list */
    .top-products-list { padding: 8px; }
    .top-product-item {
        display: flex; align-items: center; gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        text-decoration: none;
        color: inherit;
        transition: background 0.2s ease;
    }
    .top-product-item:hover { background: var(--hhx-bg); }
    .top-product-rank {
        width: 28px; height: 28px;
        border-radius: 8px;
        background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
        color: #fff;
        display: grid; place-items: center;
        font-weight: 800;
        font-size: 0.78rem;
        flex-shrink: 0;
    }
    .top-product-item:nth-child(1) .top-product-rank { background: linear-gradient(135deg, #ffd700, #ffb300); }
    .top-product-item:nth-child(2) .top-product-rank { background: linear-gradient(135deg, #cbd5e1, #94a3b8); }
    .top-product-item:nth-child(3) .top-product-rank { background: linear-gradient(135deg, #cd7f32, #a05a2c); }
    .top-product-thumb {
        width: 44px; height: 44px;
        border-radius: 10px;
        overflow: hidden;
        flex-shrink: 0;
        background: var(--hhx-bg);
        display: grid; place-items: center;
        color: var(--hhx-muted);
    }
    .top-product-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .top-product-name { font-size: 0.86rem; font-weight: 700; color: var(--hhx-text); }
    .top-product-meta { font-size: 0.74rem; color: var(--hhx-muted); display: flex; gap: 6px; }
    .top-product-sold {
        font-size: 0.72rem;
        font-weight: 700;
        color: var(--hhx-pink);
        background: rgba(214, 51, 132, 0.08);
        padding: 4px 10px;
        border-radius: 999px;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .user-avatar-sm {
        width: 32px; height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--hhx-pink), var(--hhx-pink-2));
        color: #fff;
        display: grid; place-items: center;
        font-weight: 800;
        font-size: 0.78rem;
        flex-shrink: 0;
    }

    @media (max-width: 991.98px) {
        .quick-actions { justify-content: flex-start; }
        .welcome-title { font-size: 1.35rem; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(214, 51, 132, 0.35)');
        gradient.addColorStop(1, 'rgba(214, 51, 132, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Doanh thu (triệu)',
                    data: @json($revenueLast7Days),
                    borderColor: '#d63384',
                    backgroundColor: gradient,
                    tension: 0.4,
                    borderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#d63384',
                    pointBorderWidth: 3,
                    fill: true,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: function (ctx) { return ctx.parsed.y + ' triệu đồng'; }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            callback: function (v) { return v + 'tr'; },
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { size: 11 } }
                    }
                }
            }
        });
    })();
</script>
@endsection
