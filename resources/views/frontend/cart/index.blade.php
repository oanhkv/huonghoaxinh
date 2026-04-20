@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
        </ol>
    </nav>

    <h2 class="mb-4">
        <i class="fas fa-shopping-cart me-2 text-success"></i>Giỏ hàng của bạn
    </h2>

    @if($cartItems->isEmpty())
        <!-- Giỏ hàng trống -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm p-5 text-center">
                    <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                    <h4 class="mb-3">Giỏ hàng của bạn trống</h4>
                    <p class="text-muted mb-4">Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục mua sắm</p>
                    <a href="{{ route('shop') }}" class="btn btn-success btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <!-- Danh sách sản phẩm -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    @php
                        $groupedItems = $cartItems->groupBy('product_id');
                    @endphp
                    <div class="card-header bg-light border-bottom py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Sản phẩm trong giỏ ({{ $groupedItems->count() }} mục)
                        </h6>
                    </div>

                    <div class="card-body p-0">
                        @foreach($groupedItems as $items)
                            @php
                                $firstItem = $items->first();
                                $groupSubtotal = $items->sum(function ($item) {
                                    return $item->quantity * $item->price;
                                });
                            @endphp
                            <div class="border-bottom p-3">
                                <div class="row align-items-start g-3">
                                    <!-- Hình ảnh sản phẩm -->
                                    <div class="col-md-3">
                                        @if($firstItem->product->image)
                                            <img src="{{ asset('storage/' . $firstItem->product->image) }}" 
                                                 alt="{{ $firstItem->product->name }}"
                                                 class="img-fluid rounded" style="height: 100px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                                 style="height: 100px; width: 100%;">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="col-md-9">
                                        <a href="{{ route('product.show', $firstItem->product->slug) }}" 
                                           class="text-decoration-none text-dark">
                                            <h6 class="mb-1 fw-bold">{{ $firstItem->product->name }}</h6>
                                        </a>
                                        <p class="text-muted small mb-2">SKU: #{{ $firstItem->product->id }}</p>

                                        @foreach($items as $item)
                                            <div class="border rounded p-3 mb-2 cart-item" data-cart-id="{{ $item->id }}">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <div>
                                                        <span class="text-muted small">{{ $item->variant ?: 'Mặc định' }}</span><br>
                                                        <strong class="text-success">{{ number_format($item->price, 0, ',', '.') }} ₫</strong>
                                                    </div>
                                                    <button class="btn btn-danger btn-sm" type="button" 
                                                            data-cart-id="{{ $item->id }}" title="Xóa sản phẩm">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex flex-wrap align-items-center gap-2">
                                                    <button class="btn btn-outline-secondary btn-sm p-1 decrease-qty" type="button">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="form-control form-control-sm text-center qty-input" 
                                                           value="{{ $item->quantity }}" 
                                                           min="1" 
                                                           max="{{ $item->product->stock }}"
                                                           data-price="{{ $item->price }}"
                                                           style="width: 60px; height: 34px;">
                                                    <button class="btn btn-outline-secondary btn-sm p-1 increase-qty" type="button">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                    <span class="text-muted small ms-2">Thành tiền: <span class="item-subtotal">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span> ₫</span>
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="text-end small text-secondary mt-1">
                                            Tổng nhóm: <span class="fw-semibold text-dark">{{ number_format($groupSubtotal, 0, ',', '.') }} ₫</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tiếp tục mua sắm -->
                <div class="mb-4">
                    <a href="{{ route('shop') }}" class="btn btn-outline-success">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>

            <!-- Tóm tắt đơn hàng -->
            <div class="col-lg-4">
                <!-- Mã giảm giá -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light border-bottom py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-tag me-2 text-success"></i>Mã giảm giá
                        </h6>
                    </div>
                    <div class="card-body">
                        <form id="voucherForm" class="d-flex gap-2">
                            @csrf
                            <input type="text" name="voucher_code" class="form-control form-control-sm" 
                                   placeholder="Nhập mã giảm giá..." id="voucherInput">
                            <button type="submit" class="btn btn-success btn-sm" id="applyVoucherBtn">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <div id="voucherMessage" class="mt-2"></div>
                    </div>
                </div>

                <!-- Tóm tắt thanh toán -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light border-bottom py-3">
                        <h6 class="mb-0">
                            <i class="fas fa-receipt me-2 text-success"></i>Tóm tắt đơn hàng
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Tạm tính:</span>
                            <span class="fw-bold" id="subtotalDisplay">
                                {{ number_format($subtotal, 0, ',', '.') }} ₫
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span>Vận chuyển:</span>
                            <span class="fw-bold text-success" id="shippingDisplay">
                                @if($shipping === 0)
                                    Miễn phí
                                @else
                                    {{ number_format($shipping, 0, ',', '.') }} ₫
                                @endif
                            </span>
                        </div>

                        <div id="discountSection" class="d-none mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <span>Giảm giá:</span>
                                <span class="fw-bold text-danger" id="discountDisplay">-0 ₫</span>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mb-4">
                            <span class="fw-bold">Tổng cộng:</span>
                            <span class="h5 text-success fw-bold mb-0" id="totalDisplay">
                                {{ number_format($total, 0, ',', '.') }} ₫
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100 mb-2">
                            <i class="fas fa-lock me-2"></i>Tiến hành thanh toán
                        </a>

                        <button class="btn btn-outline-danger w-100" id="clearCartBtn" type="button">
                            <i class="fas fa-trash-alt me-2"></i>Xóa toàn bộ giỏ hàng
                        </button>
                    </div>
                </div>

                <!-- Chính sách -->
                <div class="mt-4 p-3 bg-light rounded">
                    <p class="small text-muted mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Giao hàng miễn phí</strong> cho đơn từ 500.000đ
                    </p>
                    <p class="small text-muted mb-2">
                        <i class="fas fa-sync text-success me-2"></i>
                        <strong>Hoàn lại hàng</strong> trong 7 ngày
                    </p>
                    <p class="small text-muted">
                        <i class="fas fa-phone text-success me-2"></i>
                        <strong>Hỗ trợ 24/7</strong> - 0859 773 086
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Thêm event listeners cho nút tăng/giảm số lượng
    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const qtyInput = this.parentElement.querySelector('.qty-input');
            const currentQty = parseInt(qtyInput.value);
            const maxQty = parseInt(qtyInput.getAttribute('max'));
            if (currentQty < maxQty) {
                qtyInput.value = currentQty + 1;
                updateCart(qtyInput);
            }
        });
    });

    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function() {
            const qtyInput = this.parentElement.querySelector('.qty-input');
            const currentQty = parseInt(qtyInput.value);
            if (currentQty > 1) {
                qtyInput.value = currentQty - 1;
                updateCart(qtyInput);
            }
        });
    });

    // Cập nhật giỏ hàng khi thay đổi số lượng
    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function() {
            if (parseInt(this.value) < 1) {
                this.value = 1;
            }
            updateCart(this);
        });
    });

    function updateCart(qtyInput) {
        const cartItem = qtyInput.closest('.cart-item');
        const cartId = cartItem.getAttribute('data-cart-id');
        const quantity = parseInt(qtyInput.value);

        fetch(`/cart/${cartId}/update`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ quantity: quantity })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Cập nhật thành tiền cho item
                const subtotalSpan = cartItem.querySelector('.item-subtotal');
                const price = parseFloat(qtyInput.dataset.price || data.subtotal / quantity);
                const newSubtotal = quantity * price;
                subtotalSpan.textContent = new Intl.NumberFormat('vi-VN').format(newSubtotal);

                // Tính lại tổng giỏ hàng
                updateCartTotal();
            } else {
                alert(data.message);
                location.reload();
            }
        });
    }

    // Xóa sản phẩm khỏi giỏ hàng
    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                const cartId = this.getAttribute('data-cart-id');

                fetch(`/cart/${cartId}/remove`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.cart-item').remove();
                        updateCartTotal();
                        
                        // Kiểm tra nếu giỏ hàng trống
                        if (document.querySelectorAll('.cart-item').length === 0) {
                            location.reload();
                        }
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });

    // Xóa toàn bộ giỏ hàng
    document.getElementById('clearCartBtn')?.addEventListener('click', function() {
        if (confirm('Bạn có chắc muốn xóa tất cả sản phẩm trong giỏ hàng?')) {
            fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            });
        }
    });

    // Áp dụng mã giảm giá
    document.getElementById('voucherForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const voucherCode = document.getElementById('voucherInput').value;
        const messageDiv = document.getElementById('voucherMessage');

        if (!voucherCode) {
            messageDiv.innerHTML = '<div class="alert alert-warning alert-sm p-2 mb-0">Vui lòng nhập mã giảm giá</div>';
            return;
        }

        fetch('/cart/apply-voucher', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ voucher_code: voucherCode })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.innerHTML = '<div class="alert alert-success alert-sm p-2 mb-0">✓ ' + data.message + '</div>';
                
                // Hiển thị giảm giá
                document.getElementById('discountSection').classList.remove('d-none');
                document.getElementById('discountDisplay').textContent = 
                    '-' + new Intl.NumberFormat('vi-VN').format(data.discount) + ' ₫';
                document.getElementById('totalDisplay').textContent = 
                    new Intl.NumberFormat('vi-VN').format(data.total) + ' ₫';
                
                // Disable input voucher
                document.getElementById('voucherInput').disabled = true;
                document.getElementById('applyVoucherBtn').disabled = true;
            } else {
                messageDiv.innerHTML = '<div class="alert alert-danger alert-sm p-2 mb-0">✗ ' + data.message + '</div>';
            }
        });
    });

    // Cập nhật tổng giỏ hàng
    function updateCartTotal() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const qtyInput = item.querySelector('.qty-input');
            const subtotalSpan = item.querySelector('.item-subtotal');
            const price = parseInt(subtotalSpan.textContent.replace(/\D/g, '')) / parseInt(qtyInput.value);
            const quantity = parseInt(qtyInput.value);
            subtotal += quantity * price;
        });

        document.getElementById('subtotalDisplay').textContent = 
            new Intl.NumberFormat('vi-VN').format(subtotal) + ' ₫';
        
        // Cập nhật tổng (nếu không có discount)
        if (document.getElementById('discountSection').classList.contains('d-none')) {
            document.getElementById('totalDisplay').textContent = 
                new Intl.NumberFormat('vi-VN').format(subtotal) + ' ₫';
        }
    }
</script>

<style>
    .alert-sm {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem !important;
    }
</style>
@endsection
