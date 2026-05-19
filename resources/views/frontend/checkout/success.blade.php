@extends('frontend.layouts.app')

@section('title', 'Đặt hàng thành công - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Hoàn tất</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm text-center p-5">
                <div class="mb-4">
                    <i class="fas fa-check-circle fa-4x text-success"></i>
                </div>
                <h2 class="mb-3">Đặt hàng thành công!</h2>
                <p class="mb-4 text-muted">Cảm ơn bạn đã mua sắm tại Hương Hoa Xinh. Đơn hàng của bạn đang được xử lý.</p>

                @if(isset($order))
                    <div class="bg-light p-4 rounded mb-4 text-start">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Mã đơn hàng</div>
                                <div class="fw-bold">{{ $order->order_code }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Trạng thái</div>
                                <div class="fw-bold">{{ ucfirst($order->status) }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Tổng thanh toán</div>
                                <div class="fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</div>
                            </div>
                            @if($order->delivery_date)
                                <div class="col-md-6">
                                    <div class="text-muted small">Thời gian giao</div>
                                    <div class="fw-bold">
                                        {{ $order->delivery_date->format('d/m/Y') }}
                                        @if($order->delivery_time_slot) · {{ $order->delivery_time_slot }} @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        <hr>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small mb-1"><i class="fas fa-user-pen me-1 text-success"></i>Người gửi</div>
                                <div class="fw-semibold">{{ $order->sender_name ?: '—' }}</div>
                                @if($order->sender_phone)<div class="small text-muted"><i class="fas fa-phone me-1"></i>{{ $order->sender_phone }}</div>@endif
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small mb-1"><i class="fas fa-gift me-1 text-success"></i>Người nhận</div>
                                <div class="fw-semibold">{{ $order->recipient_name ?: '—' }}</div>
                                @if($order->phone)<div class="small text-muted"><i class="fas fa-phone me-1"></i>{{ $order->phone }}</div>@endif
                                <div class="small text-muted mt-1"><i class="fas fa-location-dot me-1"></i>{{ $order->shipping_address }}</div>
                            </div>

                            @if($order->recipient_message)
                                <div class="col-12">
                                    <div class="text-muted small mb-1"><i class="far fa-envelope me-1 text-success"></i>Lời nhắn gửi người nhận</div>
                                    <div class="p-3 rounded bg-white border" style="white-space: pre-line;">{{ $order->recipient_message }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <a href="{{ route('shop') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
