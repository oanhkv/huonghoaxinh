@extends('admin.layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Bảng Điều Khiển Hệ Thống</h3>
        <span class="badge bg-success px-3 py-2">Cập nhật lúc: {{ now()->format('H:i d/m/Y') }}</span>
    </div>

    <!-- Thống kê Cards -->
    <div class="row g-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">ĐƠN HÀNG MỚI HÔM NAY</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalOrdersToday) }}</h2>
                        </div>
                        <i class="fas fa-shopping-bag fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">DOANH THU HÔM NAY</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalRevenueToday / 1000) }}k</h2>
                        </div>
                        <i class="fas fa-money-bill-wave fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">SẢN PHẨM ĐÃ BÁN</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($totalProductsSoldToday) }}</h2>
                        </div>
                        <i class="fas fa-box fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="mb-1 opacity-75">KHÁCH HÀNG MỚI</h6>
                            <h2 class="mb-0 fw-bold">{{ number_format($newCustomersToday) }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ Doanh thu -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">📈 Doanh thu 7 ngày gần nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart" height="110"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Đơn hàng gần đây -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between">
                    <h5 class="mb-0">📋 Đơn hàng gần đây</h5>
                    <a href="#" class="text-success">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                            <tr>
                                <td><strong>#{{ $order->order_code ?? 'HH' . $order->id }}</strong></td>
                                <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold text-danger">{{ number_format($order->total_amount) }}đ</td>
                                <td>
                                    <span class="badge bg-{{ $order->status == 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4">Chưa có đơn hàng nào</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Doanh thu (triệu đồng)',
                data: @json($revenueLast7Days),
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.15)',
                tension: 0.4,
                borderWidth: 4,
                pointRadius: 5,
                pointBackgroundColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { callback: function(v) { return v + 'tr'; }}}
            }
        }
    });
</script>
@endsection