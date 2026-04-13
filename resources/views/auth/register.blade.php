@extends('frontend.layouts.app')

@section('title', 'Đăng Ký - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    <h3 class="text-center fw-bold mb-4">ĐĂNG KÝ TÀI KHOẢN</h3>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="name" 
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Họ và tên *" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="email" name="email" 
                                       class="form-control @error('email') is-invalid @enderror"
                                       placeholder="Email *" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="tel" name="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       placeholder="Số điện thoại" value="{{ old('phone') }}">
                                @error('phone')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <input type="text" name="address" 
                                       class="form-control @error('address') is-invalid @enderror"
                                       placeholder="Địa chỉ nhận hàng" value="{{ old('address') }}">
                                @error('address')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="password" name="password" 
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Mật khẩu *" required>
                                @error('password')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="password" name="password_confirmation" 
                                       class="form-control"
                                       placeholder="Xác nhận mật khẩu *" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100 mb-3">
                            ĐĂNG KÝ NGAY
                        </button>

                        <div class="text-center">
                            <span>Bạn đã có tài khoản? </span>
                            <a href="{{ route('login') }}" class="text-danger fw-bold text-decoration-none">
                                ĐĂNG NHẬP
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection