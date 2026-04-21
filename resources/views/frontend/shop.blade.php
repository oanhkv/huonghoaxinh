@extends('frontend.layouts.app')

@section('title', 'Cửa hàng - Hương Hoa Xinh')

@section('content')
<div class="container py-5 shop-page">

    <div class="shop-hero rounded-4 p-4 p-lg-5 mb-5 text-white position-relative overflow-hidden reveal-up">
        <div class="position-absolute top-0 end-0 opacity-25" style="font-size: 8rem; line-height: 1; transform: translate(10%, -20%);">🌷</div>
        <div class="row align-items-center position-relative" style="z-index: 1;">
            <div class="col-lg-8">
                <p class="fw-bold mb-2 text-white-50 text-uppercase small letter-spacing-1">Cửa hàng trực tuyến</p>
                <h1 class="display-5 fw-bold mb-3">Tìm bó hoa ưng ý cho mọi dịp</h1>
                <p class="mb-0 opacity-90 lead">Khám phá bộ sưu tập hoa tươi, giỏ hoa và quà tặng — giao diện tối ưu để tôn sản phẩm.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="{{ route('vouchers') }}" class="btn btn-light btn-lg rounded-pill px-4 shadow me-lg-2 mb-2 mb-lg-0">
                    <i class="fas fa-tag me-2 text-danger"></i>Ưu đãi
                </a>
                <a href="#shop-grid" class="btn btn-success btn-lg rounded-pill px-4 shadow">
                    <i class="fas fa-seedling me-2"></i>Xem sản phẩm
                </a>
            </div>
        </div>
    </div>

    <!-- Banner Mã giảm giá -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 bg-gradient shadow-sm" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h5 class="text-white mb-2">
                                <i class="fas fa-tag me-2"></i>Ưu đãi đặc biệt cho bạn!
                            </h5>
                            <p class="text-white-50 mb-0">Sử dụng mã giảm giá để nhận ưu đãi tuyệt vời khi mua hàng hôm nay.</p>
                        </div>
                        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                            <a href="{{ route('vouchers') }}" class="btn btn-white btn-sm fw-bold">
                                <i class="fas fa-arrow-right me-2"></i>Xem tất cả mã
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Danh mục -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4 border-0 rounded-4 overflow-hidden reveal-up">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Danh mục Hoa</h5>
                </div>
                <div class="card-body p-3 bg-light">
                    <div class="category-sidebar">
                        <a href="{{ route('shop') }}" class="category-item {{ !request('category') ? 'active' : '' }}">
                            <span>Tất cả sản phẩm</span>
                            <span class="badge bg-success">{{ $products->total() }}</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('shop') }}?category={{ $category->slug }}" 
                               class="category-item {{ request('category') == $category->slug ? 'active' : '' }}">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-success">{{ $category->products_count ?? 0 }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="col-lg-9" id="shop-grid">
            <!-- Bộ lọc & Sắp xếp -->
            <div class="card shadow-sm mb-4 shop-filter-card border-0 reveal-up">
                <div class="card-body p-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-6">
                            <form method="GET" action="{{ route('shop') }}">
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" 
                                           placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                                    <button class="btn btn-success" type="submit">Tìm</button>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-3">
                            <select name="price_range" class="form-select form-select-lg" onchange="this.form.submit()" form="filterForm">
                                <option value="">-- Lọc theo giá --</option>
                                <option value="under_500" {{ request('price_range') == 'under_500' ? 'selected' : '' }}>Dưới 500.000đ</option>
                                <option value="500_1000" {{ request('price_range') == '500_1000' ? 'selected' : '' }}>500.000 - 1.000.000đ</option>
                                <option value="over_1000" {{ request('price_range') == 'over_1000' ? 'selected' : '' }}>Trên 1.000.000đ</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <form id="filterForm" method="GET" action="{{ route('shop') }}">
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                <select name="sort" class="form-select" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Mới nhất</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Giá thấp đến cao</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Giá cao đến thấp</option>
                                    <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Nổi bật</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <div class="row g-4">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6 reveal-up">
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

                            <!-- Wishlist Heart Button -->
                            <button class="btn btn-sm btn-light position-absolute top-0 end-0 m-2 wishlist-btn" 
                                    onclick="toggleWishlist({{ $product->id }}, event)"
                                    style="width: 40px; height: 40px; border-radius: 50%; padding: 0;">
                                <i class="fas fa-heart" style="color: #dc3545;"></i>
                            </button>

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
                        </div>

                        <!-- Product Info -->
                        <div class="card-body d-flex flex-column py-3">
                            <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark flex-grow-1">
                                <h5 class="card-title mb-2">{{ Str::limit($product->name, 55) }}</h5>
                            </a>

                            <div class="d-flex align-items-center mb-2 small text-muted">
                                @php $avgRating = $product->average_rating; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }} me-1"></i>
                                @endfor
                                <span class="ms-2">({{ $product->review_count }})</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="text-success fw-bold mb-0 fs-5">{{ number_format($product->price, 0, ',', '.') }} ₫</p>
                                @if($product->stock > 0)
                                    <span class="badge bg-success">Còn hàng</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </div>

                            <div class="d-grid gap-2">
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-info-circle me-1"></i>Chi tiết
                                </a>
                                <button class="btn btn-success btn-sm" onclick="addToCart({{ $product->id }})" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-cart me-1"></i>Giỏ hàng
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <p class="text-muted fs-5">Không tìm thấy sản phẩm nào phù hợp.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-5">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
.shop-hero {
    background: linear-gradient(125deg, #157347 0%, #0d6efd 45%, #6f42c1 100%);
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.18);
}
.shop-page .reveal-up {
    opacity: 0;
    transform: translateY(24px);
    transition: opacity 0.6s ease, transform 0.6s ease;
}
.shop-page .reveal-up.is-visible {
    opacity: 1;
    transform: translateY(0);
}
.shop-filter-card {
    border-radius: 24px;
    transition: transform 0.3s ease;
}
.shop-filter-card:hover {
    transform: translateY(-2px);
}
.product-card {
    border-radius: 24px;
    overflow: hidden;
    transition: transform 0.35s ease, box-shadow 0.35s ease;
    border: none;
}
.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 18px 40px rgba(50, 50, 93, 0.15);
}
.product-card .card-img-top {
    transition: transform 0.35s ease;
}
.product-card:hover .card-img-top {
    transform: scale(1.05);
}
.product-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0, 0, 0, 0.55);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 2;
}
.category-sidebar {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}
.category-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 18px;
    border-radius: 18px;
    background: #ffffff;
    border: 1px solid rgba(33, 37, 41, 0.08);
    color: #212529;
    text-decoration: none;
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
}
.category-item:hover,
.category-item.active {
    transform: translateX(6px);
    border-color: #198754;
    box-shadow: 0 18px 30px rgba(25, 135, 84, 0.12);
    background: #f0fbf4;
    color: #0f5132;
}
.category-item span {
    display: inline-flex;
    align-items: center;
}
.category-item .badge {
    min-width: 28px;
}
.product-card:hover .product-overlay {
    opacity: 1;
}
.product-overlay .btn,
.product-overlay a.btn {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.95);
    color: #212529;
    border: none;
}
.product-overlay .btn:hover,
.product-overlay a.btn:hover {
    background: #198754;
    color: white;
    transform: translateY(-2px);
}
.wishlist-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.09);
}
.wishlist-btn i {
    color: #dc3545;
}
.card-body .card-title {
    min-height: 56px;
}
@media (max-width: 767px) {
    .shop-filter-card {
        border-radius: 18px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var root = document.querySelector('.shop-page');
    if (!root) return;
    var els = root.querySelectorAll('.reveal-up');
    if (!('IntersectionObserver' in window)) {
        els.forEach(function (el) { el.classList.add('is-visible'); });
        return;
    }
    var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -30px 0px' });
    els.forEach(function (el) { io.observe(el); });
});
</script>
@endsection