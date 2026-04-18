@extends('frontend.layouts.app')

@section('title', 'Xác thực SMS - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('checkout.index') }}" class="text-decoration-none">Thanh toán</a></li>
            <li class="breadcrumb-item active" aria-current="page">Xác thực SMS</li>
        </ol>
    </nav>

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-light py-3">
                    <h5 class="mb-0"><i class="fas fa-sms me-2 text-success"></i>Xác nhận mã SMS</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('payment_otp_code'))
                        <div class="alert alert-info">
                            Mã xác thực hiện tại của bạn là: <strong>{{ session('payment_otp_code') }}</strong>
                        </div>
                    @endif

                    <p class="mb-4">Chúng tôi đã gửi mã xác thực gồm 6 chữ số đến số điện thoại của bạn. Vui lòng nhập mã để hoàn tất thanh toán trực tuyến.</p>

                    <form method="POST" action="{{ route('checkout.verify.submit') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label">Mã xác thực</label>
                            <input type="text" name="otp" id="otp" class="form-control @error('otp') is-invalid @enderror" required maxlength="6" value="{{ old('otp') }}">
                            @error('otp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100">Xác nhận và hoàn tất đơn hàng</button>
                    </form>

                    <div class="mt-3">
                        <a href="{{ url('/checkout') }}" class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-arrow-left me-2"></i>Quay lại trang thanh toán
                        </a>
                        <div class="text-muted small">
                            <p class="mb-1"><strong>Lưu ý:</strong> Đây là mô phỏng SMS. Trong môi trường thực tế, mã sẽ được gửi qua dịch vụ SMS.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
