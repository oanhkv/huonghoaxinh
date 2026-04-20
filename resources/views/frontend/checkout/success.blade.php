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
                    <div class="bg-light p-4 rounded mb-4">
                        <p class="mb-2"><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</p>
                        <p class="mb-2"><strong>Trạng thái:</strong> {{ ucfirst($order->status) }}</p>
                        <p class="mb-0"><strong>Tổng thanh toán:</strong> {{ number_format($order->total_amount, 0, ',', '.') }} ₫</p>
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
