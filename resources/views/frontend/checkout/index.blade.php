@extends('frontend.layouts.app')

@section('title', 'Thông tin đơn hàng - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thông tin đơn hàng</li>
                </ol>
            </nav>
            <h1 class="mt-3 fw-bold">Thông tin đơn hàng</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('checkout.store') }}">
        @csrf
        <div class="row g-4">
            <div class="col-xl-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Đơn hàng của bạn</h5>
                        </div>

                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center gap-3 border rounded-4 p-3 mb-3">
                                <div style="width:90px; height:90px; overflow:hidden; border-radius:12px; background:#f8f9fa;">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="img-fluid h-100 w-100" style="object-fit:cover;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">No image</div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item['name'] }}</h6>
                                    <div class="text-muted small">Số lượng: {{ $item['quantity'] }}</div>
                                    <div class="text-muted small">Kích cỡ: {{ $item['size'] ?? 'Standard' }}</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold text-danger">{{ number_format($item['price'] * $item['quantity']) }} đ</div>
                                    <div class="text-muted small">{{ number_format($item['price']) }} đ / 1</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold mb-0">Địa chỉ nhận hàng</h5>
                            <small class="text-muted">Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nguyễn Văn A" required>
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="email@example.com" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Số điện thoại</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="0901234567" required>
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Số nhà / Ngõ / Đường</label>
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Số nhà, đường, phường" required>
                                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tỉnh thành</label>
                                <select name="province" class="form-select @error('province') is-invalid @enderror" required>
                                    <option value="">-- Tỉnh thành --</option>
                                    <option value="Hồ Chí Minh" {{ old('province') == 'Hồ Chí Minh' ? 'selected' : '' }}>Hồ Chí Minh</option>
                                    <option value="Hà Nội" {{ old('province') == 'Hà Nội' ? 'selected' : '' }}>Hà Nội</option>
                                    <option value="Đà Nẵng" {{ old('province') == 'Đà Nẵng' ? 'selected' : '' }}>Đà Nẵng</option>
                                </select>
                                @error('province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Quận huyện</label>
                                <select name="district" class="form-select @error('district') is-invalid @enderror" required>
                                    <option value="">-- Quận huyện --</option>
                                    <option value="Quận 1" {{ old('district') == 'Quận 1' ? 'selected' : '' }}>Quận 1</option>
                                    <option value="Quận 3" {{ old('district') == 'Quận 3' ? 'selected' : '' }}>Quận 3</option>
                                    <option value="Quận Gò Vấp" {{ old('district') == 'Quận Gò Vấp' ? 'selected' : '' }}>Quận Gò Vấp</option>
                                </select>
                                @error('district')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Phường xã</label>
                                <select name="ward" class="form-select @error('ward') is-invalid @enderror" required>
                                    <option value="">-- Phường xã --</option>
                                    <option value="Phường 1" {{ old('ward') == 'Phường 1' ? 'selected' : '' }}>Phường 1</option>
                                    <option value="Phường 5" {{ old('ward') == 'Phường 5' ? 'selected' : '' }}>Phường 5</option>
                                    <option value="Phường 10" {{ old('ward') == 'Phường 10' ? 'selected' : '' }}>Phường 10</option>
                                </select>
                                @error('ward')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" rows="3" class="form-control @error('note') is-invalid @enderror" placeholder="Ghi chú đến nhân viên giao hàng">{{ old('note') }}</textarea>
                                @error('note')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label d-block mb-2">Hình thức thanh toán</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_cod" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_cod">Thanh toán khi nhận hàng</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="payment_method" id="payment_online" value="online" {{ old('payment_method') === 'online' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="payment_online">Thanh toán online</label>
                                </div>
                                @error('payment_method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="fw-bold mb-4">Thông tin đơn hàng</h5>

                        <div class="mb-3 d-flex justify-content-between">
                            <span>Đơn giá</span>
                            <span class="fw-bold text-danger">{{ number_format($cartTotal) }} đ</span>
                        </div>

                        <div class="mb-3 d-flex justify-content-between">
                            <span>Phí giao hàng</span>
                            <span class="text-success">Miễn phí</span>
                        </div>

                        <hr>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <span class="fw-bold">Thành tiền</span>
                            <span class="fs-5 fw-bold text-success">{{ number_format($cartTotal) }} đ</span>
                        </div>

                        <button type="submit" class="btn btn-danger btn-lg w-100">Thanh toán</button>
                        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-3">← Quay lại giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
