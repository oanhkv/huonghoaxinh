@extends('frontend.layouts.app')

@section('title', 'Thanh toán online - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="fw-bold mb-4">Thanh toán online</h1>

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Thông tin đơn hàng</h5>
                                    <p class="mb-2"><strong>Khách hàng:</strong> {{ $checkoutData['name'] }}</p>
                                    <p class="mb-2"><strong>Email:</strong> {{ $checkoutData['email'] }}</p>
                                    <p class="mb-2"><strong>Điện thoại:</strong> {{ $checkoutData['phone'] }}</p>
                                    <p class="mb-2"><strong>Địa chỉ:</strong> {{ $checkoutData['address'] }}, {{ $checkoutData['ward'] }}, {{ $checkoutData['district'] }}, {{ $checkoutData['province'] }}</p>
                                    <p class="mb-2"><strong>Ghi chú:</strong> {{ $checkoutData['note'] ?: 'Không có' }}</p>
                                    <hr>
                                    <h6 class="fw-bold mb-3">Sản phẩm</h6>
                                    @foreach($checkoutData['cartItems'] as $item)
                                        <div class="d-flex align-items-center gap-3 mb-3">
                                            <div style="width: 64px; height: 64px; overflow:hidden; border-radius:12px; background:#f8f9fa;">
                                                @if(!empty($item['image']))
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid h-100 w-100" style="object-fit:cover;">
                                                @endif
                                            </div>
                                            <div>
                                                <strong>{{ $item['name'] }}</strong>
                                                <div class="small text-muted">Số lượng: {{ $item['quantity'] }}</div>
                                                @if(!empty($item['size']))
                                                    <div class="small text-muted">Kích cỡ: {{ $item['size'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h5 class="fw-bold mb-3">Nhập thông tin thẻ</h5>

                                    <form method="POST" action="{{ route('checkout.online.process') }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">Tên chủ thẻ</label>
                                            <input type="text" name="card_holder" class="form-control @error('card_holder') is-invalid @enderror" value="{{ old('card_holder') }}" placeholder="Nguyen Van A" required>
                                            @error('card_holder')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Số thẻ</label>
                                            <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror" value="{{ old('card_number') }}" placeholder="1234 5678 9012 3456" required>
                                            @error('card_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="row g-3 mb-3">
                                            <div class="col-6">
                                                <label class="form-label">Hạn dùng (MM/YY)</label>
                                                <input type="text" name="expiry" class="form-control @error('expiry') is-invalid @enderror" value="{{ old('expiry') }}" placeholder="MM/YY" required>
                                                @error('expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label">CVC</label>
                                                <input type="text" name="cvc" class="form-control @error('cvc') is-invalid @enderror" value="{{ old('cvc') }}" placeholder="123" required>
                                                @error('cvc')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>

                                        <div class="mb-3 d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="small text-muted">Tổng thanh toán</span>
                                                <div class="fw-bold text-success fs-5">{{ number_format($checkoutData['cartTotal']) }} đ</div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-danger w-100 btn-lg">Thanh toán Online</button>
                                    </form>
                                </div>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary w-100">← Quay lại trang thanh toán</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection