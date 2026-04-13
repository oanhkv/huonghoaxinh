@extends('admin.layouts.admin')

@section('title', 'Thống kê doanh thu')

@section('content')
<style>
    .revenue-chart-wrap {
        position: relative;
        width: 100%;
        height: 340px;
    }

    .revenue-doughnut-wrap {
        position: relative;
        width: 100%;
        height: 300px;
    }

    @media (max-width: 992px) {
        .revenue-chart-wrap,
        .revenue-doughnut-wrap {
            height: 280px;
        }
    }
</style>

<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h3 class="fw-bold mb-1">Thống kê doanh thu</h3>
            <p class="text-muted mb-0">Theo dõi doanh thu, trạng thái đơn hàng và xuất báo cáo Excel theo thời gian.</p>
        </div>
        <a
            href="{{ route('admin.revenue.export', ['date_from' => $dateFrom, 'date_to' => $dateTo, 'group_by' => $groupBy]) }}"
            class="btn btn-success"
        >
            <i class="fas fa-file-excel me-1"></i> Xuất báo cáo Excel
        </a>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.revenue.index') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Từ ngày</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $dateFrom }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Đến ngày</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $dateTo }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Gom theo</label>
                        <select name="group_by" class="form-select">
                            <option value="day" {{ $groupBy === 'day' ? 'selected' : '' }}>Ngày</option>
                            <option value="month" {{ $groupBy === 'month' ? 'selected' : '' }}>Tháng</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-1"></i> Lọc dữ liệu
                        </button>
                    </div>
                </div>
            </form>

            @if(! empty($groupByNotice))
                <div class="alert alert-warning mt-3 mb-0">
                    {{ $groupByNotice }}
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">Tổng đơn hàng</div>
                            <h3 class="fw-bold mb-0">{{ number_format($totalOrders) }}</h3>
                        </div>
                        <i class="fas fa-receipt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">Doanh thu hoàn thành</div>
                            <h3 class="fw-bold mb-0">{{ number_format($totalRevenue) }}đ</h3>
                        </div>
                        <i class="fas fa-money-bill-trend-up fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">Đơn hoàn thành</div>
                            <h3 class="fw-bold mb-0">{{ number_format($completedOrdersCount) }}</h3>
                        </div>
                        <i class="fas fa-circle-check fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card card-stat bg-danger text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="small opacity-75">Đơn hủy</div>
                            <h3 class="fw-bold mb-0">{{ number_format($cancelledOrdersCount) }}</h3>
                        </div>
                        <i class="fas fa-ban fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold">Biểu đồ doanh thu {{ $groupBy === 'day' ? 'theo ngày' : 'theo tháng' }}</h6>
                    <span class="badge bg-light text-dark border">Giá trị: VNĐ</span>
                </div>
                <div class="card-body">
                    <div class="revenue-chart-wrap">
                        <canvas id="revenueLineChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Tỉ trọng trạng thái đơn</h6>
                </div>
                <div class="card-body">
                    <div class="revenue-doughnut-wrap">
                        <canvas id="orderStatusChart"></canvas>
                    </div>
                    <hr>
                    <div class="small text-muted">
                        Giá trị đơn trung bình (đơn hoàn thành):
                        <span class="fw-bold text-dark">{{ number_format($averageOrderValue) }}đ</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold">Đơn hàng trong khoảng thời gian đã chọn</h6>
            <span class="badge bg-primary">{{ $recentOrders->count() }} đơn gần nhất</span>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover admin-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td class="fw-semibold">#{{ $order->order_code ?? ('HH' . $order->id) }}</td>
                            <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @php
                                    $statusColor = match($order->status) {
                                        'completed' => 'success',
                                        'shipping' => 'primary',
                                        'confirmed' => 'info',
                                        'cancelled' => 'danger',
                                        default => 'warning',
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                            </td>
                            <td class="text-end fw-bold text-danger">{{ number_format($order->total_amount) }}đ</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Không có đơn hàng trong khoảng thời gian này.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function formatCompactVND(value) {
        const number = Number(value || 0);

        if (Math.abs(number) >= 1000000000) {
            return (number / 1000000000).toFixed(1).replace('.0', '') + ' ty';
        }

        if (Math.abs(number) >= 1000000) {
            return (number / 1000000).toFixed(1).replace('.0', '') + ' tr';
        }

        if (Math.abs(number) >= 1000) {
            return (number / 1000).toFixed(1).replace('.0', '') + 'k';
        }

        return new Intl.NumberFormat('vi-VN').format(number) + 'd';
    }

    new Chart(document.getElementById('revenueLineChart'), {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: @json($chartValues),
                borderColor: '#3577e8',
                backgroundColor: 'rgba(53, 119, 232, 0.14)',
                borderWidth: 3,
                tension: 0.35,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            interaction: {
                mode: 'index',
                intersect: false
            },
            scales: {
                x: {
                    ticks: {
                        autoSkip: true,
                        maxTicksLimit: 12
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        maxTicksLimit: 6,
                        callback: function(value) {
                            return formatCompactVND(value);
                        }
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.raw || 0) + 'd';
                        }
                    }
                }
            }
        }
    });

    new Chart(document.getElementById('orderStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json($statusChartLabels),
            datasets: [{
                data: @json($statusChartValues),
                backgroundColor: ['#f59e0b', '#0ea5e9', '#4a8df7', '#16a34a', '#dc2626'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                }
            }
        }
    });
</script>
@endsection
