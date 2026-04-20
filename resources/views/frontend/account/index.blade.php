@extends('frontend.layouts.app')

@section('title', 'Tai khoan cua toi - Huong Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Tai khoan cua toi</h2>
                    <p class="text-muted mb-0">Cap nhat thong tin, doi mat khau, khoa tai khoan hoac xoa tai khoan.</p>
                </div>
                <a href="{{ route('orders.history') }}" class="btn btn-outline-success">Don hang cua toi</a>
            </div>

            @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
                <div class="alert alert-success">Da cap nhat thanh cong.</div>
            @endif

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold">Thong tin ca nhan</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Ho va ten</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control @error('email') is-invalid @enderror" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">So dien thoai</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dia chi</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}" class="form-control @error('address') is-invalid @enderror">
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button class="btn btn-success mt-3">Luu thong tin</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light fw-bold">Doi mat khau</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Mat khau hien tai</label>
                                <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                                @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Mat khau moi</label>
                                <input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                                @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Xac nhan mat khau</label>
                                <input type="password" name="password_confirmation" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" required>
                                @error('password_confirmation', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button class="btn btn-primary mt-3">Cap nhat mat khau</button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-light fw-bold text-danger">Bao mat tai khoan</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold">Khoa tai khoan</h6>
                                <p class="small text-muted">Khi khoa, ban se bi dang xuat ngay va khong dang nhap lai duoc den khi admin mo khoa.</p>
                                <form method="POST" action="{{ route('profile.lock') }}">
                                    @csrf
                                    <input type="password" name="password" class="form-control mb-2 @error('password') is-invalid @enderror" placeholder="Nhap mat khau de khoa tai khoan" required>
                                    @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <button class="btn btn-warning">Khoa tai khoan</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h6 class="fw-bold text-danger">Xoa tai khoan</h6>
                                <p class="small text-muted">Du lieu tai khoan se bi xoa vinh vien va khong the khoi phuc.</p>
                                <form method="POST" action="{{ route('profile.destroy') }}">
                                    @csrf
                                    @method('delete')
                                    <input type="password" name="password" class="form-control mb-2 @error('password', 'userDeletion') is-invalid @enderror" placeholder="Nhap mat khau de xoa tai khoan" required>
                                    @error('password', 'userDeletion')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                    <button class="btn btn-danger">Xoa tai khoan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
