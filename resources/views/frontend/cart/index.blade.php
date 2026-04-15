@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng của bạn - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="fw-bold mb-3">Giỏ hàng của bạn</h1>
                    <p class="text-muted mb-4">
                        Đây là nơi bạn sẽ thấy sản phẩm đã thêm vào giỏ hàng. Nếu bạn chưa có sản phẩm nào,
                        hãy quay lại trang cửa hàng để tiếp tục mua sắm.
                    </p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(!empty($cartItems) && count($cartItems))
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Kích cỡ</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Tổng</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $cartKey => $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($item['image'])
                                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" style="width: 80px; height: 80px; object-fit: cover;" class="rounded">
                                                    @else
                                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">No image</div>
                                                    @endif
                                                    <div>
                                                        <a href="{{ route('product.show', $item['slug']) }}" class="text-decoration-none text-dark fw-bold">{{ $item['name'] }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $item['size'] ?? 'Standard' }}</td>
                                            <td>
                                                <form method="POST" action="{{ route('cart.update', $cartKey) }}" class="d-flex align-items-center gap-2">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm" style="width: 80px;" />
                                                    <button type="submit" class="btn btn-sm btn-outline-secondary">Cập nhật</button>
                                                </form>
                                            </td>
                                            <td>{{ number_format($item['price']) }} đ</td>
                                            <td>{{ number_format($item['price'] * $item['quantity']) }} đ</td>
                                            <td>
                                                <form method="POST" action="{{ route('cart.destroy', $cartKey) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <div class="text-end">
                                <p class="mb-2">Tổng đơn hàng</p>
                                <h4 class="fw-bold">{{ number_format($cartTotal) }} đ</h4>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('shop') }}" class="btn btn-outline-success">Tiếp tục mua sắm</a>
                            <a href="{{ route('checkout.index') }}" class="btn btn-success">Thanh toán</a>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Giỏ hàng trống</h5>
                            <p>Hiện tại bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                            <a href="{{ route('shop') }}" class="btn btn-success mt-2">Tiếp tục mua sắm</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
