@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none">Cửa hàng</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="text-decoration-none">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Product Detail -->
    <div class="row mb-5">
        <!-- Product Image -->
        <div class="col-lg-5 mb-4 mb-lg-0">
            <div class="bg-light rounded-3 overflow-hidden">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                         class="img-fluid w-100" style="max-height: 500px; object-fit: cover;">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center" style="height: 500px;">
                        <i class="fas fa-image fa-4x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-7">
            <!-- Category Badge -->
            <span class="badge bg-success mb-3">{{ $product->category->name }}</span>

            <!-- Product Name -->
            <h1 class="mb-3">{{ $product->name }}</h1>

            <!-- Rating & Reviews -->
            <div class="d-flex align-items-center mb-4">
                <div class="d-flex align-items-center me-4">
                    @php
                        $avgRating = $product->average_rating;
                        $reviewCount = $product->review_count;
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                    <span class="ms-2 fw-bold">{{ number_format($avgRating, 1) }}</span>
                </div>
                <span class="text-muted">({{ $reviewCount }} đánh giá)</span>
            </div>

            <!-- Price Section -->
            <div class="bg-light p-4 rounded-3 mb-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Giá bán</p>
                        <h2 class="text-success mb-0" id="priceDisplay">{{ number_format($product->price, 0, ',', '.') }} ₫</h2>
                        <input type="hidden" id="basePrice" value="{{ $product->price }}">
                        <input type="hidden" id="selectedSizePrice" value="0">
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Tình trạng kho</p>
                        @if($product->stock > 0)
                            <span class="badge bg-success p-2">Còn {{ $product->stock }} sản phẩm</span>
                        @else
                            <span class="badge bg-danger p-2">Hết hàng</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="mb-4">
                <h5 class="mb-3">Mô tả sản phẩm</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

            <!-- Size Selection -->
            @if($product->sizes && count($product->sizes) > 0)
                <div class="mb-4">
                    <label class="form-label small fw-semibold mb-2">
                        <i class="fas fa-ruler-combined me-2 text-success"></i>Kích cỡ
                    </label>
                    <div class="size-selector d-flex flex-wrap gap-2">
                        <input type="hidden" id="selectedSize" value="">
                        @foreach($product->sizes as $index => $size)
                            <button type="button" class="size-btn btn btn-sm btn-outline-success rounded-pill" 
                                    data-size="{{ $size['size'] }}" 
                                    data-price="{{ $size['price'] }}"
                                    onclick="selectSize(this)">
                                {{ $size['size'] }}
                                @if($size['price'] > 0)
                                    <span class="text-muted ms-1">+{{ number_format($size['price'], 0, ',', '.') }}₫</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Add to Cart & Actions -->
            <div class="d-flex gap-3 mb-4">
                <div style="width: 150px;">
                    <label for="quantity" class="form-label small">Số lượng</label>
                    <div class="input-group">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="decreaseQty">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="quantity" class="form-control form-control-sm text-center" 
                               value="1" min="1" max="{{ $product->stock }}">
                        <button class="btn btn-outline-secondary btn-sm" type="button" id="increaseQty">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="flex-grow-1">
                    <label class="form-label small d-block">&nbsp;</label>
                    <button class="btn btn-success btn-lg w-100" id="addToCartBtn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                        <i class="fas fa-shopping-cart me-2"></i>Thêm vào giỏ hàng
                    </button>
                </div>
            </div>

            <!-- Wishlist & Share -->
            <div class="d-flex gap-2">
                <button class="btn btn-outline-secondary flex-grow-1" id="addToWishlistBtn">
                    <i class="far fa-heart me-2"></i>Thêm vào yêu thích
                </button>
                <button class="btn btn-outline-secondary" data-bs-toggle="dropdown">
                    <i class="fas fa-share-alt"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#" onclick="shareOnFacebook()"><i class="fab fa-facebook me-2"></i>Facebook</a></li>
                    <li><a class="dropdown-item" href="#" onclick="shareOnTwitter()"><i class="fab fa-twitter me-2"></i>Twitter</a></li>
                    <li><a class="dropdown-item" href="#" onclick="copyLink()"><i class="fas fa-link me-2"></i>Copy link</a></li>
                </ul>
            </div>

            <!-- Shipping Info -->
            <div class="border-top pt-4 mt-4">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <p class="small text-muted mb-1"><i class="fas fa-truck text-success me-2"></i>Giao hàng</p>
                        <p class="small">Giao nhanh khu vực Hà Nội &amp; lân cận — phí ship theo khoảng cách từ cửa hàng.</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <p class="small text-muted mb-1"><i class="fas fa-sync text-success me-2"></i>Hoàn lại</p>
                        <p class="small">Chấp nhận hoàn lại trong 7 ngày</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs: Description & Reviews -->
    <div class="row mb-5">
        <div class="col-12">
            <ul class="nav nav-tabs mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                        <i class="fas fa-info-circle me-2"></i>Chi tiết
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        <i class="fas fa-comments me-2"></i>Đánh giá ({{ $reviewCount }})
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-4 bg-light rounded-3">
                        <h5 class="mb-3">Thông tin sản phẩm</h5>
                        <p>{{ $product->description }}</p>
                        <h5 class="mt-4 mb-3">Chi tiết kỹ thuật</h5>
                        <table class="table table-sm">
                            <tr>
                                <td class="fw-bold">SKU</td>
                                <td>#{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Danh mục</td>
                                <td>{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Giá</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                            </tr>
                            <tr>
                                <td class="fw-bold">Kho</td>
                                <td>{{ $product->stock }} sản phẩm</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-4">
                        @if($product->visibleReviews()->count() > 0)
                            <!-- Reviews List -->
                            <div class="mb-5">
                                <h5 class="mb-4">Đánh giá từ khách hàng</h5>
                                @foreach($product->visibleReviews as $review)
                                    <div class="border-bottom pb-4 mb-4">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div>
                                                <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                <div class="d-flex gap-2 align-items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                                    @endfor
                                                    <span class="text-muted small">{{ $review->rating }} sao</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <p class="text-muted mb-0">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-center text-muted py-5">Chưa có đánh giá nào cho sản phẩm này.</p>
                        @endif

                        @auth
                            <hr class="my-5">
                            @if($userReview)
                                <div class="alert alert-success border-0 shadow-sm">
                                    <h6 class="fw-bold mb-2">Bạn đã đánh giá sản phẩm này</h6>
                                    <div class="mb-1">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $userReview->rating ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="mb-0 text-muted">{{ $userReview->comment }}</p>
                                </div>
                            @elseif($canReview)
                                <h5 class="mb-4">Để lại đánh giá của bạn</h5>
                                <p class="small text-muted mb-3">Chỉ khách đã mua sản phẩm trong đơn hàng <strong>đã thanh toán</strong> hoặc <strong>đã giao</strong> mới gửi đánh giá được.</p>
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <div class="mb-3">
                                        <label class="form-label">Xếp hạng của bạn</label>
                                        <div class="rating-input" id="ratingStars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star star-icon" data-rating="{{ $i }}" style="cursor: pointer; font-size: 24px; color: #ddd; transition: color 0.2s;"></i>
                                            @endfor
                                        </div>
                                        <input type="hidden" id="ratingValue" name="rating" value="5" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="reviewComment" class="form-label">Nhận xét của bạn</label>
                                        <textarea class="form-control @error('comment') is-invalid @enderror" id="reviewComment" name="comment" rows="4"
                                                  placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." required>{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-info border-0" role="alert">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Mua và hoàn tất đơn hàng (đã thanh toán hoặc đã nhận hàng) để có thể đánh giá sản phẩm này.
                                </div>
                            @endif
                        @else
                            <div class="alert alert-info" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <a href="{{ route('login') }}">Đăng nhập</a> để để lại đánh giá cho sản phẩm này.
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h4 class="mb-4">
                    <i class="fas fa-box-open me-2 text-success"></i>Sản phẩm liên quan
                </h4>
                <div class="row g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col-lg-3 col-md-6">
                            <div class="card h-100 shadow-sm product-card">
                                <!-- Product Image -->
                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                    @if($related->image)
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" 
                                             class="card-img-top h-100" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    @endif

                                    <!-- Overlay with Actions -->
                                    <div class="product-overlay">
                                        <a href="{{ route('product.show', $related->slug) }}" class="btn btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm" onclick="addToCart({{ $related->id }})">
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <a href="{{ route('product.show', $related->slug) }}" class="text-decoration-none text-dark">
                                        <h6 class="card-title mb-2">{{ Str::limit($related->name, 40) }}</h6>
                                    </a>

                                    <!-- Rating -->
                                    <div class="d-flex align-items-center mb-2 small">
                                        @php $relatedAvgRating = $related->average_rating; @endphp
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= round($relatedAvgRating) ? 'text-warning' : 'text-muted' }}"></i>
                                        @endfor
                                        <span class="ms-1">({{ $related->review_count }})</span>
                                    </div>

                                    <!-- Price -->
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h6 text-success mb-0">{{ number_format($related->price, 0, ',', '.') }} ₫</span>
                                        @if($related->stock > 0)
                                            <span class="badge bg-success">Còn hàng</span>
                                        @else
                                            <span class="badge bg-danger">Hết hàng</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    // Base product data
    const basePrice = {{ $product->price }};
    const productSizes = @json($product->sizes ?? []);

    // Select size and update price
    function selectSize(btn) {
        // Remove active state from all size buttons
        document.querySelectorAll('.size-btn').forEach(b => {
            b.classList.remove('active');
            b.classList.remove('btn-success');
            b.classList.add('btn-outline-success');
        });
        
        // Add active state to clicked button
        btn.classList.add('active');
        btn.classList.remove('btn-outline-success');
        btn.classList.add('btn-success');
        
        // Get size data
        const sizeData = {
            size: btn.dataset.size,
            price: parseInt(btn.dataset.price) || 0
        };
        
        // Store selected size
        document.getElementById('selectedSize').value = JSON.stringify(sizeData);
        
        // Update price
        updatePrice();
    }

    // Update price based on selected size
    function updatePrice() {
        const selectedSizeStr = document.getElementById('selectedSize').value;
        let totalPrice = basePrice;
        
        if (selectedSizeStr) {
            const sizeData = JSON.parse(selectedSizeStr);
            totalPrice = basePrice + (sizeData.price || 0);
        }
        
        const priceDisplay = document.getElementById('priceDisplay');
        priceDisplay.textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0,
        }).format(totalPrice).replace('₫', '₫');
    }

    // Quantity controls
    document.getElementById('increaseQty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        const max = {{ $product->stock }};
        if(parseInt(qtyInput.value) < max) {
            qtyInput.value = parseInt(qtyInput.value) + 1;
        }
    });

    document.getElementById('decreaseQty').addEventListener('click', function() {
        const qtyInput = document.getElementById('quantity');
        if(parseInt(qtyInput.value) > 1) {
            qtyInput.value = parseInt(qtyInput.value) - 1;
        }
    });

    // Add to cart
    document.getElementById('addToCartBtn').addEventListener('click', function() {
        const quantity = parseInt(document.getElementById('quantity').value);
        const selectedSize = document.getElementById('selectedSize').value;
        
        // Check if size is required
        @if($product->sizes && count($product->sizes) > 0)
            if (!selectedSize) {
                alert('Vui lòng chọn kích cỡ trước khi thêm vào giỏ hàng!');
                return;
            }
        @endif

        let unitPrice = basePrice;
        let variant = '';
        if (selectedSize) {
            const sizeData = JSON.parse(selectedSize);
            unitPrice = basePrice + (sizeData.price || 0);
            variant = sizeData.size || '';
        }

        addToCart({{ $product->id }}, quantity, unitPrice, variant);
    });

    // Add to wishlist
    document.getElementById('addToWishlistBtn').addEventListener('click', function() {
        toggleWishlist({{ $product->id }});
    });

    (function () {
        const ratingWrap = document.getElementById('ratingStars');
        const ratingVal = document.getElementById('ratingValue');
        if (!ratingWrap || !ratingVal) return;

        ratingWrap.querySelectorAll('.star-icon').forEach(function (star) {
            star.addEventListener('click', function () {
                const rating = this.getAttribute('data-rating');
                ratingVal.value = rating;
                ratingWrap.querySelectorAll('.star-icon').forEach(function (s) {
                    s.style.color = parseInt(s.getAttribute('data-rating'), 10) <= parseInt(rating, 10) ? '#FFC107' : '#ddd';
                });
            });
            star.addEventListener('mouseover', function () {
                const rating = this.getAttribute('data-rating');
                ratingWrap.querySelectorAll('.star-icon').forEach(function (s) {
                    s.style.color = parseInt(s.getAttribute('data-rating'), 10) <= parseInt(rating, 10) ? '#FFC107' : '#ddd';
                });
            });
        });

        ratingWrap.addEventListener('mouseleave', function () {
            const selectedRating = ratingVal.value;
            ratingWrap.querySelectorAll('.star-icon').forEach(function (s) {
                if (selectedRating && parseInt(s.getAttribute('data-rating'), 10) <= parseInt(selectedRating, 10)) {
                    s.style.color = '#FFC107';
                } else {
                    s.style.color = '#ddd';
                }
            });
        });
    })();

    function shareOnFacebook() {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${window.location.href}`, '_blank', 'width=600,height=400');
    }

    function shareOnTwitter() {
        window.open(`https://twitter.com/intent/tweet?url=${window.location.href}&text={{ $product->name }}`, '_blank', 'width=600,height=400');
    }

    function copyLink() {
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Đã copy link sản phẩm!');
        });
    }
