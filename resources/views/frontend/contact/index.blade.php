@extends('frontend.layouts.app')

@section('title', 'Liên hệ - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-2 text-center">Liên hệ với chúng tôi</h1>
            <p class="text-muted text-center mb-5">Chúng tôi rất vui được nghe từ bạn. Hãy gửi tin nhắn hoặc gọi cho chúng tôi ngay bây giờ.</p>

            <div class="row mb-5">
                <div class="col-md-4 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle mx-auto" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold">Địa chỉ</h6>
                    <p class="text-muted small">123 Đường Hoa, Quận 1<br>TP. Hồ Chí Minh, Việt Nam</p>
                </div>

                <div class="col-md-4 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle mx-auto" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="bi bi-telephone"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold">Điện thoại</h6>
                    <p class="text-muted small"><a href="tel:+84123456789" class="text-decoration-none">+84 (12) 3456 789</a><br>Thứ 2 - Chủ nhật: 8AM - 10PM</p>
                </div>

                <div class="col-md-4 text-center mb-4">
                    <div class="mb-3">
                        <div class="bg-primary text-white rounded-circle mx-auto" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                            <i class="bi bi-envelope"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold">Email</h6>
                    <p class="text-muted small"><a href="mailto:info@huonghoaxinh.com" class="text-decoration-none">info@huonghoaxinh.com</a><br>Chúng tôi sẽ phản hồi trong 24h</p>
                </div>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <h4 class="fw-bold mb-4">Gửi tin nhắn cho chúng tôi</h4>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">Họ tên</label>
                                <input 
                                    type="text" 
                                    name="name" 
                                    id="name" 
                                    class="form-control @error('name') is-invalid @enderror" 
                                    value="{{ old('name') }}"
                                    placeholder="Nhập họ tên của bạn"
                                    required
                                >
                                @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-bold">Email</label>
                                <input 
                                    type="email" 
                                    name="email" 
                                    id="email" 
                                    class="form-control @error('email') is-invalid @enderror" 
                                    value="{{ old('email') }}"
                                    placeholder="example@email.com"
                                    required
                                >
                                @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label fw-bold">Số điện thoại</label>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone" 
                                    class="form-control @error('phone') is-invalid @enderror" 
                                    value="{{ old('phone') }}"
                                    placeholder="+84 (123) 456 789"
                                    required
                                >
                                @error('phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label fw-bold">Tiêu đề</label>
                                <input 
                                    type="text" 
                                    name="subject" 
                                    id="subject" 
                                    class="form-control @error('subject') is-invalid @enderror" 
                                    value="{{ old('subject') }}"
                                    placeholder="Tiêu đề tin nhắn"
                                    required
                                >
                                @error('subject')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label fw-bold">Nội dung tin nhắn</label>
                            <textarea 
                                name="message" 
                                id="message" 
                                rows="6"
                                class="form-control @error('message') is-invalid @enderror" 
                                placeholder="Nhập nội dung tin nhắn của bạn..."
                                required
                            >{{ old('message') }}</textarea>
                            @error('message')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input 
                                type="checkbox" 
                                class="form-check-input" 
                                id="agree"
                                required
                            >
                            <label class="form-check-label" for="agree">
                                Tôi đồng ý với điều khoản và chính sách bảo mật của Hương Hoa Xinh
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold">
                            <i class="bi bi-send me-2"></i>Gửi tin nhắn
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
