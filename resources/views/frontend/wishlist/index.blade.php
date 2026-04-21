@extends('frontend.layouts.app')

@section('title', 'Sản phẩm yêu thích - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8">
            <h2 class="mb-3">
                <i class="fas fa-heart text-danger me-2"></i>Sản phẩm yêu thích
            </h2>
            <p class="text-muted">
                Bạn có <strong>{{ $wishlists->total() }}</strong> sản phẩm yêu thích
            </p>
        </div>
        <div class="col-lg-4 text-end">
            <a href="{{ route('shop') }}" class="btn btn-outline-success">
                <i class="fas fa-arrow-left me-1"></i>Tiếp tục mua sắm
            </a>
        </div>
    </div>

    @if($wishlists->count() > 0)
        <div class="row g-4">
            @forelse($wishlists as $wishlist)
            @php $product = $wishlist->product; @endphp
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 shadow-sm product-card">
                    <!-- Product Image Container -->
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top h-100" style="object-fit: cover;" alt="{{ $product->name }}">
                        @else
                            <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        @endif

                        <!-- Overlay with Action Buttons -->
                        <div class="product-overlay">
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-sm">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button class="btn btn-sm" onclick="addToCart({{ $product->id }})">
                                <i class="fas fa-shopping-cart"></i>
                            </button>
                        </div>

                        <!-- Featured Badge -->
                        @if($product->is_featured)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                                <i class="fas fa-star me-1"></i>Nổi bật
                            </span>
                        @endif

                        <!-- Stock Status Badge -->
                        @if($product->stock <= 0)
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <span class="badge bg-dark p-3 fs-6">Hết hàng</span>
                            </div>
                        @endif

                        <!-- Remove from Wishlist Button -->
                        <button class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2" 
                                onclick="toggleWishlist({{ $product->id }})">
                            <i class="fas fa-heart-broken"></i>
                        </button>
                    </div>

                    <!-- Product Info -->
                    <div class="card-body d-flex flex-column">
                        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark flex-grow-1">
                            <h6 class="card-title mb-2">{{ Str::limit($product->name, 50) }}</h6>
                        </a>

                        <!-- Rating -->
                        <div class="d-flex align-items-center mb-2 small">
                            @php $avgRating = $product->average_rating; @endphp
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                            <span class="ms-1 text-muted">({{ $product->review_count }})</span>
                        </div>

                        <!-- Price -->
                        <p class="text-success fw-bold mb-3">{{ number_format($product->price, 0, ',', '.') }} ₫</p>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('product.show', $product->slug) }}" 
                               class="btn btn-outline-success btn-sm">
                                <i class="fas fa-info-circle me-1"></i>Chi tiết
                            </a>
                            <button class="btn btn-success btn-sm" onclick="addToCart({{ $product->id }})" 
                                    {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart me-1"></i>Giỏ hàng
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-heart fa-5x text-muted mb-3" style="opacity: 0.3;"></i>
                    <p class="text-muted fs-5">Không có sản phẩm yêu thích nào</p>
                    <p class="text-muted mb-4">Hãy thêm sản phẩm bạn yêu thích vào danh sách này!</p>
                    <a href="{{ route('shop') }}" class="btn btn-success">
                        <i class="fas fa-shopping-bag me-1"></i>Khám phá sản phẩm
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($wishlists->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $wishlists->links() }}
        </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="fas fa-heart fa-5x text-muted mb-3" style="opacity: 0.3;"></i>
            <p class="text-muted fs-5">Không có sản phẩm yêu thích nào</p>
            <p class="text-muted mb-4">Hãy thêm sản phẩm bạn yêu thích vào danh sách này!</p>
            <a href="{{ route('shop') }}" class="btn btn-success">
                <i class="fas fa-shopping-bag me-1"></i>Khám phá sản phẩm
            </a>
        </div>
    @endif
</div>

<style>
    .product-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        gap: 10px;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .product-overlay .btn {
        background: white;
        color: #28a745;
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .product-overlay .btn:hover {
        transform: scale(1.1);
        background: #28a745;
        color: white;
    }
</style>

<script>
function toggleWishlist(productId) {
    @auth
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                // Reload page after 500ms
                setTimeout(() => {
                    location.reload();
                }, 500);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Lỗi: ' + error.message, 'error');
        });
    @else
        window.location.href = '{{ route("login") }}';
    @endauth
}
</script>
@endsection