</script>

<style>
    .rating-input {
        display: flex;
        gap: 8px;
    }
    
    .star-icon:hover,
    .star-icon.active {
        color: #FFC107 !important;
    }

    /* Size Selector Styles */
    .size-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 12px;
    }

    .size-btn {
        font-size: 13px;
        padding: 6px 14px;
        border-width: 1.5px;
        white-space: nowrap;
        transition: all 0.2s ease;
        font-weight: 500;
        border-radius: 20px;
    }

    .size-btn.btn-outline-success {
        color: #198754;
        border-color: #198754;
    }

    .size-btn.btn-outline-success:hover {
        background-color: #f0fbf4;
        color: #0f5132;
        border-color: #0f5132;
        transform: translateY(-1px);
    }

    .size-btn.btn-success {
        background-color: #198754;
        border-color: #198754;
        color: white;
        box-shadow: 0 4px 12px rgba(25, 135, 84, 0.25);
        transform: translateY(-2px);
    }

    .size-btn.btn-success:hover {
        background-color: #157347;
        border-color: #157347;
    }

    .size-btn span.text-muted {
        font-size: 11px;
        opacity: 0.7;
        font-weight: 400;
    }

    .size-btn.btn-success span.text-muted {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    @media (max-width: 576px) {
        .size-btn {
            font-size: 12px;
            padding: 5px 12px;
        }
    }
</style>
@endsection
