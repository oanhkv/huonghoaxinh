@extends('frontend.layouts.app')

@section('title', 'Đăng Nhập - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            
            <div class="card shadow-lg border-0">
                <div class="card-body p-5">
                    
                    <h3 class="text-center fw-bold mb-4">ĐĂNG NHẬP</h3>
                    
                    <!-- Tab chọn loại tài khoản -->
                    <ul class="nav nav-pills nav-justified mb-4" id="loginTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="customer-tab" data-bs-toggle="tab" 
                                    data-bs-target="#customer" type="button">
                                👤 Khách hàng
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="admin-tab" data-bs-toggle="tab" 
                                    data-bs-target="#admin" type="button">
                                ⚡ Quản trị viên
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">
                        
                        <!-- Tab Khách hàng -->
                        <div class="tab-pane fade show active" id="customer" role="tabpanel">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <input type="email" name="email" 
                                           class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           placeholder="Email hoặc số điện thoại" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <input type="password" name="password" 
                                           class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           placeholder="Mật khẩu" required>
                                    @error('password')
                                        <span class="text-danger small">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" name="remember" class="form-check-input">
                                        <label class="form-check-label">Ghi nhớ tôi</label>
                                    </div>
                                    <a href="{{ route('password.request') }}" class="text-danger">Quên mật khẩu?</a>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100">
                                    ĐĂNG NHẬP TÀI KHOẢN KHÁCH HÀNG
                                </button>
                            </form>
                        </div>

                        <!-- Tab Admin -->
                        <div class="tab-pane fade" id="admin" role="tabpanel">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                
                                <div class="mb-3">
                                    <input type="email" name="email" 
                                           class="form-control form-control-lg"
                                           placeholder="Email Admin" required>
                                </div>

                                <div class="mb-3">
                                    <input type="password" name="password" 
                                           class="form-control form-control-lg"
                                           placeholder="Mật khẩu Admin" required>
                                </div>

                                <input type="hidden" name="is_admin" value="1">

                                <button type="submit" class="btn btn-danger btn-lg w-100">
                                    <i class="fas fa-shield-alt"></i> ĐĂNG NHẬP QUẢN TRỊ VIÊN
                                </button>
                            </form>
                        </div>

                    </div>

                    <div class="text-center mt-4">
                        <span>Chưa có tài khoản? </span>
                        <a href="{{ route('register') }}" class="text-danger fw-bold">Đăng ký ngay</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection