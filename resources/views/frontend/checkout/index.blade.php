@extends('frontend.layouts.app')

@section('title', 'Thanh toán - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Giỏ hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-credit-card me-2 text-success"></i>Thanh toán đơn hàng</h2>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0">Thông tin giao hàng</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf

                        @if(isset($pendingOrder) && $pendingOrder->status === 'pending')
                            <div class="alert alert-info">
                                Bạn đang tiếp tục đơn hàng thanh toán qua SMS. Nhấn "Tiếp tục" để gửi lại mã xác thực.
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="shippingAddress" class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="shipping_address" id="shippingAddress" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required>{{ $shippingAddress ?? '' }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ $phone ?? '' }}" class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paymentCod" value="cod" {{ ($paymentMethod ?? 'cod') === 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label" for="paymentCod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paymentOnline" value="online" {{ ($paymentMethod ?? '') === 'online' ? 'checked' : '' }}>
                                <label class="form-check-label" for="paymentOnline">Thanh toán trực tuyến bằng SMS xác thực</label>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú đơn hàng</label>
                            <textarea name="note" id="note" class="form-control" rows="3">{{ $note ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Tiếp tục</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0">Tóm tắt đơn hàng</h6>
                </div>
                <div class="card-body">
                    @php $groupedItems = $cartItems->groupBy('product_id'); @endphp
                    @foreach($groupedItems as $items)
                        @php $firstItem = $items->first(); @endphp
                        <div class="mb-3">
                            <div class="fw-bold">{{ $firstItem->product->name }}</div>
                            @foreach($items as $item)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <div class="small text-muted">{{ $item->variant ?: 'Mặc định' }}</div>
                                        <div class="small">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }} ₫</div>
                                    </div>
                                    <div class="fw-bold text-success">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} ₫</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <span class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển</span>
                        <span class="fw-bold text-success">{{ $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . ' ₫' }}</span>
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold pt-2 border-top">
                        <span>Tổng cộng</span>
                        <span class="text-success">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm bg-light p-3">
                <p class="mb-2"><strong>Ghi chú:</strong></p>
                <p class="small text-muted mb-1"><i class="fas fa-info-circle me-2"></i>Với thanh toán trực tuyến, chúng tôi sẽ gửi mã SMS xác thực đến số điện thoại của bạn.</p>
                <p class="small text-muted mb-0"><i class="fas fa-shield-alt me-2"></i>Đơn hàng được xử lý ngay khi xác thực thành công.</p>
            </div>
        </div>
    </div>
</div>
@endsection
