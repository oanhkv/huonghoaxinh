@extends('frontend.layouts.app')

@section('title', $product->name . ' - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row">

        <!-- Hình ảnh sản phẩm -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="img-fluid rounded" alt="{{ $product->name }}">
                @else
                    <img src="https://via.placeholder.com/600x600" class="img-fluid rounded" alt="{{ $product->name }}">
                @endif
            </div>
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-lg-6">
            <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
            
            <p class="text-danger fs-3 fw-bold mb-4" id="product-price">
                {{ number_format($product->price) }} đ
            </p>

            <div class="mb-4">
                <strong>Danh mục:</strong> 
                <span class="badge bg-light text-dark">{{ $product->category->name ?? 'Chưa phân loại' }}</span>
            </div>

            <div class="mb-4">
                <strong>Trạng thái:</strong> 
                @if($product->stock > 0)
                    <span class="text-success">Còn hàng ({{ $product->stock }} sản phẩm)</span>
                @else
                    <span class="text-danger">Hết hàng</span>
                @endif
            </div>

            <div class="mb-5">
                <h5>Mô tả sản phẩm</h5>
                <p class="text-muted">{{ $product->description ?? 'Đang cập nhật mô tả chi tiết...' }}</p>
            </div>

            @php
                $sizeOptions = [
                    ['label' => '40cm', 'value' => '40cm', 'price' => $product->price],
                    ['label' => '50cm', 'value' => '50cm', 'price' => $product->price + 150000],
                    ['label' => '60cm', 'value' => '60cm', 'price' => $product->price + 250000],
                ];
            @endphp

            <div class="mb-4">
                <h5>Chọn kích cỡ</h5>
                <select id="sizeSelector" name="size_label" class="form-select" style="max-width: 320px;">
                    @foreach($sizeOptions as $option)
                        <option value="{{ $option['value'] }}" data-price="{{ $option['price'] }}">
                            {{ $option['label'] }} @if($option['price'] > $product->price) (+{{ number_format($option['price'] - $product->price) }} đ) @endif
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Nút hành động -->
            <form method="POST" action="{{ route('cart.store') }}" class="w-100">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <input type="hidden" name="size_price" id="sizePriceInput" value="{{ $product->price }}">
                <div class="d-flex gap-3 align-items-center">
                    <button type="submit" class="btn btn-success btn-lg px-5 w-100" @if($product->stock <= 0) disabled @endif>
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </div>
            </form>
            <form method="POST" action="{{ route('favorites.store') }}" class="mt-3">
                @csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-outline-danger btn-lg px-4 w-100">
                    <i class="fas fa-heart"></i> Thêm vào yêu thích
                </button>
            </form>

            <div class="mt-4 text-muted small">
                <p><strong>Giao hàng:</strong> Miễn phí nội thành TP.HCM trong 2 giờ</p>
                <p><strong>Cam kết:</strong> Hoa tươi 100% - Đổi trả trong 24h</p>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var sizeSelector = document.getElementById('sizeSelector');
                var priceDisplay = document.getElementById('product-price');
                var sizePriceInput = document.getElementById('sizePriceInput');

                if (!sizeSelector || !priceDisplay || !sizePriceInput) {
                    return;
                }

                function updateSizePrice() {
                    var selected = sizeSelector.options[sizeSelector.selectedIndex];
                    var price = selected ? parseInt(selected.dataset.price, 10) : {{ $product->price }};
                    priceDisplay.textContent = new Intl.NumberFormat('vi-VN').format(price) + ' đ';
                    sizePriceInput.value = price;
                }

                sizeSelector.addEventListener('change', updateSizePrice);
                updateSizePrice();
            });
        </script>
    </div>

    <!-- Sản phẩm liên quan -->
    <div class="mt-5">
        <h4 class="fw-bold mb-4">Sản phẩm liên quan</h4>
        <div class="row g-4">
            @foreach($relatedProducts as $related)
            <div class="col-md-3 col-6">
                <div class="card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    @if($related->image)
                        <img src="{{ asset('storage/' . $related->image) }}" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $related->name }}">
                    @endif
                    <div class="card-body text-center">
                        <h6 class="card-title">{{ $related->name }}</h6>
                        <p class="text-danger fw-bold">{{ number_format($related->price) }} đ</p>
                    </div>
                    <a href="{{ route('product.show', $related->slug) }}" class="stretched-link"></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection