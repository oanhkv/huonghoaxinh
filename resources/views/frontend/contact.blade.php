@extends('frontend.layouts.app')

@section('title', 'Liên hệ - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            <p class="text-success fw-bold mb-2">Liên hệ với chúng tôi</p>
            <h1 class="display-6 fw-bold mb-3">Gửi tin nhắn, đặt hoa hoặc hỏi đáp</h1>
            <p class="text-muted mb-0">Hãy chia sẻ yêu cầu và chúng tôi sẽ phản hồi nhanh chóng nhất để bạn có món quà hoa hoàn hảo.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="row mb-4">
            <div class="col-lg-10 mx-auto">
                <div class="alert alert-success rounded-4 shadow-sm py-3">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row justify-content-center gy-4">
        <div class="col-lg-10">
            <div class="contact-layout shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    <div class="col-lg-7 bg-white p-5">
                        <div class="mb-4">
                            <h5 class="fw-bold">Nói cho chúng tôi biết</h5>
                            <p class="text-muted mb-0">Điền thông tin chi tiết và chúng tôi sẽ liên hệ bạn sớm nhất.</p>
                        </div>

                        <form method="POST" action="{{ route('contact.submit') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control rounded-3 border-0 shadow-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control rounded-3 border-0 shadow-sm @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control rounded-3 border-0 shadow-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="subject" class="form-label">Tiêu đề</label>
                                    <input type="text" class="form-control rounded-3 border-0 shadow-sm @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}">
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="message" class="form-label">Nội dung</label>
                                <textarea class="form-control rounded-3 border-0 shadow-sm @error('message') is-invalid @enderror" id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4 text-start">
                                <button type="submit" class="btn btn-success btn-lg px-4 py-2 shadow-sm">Gửi tin nhắn</button>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-5 contact-side-panel p-5 text-white">
                        <div class="mb-4">
                            <h5 class="fw-bold">Thông tin liên hệ</h5>
                            <p class="text-white-75">Những kênh hỗ trợ nhanh chóng dành cho bạn.</p>
                        </div>
                                            <div class="info-card mb-4 rounded-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-white text-success me-3">
                                    <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-white">Hotline</h6>
                                    <p class="mb-0 text-white-75">{{ $siteSettings['hotline'] ?? '0859 773 086' }}</p>
                                </div>
                            </div>
                            <p class="text-white-75 mb-0">Luôn sẵn sàng hỗ trợ bạn 24/7.</p>
                        </div>

                        <div class="info-card mb-4 rounded-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-white text-success me-3">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-white">Địa chỉ</h6>
                                    <p class="mb-0 text-white-75">{{ $siteSettings['address'] ?? 'Quận Gò Vấp, TP.HCM' }}</p>
                                </div>
                            </div>
                            <p class="text-white-75 mb-0">Đến thăm cửa hàng hoặc nhận giao hoa tận nơi.</p>
                        </div>

                        <div class="info-card rounded-4">
                            <div class="d-flex align-items-center mb-3">
                                <div class="icon-box bg-white text-success me-3">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-1 text-white">Email</h6>
                                    <p class="mb-0 text-white-75">{{ $siteSettings['support_email'] ?? 'support@huonghoaxinh.vn' }}</p>
                                </div>
                            </div>
                            <p class="text-white-75 mb-0">Nhận thông tin và phản hồi trong 1 ngày làm việc.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.contact-layout {
    border-radius: 30px;
}
.contact-side-panel {
    background: linear-gradient(135deg, #0f9d58 0%, #0a7e42 100%);
}
.contact-side-panel .icon-box {
    width: 48px;
    height: 48px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.05rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
}
.info-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease;
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.15);
    padding: 16px;
    min-height: 130px;
}
.info-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 18px 30px rgba(0, 0, 0, 0.18);
}
.contact-panel .form-control {
    background: #f8f9fa;
}
.contact-panel .form-control:focus {
    background: #ffffff;
    border-color: #198754;
    box-shadow: 0 0 0 0.15rem rgba(25, 135, 84, 0.2);
}
.btn-success {
    background-color: #198754;
    border-color: #198754;
}
.btn-success:hover {
    background-color: #146c43;
    border-color: #146c43;
}
@media (max-width: 991px) {
    .contact-side-panel {
        min-height: auto;
    }
}
</style>
@endsection
