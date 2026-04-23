@extends('frontend.layouts.app')

@section('title', 'Lịch sử đơn hàng - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Lịch sử đơn hàng</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-history me-2 text-success"></i>Lịch sử đơn hàng</h2>

    <style>
        .order-history-tabs .nav-link {
            background-color: #f2f6f4;
            color: #0f5132;
            border: 1px solid rgba(15, 81, 50, 0.12);
        }
        .order-history-tabs .nav-link.active {
            background-color: #d7f0df;
            color: #14532d;
            border-color: #b8e7c6;
        }
    </style>

    <div class="mb-4 order-history-tabs">
        <ul class="nav nav-pills gap-2 flex-wrap">
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('orders.history', ['status' => 'all']) }}">Tất cả đơn hàng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'pending' ? 'active' : '' }}" href="{{ route('orders.history', ['status' => 'pending']) }}">Đang chờ xác nhận</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'processing' ? 'active' : '' }}" href="{{ route('orders.history', ['status' => 'processing']) }}">Chờ giao hàng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $filter === 'delivered' ? 'active' : '' }}" href="{{ route('orders.history', ['status' => 'delivered']) }}">Đã giao</a>
            </li>
        </ul>
    </div>

    @if($orders->isEmpty())
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <h5 class="fw-bold">Bạn chưa có đơn hàng nào.</h5>
                <p class="text-muted mb-4">Hãy thêm sản phẩm vào giỏ và đặt hàng để xem lịch sử đơn hàng của bạn ở đây.</p>
                <a href="{{ route('shop') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-12">
                @foreach($orders as $order)
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-light border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-1">Mã đơn hàng: <span class="text-success">{{ $order->order_code }}</span></h5>
                                <p class="mb-1 text-muted">Ngày đặt: {{ $order->created_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</p>
                                @php
                                    if ($order->status === 'pending') {
                                        $statusClass = 'warning text-dark';
                                        $statusLabel = 'Đang chờ thanh toán';
                                    } elseif ($order->status === 'paid') {
                                        $statusClass = 'primary';
                                        $statusLabel = 'Chờ giao hàng';
                                    } elseif ($order->status === 'cod') {
                                        $statusClass = 'primary';
                                        $statusLabel = 'Chờ giao hàng';
                                    } elseif ($order->status === 'delivered') {
                                        $statusClass = 'success';
                                        $statusLabel = 'Đã giao';
                                    } elseif ($order->status === 'cancelled') {
                                        $statusClass = 'secondary';
                                        $statusLabel = 'Đã hủy';
                                    } else {
                                        $statusClass = 'secondary';
                                        $statusLabel = ucfirst($order->status);
                                    }
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                                @if($order->status === 'pending')
                                    @php
                                        $expiresAtMs = $order->created_at->copy()->addMinutes(10)->valueOf();
                                    @endphp
                                    <div class="small mt-2 text-danger">
                                        Thời gian thanh toán còn lại:
                                        <strong class="pending-order-countdown" data-expires-at="{{ $expiresAtMs }}">10:00</strong>
                                    </div>
                                    <div class="small text-muted">Quá 10 phút chưa thanh toán, đơn hàng sẽ tự động hủy.</div>
                                @endif
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</div>
                                @php
                                    $cancelAllowed = $order->status === 'pending' && $order->created_at->diffInMinutes(
                                        \Carbon\Carbon::now()
                                    ) <= 10;
                                @endphp
                                @if($cancelAllowed)
                                    <div class="d-flex gap-2 mt-2">
                                        <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-success">Tiếp tục thanh toán</a>
                                        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hủy đơn</button>
                                        </form>
                                    </div>
                                @elseif($order->status === 'pending')
                                    <div class="mt-2 text-warning small">Đã quá 10 phút, không thể hủy đơn.</div>
                                    <a href="{{ route('checkout.index') }}" class="btn btn-sm btn-success mt-2">Tiếp tục thanh toán</a>
                                @elseif(in_array($order->status, ['paid', 'cod']))
                                    <form action="{{ route('orders.confirmReceived', $order) }}" method="POST" class="mt-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Xác nhận đã nhận</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    @foreach($order->orderItems as $item)
                                        <div class="border-bottom py-3">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div>
                                                    <div class="fw-semibold">{{ $item->product->name }}</div>
                                                    <div class="small text-muted">{{ $item->variant ?: 'Mặc định' }}</div>
                                                </div>
                                                <div class="text-success fw-bold">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} ₫</div>
                                            </div>
                                            <div class="d-flex justify-content-between text-muted small">
                                                <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }} ₫</span>
                                                <span>Giá mỗi: {{ number_format($item->price, 0, ',', '.') }} ₫</span>
                                            </div>
                                            @if($order->status === 'delivered')
                                                @php
                                                    $isReviewed = in_array((int) $item->product_id, $reviewedProductIds ?? [], true);
                                                @endphp
                                                <div class="mt-2">
                                                    @if($isReviewed)
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                            <i class="fas fa-check-circle me-1"></i>Đã đánh giá
                                                        </span>
                                                    @else
                                                        <a href="{{ route('reviews.create', $item->product->slug) }}"
                                                           class="btn btn-sm btn-outline-success">
                                                            <i class="fas fa-star me-1"></i>Đánh giá sản phẩm
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 bg-light rounded h-100">
                                        <div class="mb-3">
                                            <div class="text-muted small">Địa chỉ</div>
                                            <div>{{ $order->shipping_address }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="text-muted small">Số điện thoại</div>
                                            <div>{{ $order->phone }}</div>
                                        </div>
                                        <div class="mb-3">
                                            <div class="text-muted small">Ghi chú</div>
                                            <div>{{ $order->note ?: 'Không có' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

<script>
    (function () {
        const countdownEls = document.querySelectorAll('.pending-order-countdown');
        if (!countdownEls.length) return;

        function formatRemaining(ms) {
            const totalSeconds = Math.max(0, Math.floor(ms / 1000));
            const mins = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
            const secs = String(totalSeconds % 60).padStart(2, '0');
            return `${mins}:${secs}`;
        }

        function tickAll() {
            let hasExpired = false;

            countdownEls.forEach(function (el) {
                const expiresAt = parseInt(el.getAttribute('data-expires-at'), 10);
                if (!expiresAt) return;

                const remaining = expiresAt - Date.now();
                if (remaining <= 0) {
                    el.textContent = '00:00';
                    hasExpired = true;
                } else {
                    el.textContent = formatRemaining(remaining);
                }
            });

            if (hasExpired) {
                setTimeout(function () {
                    window.location.reload();
                }, 700);
                return;
            }

            setTimeout(tickAll, 1000);
        }

        tickAll();
    })();
</script>
@endsection
