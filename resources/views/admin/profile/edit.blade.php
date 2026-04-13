@extends('admin.layouts.admin')

@section('title', 'Thông tin cá nhân')

@section('content')
<div class="container-fluid admin-form-shell">
    <div class="card admin-form-card shadow-sm">
        <div class="admin-form-hero p-4 p-lg-5">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="admin-form-icon">
                    <i class="fas fa-user-gear fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-2">Thông tin cá nhân</h3>
                    <p class="admin-form-subtitle">Cập nhật thông tin tài khoản quản trị của bạn với giao diện sáng, rõ và tinh gọn hơn.</p>
                </div>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại dashboard
                </a>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <form method="POST" action="{{ route('admin.profile.update') }}">
                @csrf
                @method('PUT')

                <div class="admin-section-title">Hồ sơ cá nhân</div>
                <div class="admin-form-panel mb-4">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label">Họ và tên</label>
                            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if($hasPhone)
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input id="phone" name="phone" type="text" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" placeholder="Nhập số điện thoại">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        @if($hasAddress)
                            <div class="col-md-6">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input id="address" name="address" type="text" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}" placeholder="Nhập địa chỉ">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 flex-wrap">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4 py-2">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-save me-1"></i> Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
