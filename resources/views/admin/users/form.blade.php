@extends('admin.layouts.admin')

@php
    $isCreate = ($mode ?? 'create') === 'create';
    $title = $isCreate ? 'Thêm Admin' : 'Chỉnh sửa tài khoản';
    $backRoute = ($isAdminForm ?? false) ? route('admin.users.admins') : route('admin.users.index');
@endphp

@section('title', $title)

@section('content')
<div class="container-fluid admin-form-shell">
    <div class="card admin-form-card shadow-sm">
        <div class="admin-form-hero p-4 p-lg-5">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="admin-form-icon">
                    <i class="fas fa-user-shield fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-2">{{ $title }}</h3>
                    <p class="admin-form-subtitle">
                        {{ $isCreate ? 'Tạo tài khoản quản trị mới với bố cục rõ ràng, hiện đại và dễ thao tác.' : 'Cập nhật nhanh thông tin tài khoản quản trị mà vẫn giữ bố cục dễ đọc.' }}
                    </p>
                </div>
                <a href="{{ $backRoute }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <form method="POST" action="{{ $isCreate ? route('admin.users.store') : route('admin.users.update', $user) }}">
                @csrf
                @if(! $isCreate)
                    @method('PUT')
                @endif

                <div class="admin-section-title">Thông tin tài khoản</div>
                <div class="admin-form-panel mb-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        @if($hasPhone)
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" id="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif

                        @if($hasAddress)
                            <div class="col-md-6">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="text" id="address" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $user->address) }}">
                                @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @endif
                    </div>
                </div>

                <div class="admin-section-title">Bảo mật</div>
                <div class="admin-form-panel">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Mật khẩu {{ $isCreate ? '' : '(để trống nếu không đổi)' }}</label>
                            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $isCreate ? 'required' : '' }}>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" {{ $isCreate ? 'required' : '' }}>
                            <div class="form-text">Nhập lại để tránh sai sót khi lưu.</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end flex-wrap mt-4">
                    <a href="{{ $backRoute }}" class="btn btn-outline-secondary px-4 py-2">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-save me-1"></i> {{ $isCreate ? 'Thêm Admin' : 'Lưu thay đổi' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
