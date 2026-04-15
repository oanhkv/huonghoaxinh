@extends('frontend.layouts.app')

@section('title', 'Đặt hàng thành công - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <h1 class="fw-bold mb-3">Cảm ơn bạn đã đặt hàng!</h1>
                    <p class="text-muted mb-4">Đơn hàng của bạn đã được ghi nhận. Chúng tôi sẽ liên hệ lại bạn sớm nhất để xác nhận và giao hàng.</p>
                    <div class="mb-4">
                        <a href="{{ route('home') }}" class="btn btn-success me-2">Về trang chủ</a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-success">Tiếp tục mua sắm</a>
                    </div>
                    @if(session('order_summary'))
                        @php $order = session('order_summary'); @endphp
                        <div class="text-start mb-4">
                            <h5 class="fw-bold">Thông tin đơn hàng</h5>
                            <p class="mb-1"><strong>Khách hàng:</strong> {{ $order['name'] }}</p>
                            <p class="mb-1"><strong>Email:</strong> {{ $order['email'] }}</p>
                            <p class="mb-1"><strong>Điện thoại:</strong> {{ $order['phone'] }}</p>
                            <p class="mb-1"><strong>Địa chỉ:</strong> {{ $order['address'] }}, {{ $order['ward'] }}, {{ $order['district'] }}, {{ $order['province'] }}</p>
                            <p class="mb-1"><strong>Ghi chú:</strong> {{ $order['note'] ?: 'Không có' }}</p>
                            <p class="mb-1"><strong>Hình thức thanh toán:</strong> {{ $order['payment_method'] === 'online' ? 'Thanh toán online' : 'Thanh toán khi nhận hàng' }}</p>
                        </div>
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body">
                                <h5 class="fw-bold mb-3">Sản phẩm</h5>
                                @foreach($order['cartItems'] as $item)
                                    <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(!empty($item['image']))
                                                <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" style="width:72px; height:72px; object-fit:cover; border-radius:12px;" />
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $item['name'] }}</div>
                                                <div class="small text-muted">Số lượng: {{ $item['quantity'] }}</div>
                                                @if(!empty($item['size']))
                                                    <div class="small text-muted">Kích cỡ: {{ $item['size'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-danger">{{ number_format($item['price'] * $item['quantity']) }} đ</div>
                                            <div class="small text-muted">{{ number_format($item['price']) }} đ / 1</div>
                                        </div>
                                    </div>
                                @endforeach
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Tổng đơn hàng</span>
                                    <span class="fs-5 fw-bold text-success">{{ number_format($order['cartTotal']) }} đ</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(session('payment_method'))
                        <div class="alert alert-info">
                            <strong>Hình thức thanh toán:</strong>
                            {{ session('payment_method') === 'online' ? 'Thanh toán online' : 'Thanh toán khi nhận hàng' }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
