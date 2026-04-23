@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-3 product-page-compact">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-2 product-breadcrumb">
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

    <div class="product-hero mb-3 p-3 p-lg-4 rounded-4">
        <div class="row g-3 align-items-center">
            <div class="col-lg-8">
                <span class="badge rounded-pill text-bg-light mb-1">{{ $product->category->name }}</span>
                <h1 class="fw-bold mb-1 product-hero-title">{{ $product->name }}</h1>
                <p class="mb-0 text-muted small">{{ Str::limit($product->description, 115) }}</p>
            </div>
            <div class="col-lg-4">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <span class="chip"><i class="fas fa-shield-heart me-1"></i>Cam kết chất lượng</span>
                    <span class="chip"><i class="fas fa-truck-fast me-1"></i>Giao nhanh Hà Nội</span>
                    <span class="chip"><i class="fas fa-bag-shopping me-1"></i>Kiểm tra trước khi nhận</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Detail -->
    <div class="row mb-4 g-3">
        <!-- Product Image -->
        <div class="col-lg-4 mb-3 mb-lg-0">
            <div class="bg-light rounded-4 overflow-hidden shadow-sm border product-main-image">
                @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                         class="img-fluid w-100 product-detail-image">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center product-detail-image-placeholder">
                        <i class="fas fa-image fa-4x text-muted"></i>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Info -->
        <div class="col-lg-8">
            <!-- Rating & Reviews -->
            <div class="d-flex align-items-center mb-2 p-2 rounded-4 border bg-white shadow-sm compact-card">
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
            <div class="bg-light p-3 rounded-4 mb-2 border compact-card">
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

            <!-- Product Highlights -->
            <div class="product-highlights mb-3">
                <div class="highlights-grid">
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="far fa-clock"></i></div>
                        <div class="highlight-text">GIAO HOA TẬN NƠI<br>2H</div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="fas fa-camera"></i></div>
                        <div class="highlight-text">GỬI ẢNH TRƯỚC<br>KHI GIAO</div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="fas fa-gift"></i></div>
                        <div class="highlight-text">TẶNG KÈM THIỆP /<br>BANNER</div>
                    </div>
                    <div class="highlight-item">
                        <div class="highlight-icon"><i class="far fa-file-alt"></i></div>
                        <div class="highlight-text">HOÁ ĐƠN CÔNG TY<br>(VAT +8%)</div>
                    </div>
                </div>

            </div>

            <!-- Size Selection -->
            @if($product->sizes && count($product->sizes) > 0)
                <div class="mb-3">
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

            <!-- Add to Cart & Order Now (style like Trạm Hoa) -->
            <div class="p-3 p-lg-4 rounded-4 border bg-white shadow-sm mb-4">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div class="small text-muted">
                        <span class="me-3"><i class="fas fa-barcode me-1"></i>Mã: <strong>#{{ $product->id }}</strong></span>
                        <span><i class="fas fa-boxes-stacked me-1"></i>
                            @if($product->stock > 0)
                                <strong class="text-success">Còn hàng</strong>
                            @else
                                <strong class="text-danger">Hết hàng</strong>
                            @endif
                        </span>
                    </div>
                    <div class="small text-muted">
                        <i class="fas fa-circle-info me-1"></i>Hoa tươi thủ công, thành phẩm có thể tương đồng 80–90% so với ảnh.
                    </div>
                </div>

                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label for="quantity" class="form-label small fw-semibold">Số lượng</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary" type="button" id="decreaseQty" aria-label="Giảm số lượng">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" id="quantity" class="form-control text-center"
                                   value="1" min="1" max="{{ max(1, (int) $product->stock) }}">
                            <button class="btn btn-outline-secondary" type="button" id="increaseQty" aria-label="Tăng số lượng">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="d-grid d-md-flex gap-2">
                            <button class="btn btn-success btn-lg flex-fill" id="orderNowBtn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-bag-shopping me-2"></i>Đặt hoa
                            </button>
                            <button class="btn btn-outline-success btn-lg flex-fill" id="addToCartBtn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                            </button>
                        </div>
                        <div class="form-text mt-2">
                            Chọn “Đặt hoa” để đi thẳng đến thanh toán. “Thêm vào giỏ” để mua tiếp sản phẩm khác.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wishlist & Share -->
            <div class="d-flex gap-2 mb-2">
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
            <div class="border-top pt-2 mt-2 compact-shipping">
                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <p class="small text-muted mb-1"><i class="fas fa-truck text-success me-2"></i>Giao hàng</p>
                        <p class="small">Giao nhanh khu vực Hà Nội &amp; lân cận — phí ship theo khoảng cách từ cửa hàng.</p>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <p class="small text-muted mb-1"><i class="fas fa-sync text-success me-2"></i>Hoàn lại</p>
                        <p class="small">Chấp nhận hoàn lại trong 2 giờ</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs: Description / Info / Reviews -->
    <div class="row mb-5">
        <div class="col-12">
            <ul class="nav product-detail-tabs justify-content-center gap-2 mb-4" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
                        Mô tả
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="information-tab" data-bs-toggle="tab" data-bs-target="#information" type="button" role="tab">
                        Thông tin
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                        Đánh giá ({{ $reviewCount }})
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content product-detail-tab-content">
                <!-- Description Tab -->
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-4">
                        <p class="mb-3">
                            <strong>{{ $product->name }}</strong>
                            {{ $product->description }}
                        </p>
                        <p class="mb-0 text-muted">
                            Tham khảo thêm các mẫu hoa tại HUONGHOAXINH để lựa chọn thiết kế phù hợp cho từng dịp đặc biệt.
                        </p>
                    </div>
                </div>

                <!-- Information Tab -->
                <div class="tab-pane fade" id="information" role="tabpanel">
                    <div class="p-4">
                        <h5 class="mb-3">Thông tin sản phẩm</h5>
                        <table class="table table-sm align-middle mb-0">
                            <tr>
                                <td class="fw-semibold text-muted" style="width: 180px;">SKU</td>
                                <td>#{{ $product->id }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Danh mục</td>
                                <td>{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Giá bán</td>
                                <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Tồn kho</td>
                                <td>{{ $product->stock }} sản phẩm</td>
                            </tr>
                            <tr>
                                <td class="fw-semibold text-muted">Kích cỡ</td>
                                <td>
                                    @if($product->sizes && count($product->sizes) > 0)
                                        {{ collect($product->sizes)->pluck('size')->implode(', ') }}
                                    @else
                                        Đang cập nhật
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-4">
                        <div class="row g-4">
                            <div class="col-lg-7">
                                <h4 class="reviews-title mb-4">{{ $reviewCount }} đánh giá cho {{ $product->name }}</h4>

                                @if($product->visibleReviews()->count() > 0)
                                    @foreach($product->visibleReviews as $review)
                                        <div class="review-item mb-3">
                                            <div class="d-flex gap-3">
                                                <div class="review-avatar">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                                                    <div class="d-flex gap-2 align-items-center mb-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                                        @endfor
                                                        <span class="text-muted small">{{ $review->rating }}/5</span>
                                                    </div>
                                                    <p class="mb-1 fst-italic text-muted">{{ $review->comment }}</p>
                                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-muted py-4">Chưa có đánh giá nào cho sản phẩm này.</p>
                                @endif
                            </div>

                            <div class="col-lg-5">
                                <div class="review-form-wrap p-4">
                                    <h4 class="mb-3">Thêm một đánh giá</h4>

                                    @auth
                                        @if($userReview)
                                            <div class="alert alert-success border-0 shadow-sm mb-0">
                                                <h6 class="fw-bold mb-2">Bạn đã đánh giá sản phẩm này</h6>
                                                <div class="mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $userReview->rating ? 'text-warning' : 'text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                                <p class="mb-0 text-muted">{{ $userReview->comment }}</p>
                                            </div>
                                        @elseif($canReview)
                                            <p class="small text-muted mb-3">
                                                Chỉ khách đã mua sản phẩm trong đơn hàng <strong>đã thanh toán</strong> hoặc <strong>đã giao</strong> mới gửi đánh giá được.
                                            </p>
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">Đánh giá của bạn <span class="text-danger">*</span></label>
                                                    <div class="rating-input" id="ratingStars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star star-icon" data-rating="{{ $i }}" style="cursor: pointer; font-size: 24px; color: #ddd; transition: color 0.2s;"></i>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" id="ratingValue" name="rating" value="5" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="reviewComment" class="form-label fw-semibold">Nội dung đánh giá <span class="text-danger">*</span></label>
                                                    <textarea class="form-control @error('comment') is-invalid @enderror" id="reviewComment" name="comment" rows="5"
                                                              placeholder="Chia sẻ trải nghiệm của bạn về sản phẩm..." required>{{ old('comment') }}</textarea>
                                                    @error('comment')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="row g-2 mb-3">
                                                    <div class="col-sm-6">
                                                        <label class="form-label fw-semibold">Tên <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" value="{{ auth()->user()->email }}" readonly>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-success px-4 py-2 fw-bold">
                                                    GỬI ĐI
                                                </button>
                                            </form>
                                        @else
                                            <div class="alert alert-info border-0 mb-0" role="alert">
                                                <i class="fas fa-shopping-bag me-2"></i>
                                                Mua và hoàn tất đơn hàng (đã thanh toán hoặc đã nhận hàng) để có thể đánh giá sản phẩm này.
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-info mb-0" role="alert">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <a href="{{ route('login') }}">Đăng nhập</a> để để lại đánh giá cho sản phẩm này.
                                        </div>
                                    @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        <img src="{{ $related->image_url }}" alt="{{ $related->name }}" 
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

    function getSelectedVariant() {
        const selectedSize = document.getElementById('selectedSize')?.value || '';
        @if($product->sizes && count($product->sizes) > 0)
            if (!selectedSize) {
                alert('Vui lòng chọn kích cỡ trước khi tiếp tục!');
                return null;
            }
        @endif

        let unitPrice = basePrice;
        let variant = '';
        if (selectedSize) {
            const sizeData = JSON.parse(selectedSize);
            unitPrice = basePrice + (sizeData.price || 0);
            variant = sizeData.size || '';
        }

        return { unitPrice, variant };
    }

    function postAddToCartThen(nextUrl = null) {
        const quantity = parseInt(document.getElementById('quantity').value || '1', 10) || 1;
        const selected = getSelectedVariant();
        if (!selected) return;

        const payload = {
            product_id: {{ $product->id }},
            quantity: quantity,
            unit_price: selected.unitPrice,
            variant: selected.variant,
        };

        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content
            },
            body: JSON.stringify(payload)
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) {
                alert(data.message || 'Không thể thêm vào giỏ hàng.');
                return;
            }
            if (typeof updateCartCount === 'function') updateCartCount();
            if (typeof showNotification === 'function') showNotification(data.message || 'Đã thêm vào giỏ hàng', 'success');
            if (nextUrl) window.location.href = nextUrl;
        })
        .catch(() => alert('Có lỗi khi thêm vào giỏ hàng. Vui lòng thử lại.'));
    }

    // Add to cart
    document.getElementById('addToCartBtn').addEventListener('click', function() {
        postAddToCartThen(null);
    });

    // Order now (go checkout)
    document.getElementById('orderNowBtn').addEventListener('click', function() {
        postAddToCartThen('{{ route('checkout.index') }}');
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
    .product-page-compact {
        max-width: 1280px;
    }
    .product-detail-image {
        height: 100%;
        max-height: 460px;
        object-fit: cover;
    }
    .product-detail-image-placeholder {
        height: 460px;
    }

    .product-hero {
        background: linear-gradient(135deg, rgba(25, 135, 84, 0.1), rgba(13, 110, 253, 0.08));
        border: 1px solid rgba(15, 23, 42, 0.08);
    }
    .product-hero-title {
        font-size: clamp(1.35rem, 1.9vw, 2rem);
        line-height: 1.25;
    }
    .product-hero .chip {
        display: inline-flex;
        align-items: center;
        padding: 0.35rem 0.65rem;
        border-radius: 999px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        font-size: 0.74rem;
        color: #334155;
    }
    .product-main-image {
        transition: transform 0.35s ease, box-shadow 0.35s ease;
    }
    .product-main-image:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 44px rgba(15, 23, 42, 0.12) !important;
    }

    #orderNowBtn.btn-success {
        box-shadow: 0 14px 30px rgba(25, 135, 84, 0.22);
    }
    #orderNowBtn.btn-success:hover {
        box-shadow: 0 18px 40px rgba(25, 135, 84, 0.28);
        transform: translateY(-1px);
    }
    #addToCartBtn.btn-outline-success:hover {
        transform: translateY(-1px);
    }
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

    .product-detail-tabs .nav-link {
        border: 1px solid rgba(15, 23, 42, 0.12);
        border-radius: 999px;
        padding: 0.45rem 1rem;
        color: #334155;
        background: #fff;
        font-weight: 500;
    }

    .product-detail-tabs .nav-link:hover {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
    }

    .product-detail-tabs .nav-link.active {
        color: #0f766e;
        border-color: rgba(15, 118, 110, 0.35);
        background: rgba(15, 118, 110, 0.08);
    }

    .product-detail-tab-content {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 1rem;
        background: #fff;
    }

    .highlights-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        border-bottom: 1px dashed #d1d5db;
    }

    .highlight-item {
        text-align: center;
        padding: 0.65rem 0.45rem;
        border-right: 1px dashed #d1d5db;
    }

    .highlight-item:last-child {
        border-right: 0;
    }

    .highlight-icon {
        color: #0f766e;
        font-size: 1.1rem;
        margin-bottom: 0.35rem;
    }

    .highlight-text {
        font-size: 0.82rem;
        font-weight: 700;
        line-height: 1.25;
        color: #0f172a;
    }

    .reviews-title {
        color: #0f766e;
        font-weight: 700;
        font-size: 1.8rem;
    }

    .review-item {
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 0.6rem;
        background: #fafafa;
        padding: 1rem;
    }

    .review-avatar {
        width: 58px;
        height: 58px;
        border-radius: 50%;
        background: #e5e7eb;
        color: #9ca3af;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.7rem;
        flex-shrink: 0;
    }

    .review-form-wrap {
        border: 2px solid rgba(15, 118, 110, 0.8);
        border-radius: 0.4rem;
        background: #fff;
    }

    @media (min-width: 992px) {
        .product-breadcrumb {
            display: none;
        }

        .product-page-compact {
            padding-top: 0.35rem !important;
        }

        .product-hero {
            margin-bottom: 0.55rem !important;
            padding: 0.65rem 0.95rem !important;
        }

        .product-hero-title {
            font-size: clamp(1.12rem, 1.25vw, 1.42rem);
            margin-bottom: 0.12rem !important;
            line-height: 1.18;
        }

        .product-hero p.small {
            font-size: 0.74rem !important;
            line-height: 1.25;
        }

        .product-detail-image {
            max-height: 315px;
        }

        .product-detail-image-placeholder {
            height: 315px;
        }

        .compact-card {
            border-radius: 0.85rem !important;
            margin-bottom: 0.45rem !important;
            padding-top: 0.55rem !important;
            padding-bottom: 0.55rem !important;
        }

        #priceDisplay {
            font-size: 1.75rem;
            line-height: 1.1;
        }

        .product-highlights {
            margin-bottom: 0.45rem !important;
        }

        .highlight-item {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .highlight-icon {
            font-size: 1rem;
            margin-bottom: 0.2rem;
        }

        .highlight-text {
            font-size: 0.74rem;
            line-height: 1.15;
        }

        .form-label.small {
            margin-bottom: 0.2rem;
        }

        .size-selector {
            gap: 6px;
            margin-bottom: 4px;
        }

        .size-btn {
            padding: 3px 10px;
            font-size: 11px;
        }

        .compact-cart-btn {
            padding: 0.42rem 0.7rem;
            font-size: 0.94rem;
        }

        #quantity {
            padding-top: 0.35rem;
            padding-bottom: 0.35rem;
        }

        #decreaseQty,
        #increaseQty {
            padding-top: 0.28rem;
            padding-bottom: 0.28rem;
        }

        .compact-shipping {
            margin-top: 0.45rem !important;
            padding-top: 0.55rem !important;
        }

        .compact-shipping .small {
            margin-bottom: 0.2rem !important;
            line-height: 1.3;
        }

        .compact-shipping .row > div {
            margin-bottom: 0.25rem !important;
        }
    }

    @media (max-width: 576px) {
        .product-page-compact {
            padding-top: 0.75rem !important;
        }

        .product-hero-title {
            font-size: 1.25rem;
        }

        .size-btn {
            font-size: 12px;
            padding: 5px 12px;
        }

        .highlights-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            border-bottom: 0;
        }

        .highlight-item {
            border-bottom: 1px dashed #d1d5db;
        }

        .highlight-item:nth-child(2n) {
            border-right: 0;
        }

        .highlight-text {
            font-size: 0.82rem;
        }
    }
</style>
@endsection
