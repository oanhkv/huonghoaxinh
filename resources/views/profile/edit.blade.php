@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-2">
                <div>
                    <h2 class="fw-bold mb-1">Quản lý hồ sơ cá nhân</h2>
                    <p class="text-muted mb-0">Cập nhật thông tin tài khoản, mật khẩu và bảo mật tài khoản của bạn.</p>
                </div>
                <a href="{{ route('orders.history') }}" class="btn btn-outline-success">Đơn hàng của tôi</a>
            </div>

            <div class="mb-4">
                @include('profile.partials.update-profile-information-form')
            </div>
            <div class="mb-4">
                @include('profile.partials.update-password-form')
            </div>
            <div>
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
