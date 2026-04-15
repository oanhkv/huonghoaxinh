@extends('frontend.layouts.app')

@section('title', 'Xác nhận SMS - Hương Hoa Xinh')

@section('content')
<!-- DEBUG: Mã SMS để test: {{ session('payment_pending')['sms_code'] ?? 'N/A' }} -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <div class="bg-light rounded-circle mx-auto" style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-telephone-forward fs-2 text-primary"></i>
                        </div>
                    </div>

                    <h3 class="fw-bold mb-2">Xác nhận SMS</h3>
                    <p class="text-muted mb-4">Chúng tôi đã gửi mã xác nhận tới số điện thoại của bạn</p>

                    <div class="alert alert-info mb-4">
                        <strong>Số điện thoại:</strong> {{ substr(session('checkout_data')['phone'], 0, -4) }}****
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form method="POST" action="{{ route('checkout.sms.verify') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold">Nhập mã xác nhận (6 chữ số)</label>
                            <div class="input-group input-group-lg">
                                <input 
                                    type="text" 
                                    name="sms_code" 
                                    class="form-control text-center fw-bold fs-4 @error('sms_code') is-invalid @enderror" 
                                    placeholder="000000" 
                                    maxlength="6"
                                    inputmode="numeric"
                                    pattern="[0-9]{6}"
                                    value="{{ old('sms_code') }}"
                                    required
                                    autofocus
                                >
                                @error('sms_code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold mb-3">Xác nhận</button>
                    </form>

                    <p class="text-muted small mb-3">Không nhận được mã SMS?</p>
                    <form method="POST" action="{{ route('checkout.sms.resend') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none">Gửi lại mã</button>
                    </form>

                    <hr class="my-4">

                    <a href="{{ route('checkout.index') }}" class="btn btn-outline-secondary w-100">← Quay lại trang thanh toán</a>
                </div>
            </div>

            <div class="alert alert-info mt-4 small">
                <strong>🔍 Mã Demo:</strong> 
                <span class="font-monospace bg-light p-2 rounded d-block mt-2">
                    Mã xác nhận: <strong>{{ session('payment_pending')['sms_code'] ?? 'Chưa được tạo' }}</strong>
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
