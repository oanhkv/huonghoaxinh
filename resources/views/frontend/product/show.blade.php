@extends('frontend.layouts.app')

@section('title', $product->name)

@section('content')
<div class="product-detail-page py-3 py-lg-4">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-3 product-breadcrumb">
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}" class="text-decoration-none text-muted">Cửa hàng</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="text-decoration-none text-muted">{{ $product->category->name }}</a></li>
                <li class="breadcrumb-item active text-truncate" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show shadow-sm rounded-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- ============================== TOP: GALLERY + INFO ============================== -->
        <div class="row g-4 mb-4">
            <!-- Gallery cột trái -->
            <div class="col-lg-5">
                <div class="pdp-gallery">
                    <div class="pdp-main-image rounded-4 overflow-hidden shadow-sm position-relative">
                        @if($product->image)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="pdpMainImage">
                        @else
                            <div class="pdp-image-fallback"><i class="fas fa-spa fa-4x"></i></div>
                        @endif
                        @if($product->is_featured)
                            <span class="pdp-badge-hot"><i class="fas fa-fire me-1"></i>BÁN CHẠY</span>
                        @endif
                        @if($product->stock <= 0)
                            <span class="pdp-badge-soldout">Hết hàng</span>
                        @endif
                    </div>
                    <!-- Thumbnails (chỉ 1 ảnh: vẫn hiển thị, để dành mở rộng) -->
                    <div class="pdp-thumbs mt-3">
                        @if($product->image)
                            <button type="button" class="pdp-thumb active">
                                <img src="{{ $product->image_url }}" alt="thumb 1">
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info cột phải -->
            <div class="col-lg-7">
                <div class="pdp-info">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small">
                            <i class="fas fa-tag me-1"></i> {{ $product->category->name }}
                        </span>
                        <span class="text-muted small">Mã: <strong>#HHX{{ str_pad((string) $product->id, 4, '0', STR_PAD_LEFT) }}</strong></span>
                    </div>
                    <h1 class="pdp-title">{{ $product->name }}</h1>

                    <!-- Rating -->
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @php
                            $avgRating = $product->average_rating;
                            $reviewCount = $product->review_count;
                        @endphp
                        <div class="pdp-stars">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($avgRating) ? 'text-warning' : 'text-muted' }}"></i>
                            @endfor
                        </div>
                        <span class="fw-bold">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-muted small">|</span>
                        <a href="#reviews" class="text-muted small text-decoration-none">{{ $reviewCount }} đánh giá</a>
                        <span class="text-muted small">|</span>
                        <span class="text-muted small"><i class="fas fa-eye me-1"></i>Đã bán {{ random_int(50, 320) }}</span>
                    </div>

                    <!-- Mô tả ngắn -->
                    @if($product->description)
                        <p class="pdp-desc">{{ $product->description }}</p>
                    @endif

                    <!-- Giá -->
                    <div class="pdp-price-block">
                        <div class="d-flex align-items-baseline gap-3 flex-wrap">
                            <span class="pdp-price" id="priceDisplay">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            <span class="pdp-price-old">{{ number_format($product->price * 1.25, 0, ',', '.') }}₫</span>
                            <span class="pdp-discount">-20%</span>
                        </div>
                        <p class="text-muted small mb-0 mt-1"><i class="fas fa-info-circle me-1"></i>Đã bao gồm thuế. Phí ship tính theo khoảng cách.</p>
                        <input type="hidden" id="basePrice" value="{{ $product->price }}">
                    </div>

                    <!-- Tóm tắt thuộc tính: Màu / Nguyên liệu -->
                    <div class="pdp-attrs">
                        @if(! empty($product->colors))
                            <div class="pdp-attr-row">
                                <span class="pdp-attr-label">Màu sắc:</span>
                                <div class="pdp-attr-values">
                                    @foreach($product->colors as $color)
                                        <span class="pdp-color-tag">
                                            <span class="dot" style="background: {{ $colorDots[$color] ?? '#ccc' }};"></span>
                                            {{ $color }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if(! empty($product->materials))
                            <div class="pdp-attr-row">
                                <span class="pdp-attr-label">Nguyên liệu:</span>
                                <div class="pdp-attr-values">
                                    @foreach($product->materials as $material)
                                        <a href="{{ route('shop') }}?materials%5B%5D={{ urlencode($material) }}" class="pdp-material-tag">
                                            <i class="fas fa-leaf small me-1"></i>{{ $material }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="pdp-attr-row">
                            <span class="pdp-attr-label">Dịp phù hợp:</span>
                            <div class="pdp-attr-values">
                                @foreach($occasions as $occ)
                                    <span class="pdp-occasion-tag">{{ $occ }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Size -->
                    @if($product->sizes && count($product->sizes) > 0)
                        <div class="mb-3">
                            <span class="pdp-attr-label d-block mb-2">Kích cỡ:</span>
                            <div class="pdp-size-row">
                                <input type="hidden" id="selectedSize" value="">
                                @foreach($product->sizes as $size)
                                    <button type="button" class="pdp-size-btn" data-size="{{ $size['size'] }}" data-price="{{ $size['price'] }}" onclick="selectSize(this)">
                                        <span class="pdp-size-name">{{ $size['size'] }}</span>
                                        @if($size['price'] > 0)
                                            <span class="pdp-size-extra">+{{ number_format($size['price'], 0, ',', '.') }}₫</span>
                                        @endif
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Số lượng + Mua -->
                    <div class="pdp-buy-row">
                        <div class="pdp-qty">
                            <button type="button" id="decreaseQty" aria-label="Giảm"><i class="fas fa-minus"></i></button>
                            <input type="number" id="quantity" value="1" min="1" max="{{ max(1, (int) $product->stock) }}">
                            <button type="button" id="increaseQty" aria-label="Tăng"><i class="fas fa-plus"></i></button>
                        </div>
                        <button class="btn pdp-btn-primary flex-fill" id="orderNowBtn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-bag-shopping me-2"></i> Đặt hoa ngay
                        </button>
                        <button class="btn pdp-btn-secondary flex-fill" id="addToCartBtn" {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <i class="fas fa-cart-plus me-2"></i> Thêm vào giỏ
                        </button>
                    </div>

                    <div class="pdp-meta-row">
                        <span><i class="fas fa-boxes-stacked text-success me-1"></i>
                            @if($product->stock > 0)
                                Còn <strong>{{ $product->stock }}</strong> sản phẩm
                            @else
                                <strong class="text-danger">Hết hàng</strong>
                            @endif
                        </span>
                        <button class="pdp-meta-link" id="addToWishlistBtn">
                            <i class="far fa-heart me-1"></i> Yêu thích
                        </button>
                        <div class="dropdown">
                            <button class="pdp-meta-link" data-bs-toggle="dropdown">
                                <i class="fas fa-share-alt me-1"></i> Chia sẻ
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="#" onclick="shareOnFacebook(); return false;"><i class="fab fa-facebook text-primary me-2"></i>Facebook</a></li>
                                <li><a class="dropdown-item" href="#" onclick="shareOnTwitter(); return false;"><i class="fab fa-twitter text-info me-2"></i>Twitter</a></li>
                                <li><a class="dropdown-item" href="#" onclick="copyLink(); return false;"><i class="fas fa-link text-muted me-2"></i>Copy link</a></li>
                            </ul>
                        </div>
                    </div>

                    <!-- Dải dịch vụ -->
                    <div class="pdp-services">
                        <div class="pdp-svc"><i class="fas fa-truck-fast"></i><div><strong>Giao 2H</strong><span>Nội thành Hà Nội</span></div></div>
                        <div class="pdp-svc"><i class="fas fa-camera"></i><div><strong>Ảnh thật</strong><span>Trước khi giao</span></div></div>
                        <div class="pdp-svc"><i class="fas fa-gift"></i><div><strong>Tặng thiệp</strong><span>Viết tay miễn phí</span></div></div>
                        <div class="pdp-svc"><i class="fas fa-rotate-left"></i><div><strong>Hoàn lại</strong><span>Trong 2 giờ</span></div></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================== TABS ============================== -->
        <div class="row mb-5">
            <div class="col-12">
                <ul class="nav pdp-tabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabDesc" type="button">Mô tả</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabMeaning" type="button">Ý nghĩa bó hoa</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabIngredients" type="button">Nguyên liệu</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabCare" type="button">Cách bảo quản</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabReviews" type="button" id="reviews">Đánh giá ({{ $reviewCount }})</button></li>
                </ul>

                <div class="tab-content pdp-tab-body">
                    <!-- Mô tả -->
                    <div class="tab-pane fade show active" id="tabDesc">
                        <h4 class="pdp-section-title">Giới thiệu về {{ $product->name }}</h4>
                        <p>{{ $product->description }}</p>
                        <p>Mỗi mẫu hoa tại <strong>Hương Hoa Xinh</strong> đều được bó thủ công bởi đội ngũ florists giàu kinh nghiệm. Chúng tôi cam kết hoa tươi 100%, kiểm tra chất lượng trước khi giao và gửi ảnh thực tế đến khách hàng để bạn yên tâm. Sản phẩm hoàn thiện có thể tương đồng 80–90% so với ảnh do tính chất thủ công và mùa vụ của hoa.</p>
                        <ul class="pdp-list">
                            <li><strong>Thiết kế:</strong> Bó hoa hiện đại, phối tone hài hoà, phù hợp cả nam và nữ.</li>
                            <li><strong>Bao bì:</strong> Giấy gói cao cấp, kèm thiệp viết tay theo yêu cầu (miễn phí).</li>
                            <li><strong>Dịch vụ:</strong> Giao tận tay, gửi ảnh trước khi giao, hoàn tiền nếu hoa không đạt chất lượng.</li>
                        </ul>
                    </div>

                    <!-- Ý nghĩa -->
                    <div class="tab-pane fade" id="tabMeaning">
                        <h4 class="pdp-section-title">Ý nghĩa của bó hoa</h4>
                        <blockquote class="pdp-quote">{{ $meaning }}</blockquote>

                        @if(! empty($product->colors))
                            <h5 class="mt-4 mb-3">Ý nghĩa theo màu sắc</h5>
                            <div class="pdp-meaning-grid">
                                @php
                                    $colorMeanings = [
                                        'Đỏ' => ['icon' => 'fa-fire', 'desc' => 'Tình yêu nồng cháy, sự đam mê và khát vọng. Phù hợp cho lời tỏ tình mạnh mẽ.'],
                                        'Hồng' => ['icon' => 'fa-heart', 'desc' => 'Sự dịu dàng, ngọt ngào và tình cảm tinh tế. Lựa chọn an toàn cho mọi dịp.'],
                                        'Trắng' => ['icon' => 'fa-dove', 'desc' => 'Tinh khôi, thuần khiết và thành kính. Mang vẻ đẹp thanh lịch vượt thời gian.'],
                                        'Vàng' => ['icon' => 'fa-sun', 'desc' => 'Niềm vui, sự ấm áp, năng lượng tích cực và lời chúc phát đạt.'],
                                        'Cam' => ['icon' => 'fa-mug-hot', 'desc' => 'Sự nhiệt huyết, ấm áp và đam mê. Tone trẻ trung, năng động.'],
                                        'Tím' => ['icon' => 'fa-moon', 'desc' => 'Lãng mạn, thuỷ chung và sâu lắng. Phù hợp tặng người đặc biệt.'],
                                        'Xanh' => ['icon' => 'fa-leaf', 'desc' => 'Hy vọng, sự bình yên và phát triển bền vững. Hợp phong thuỷ mệnh Mộc.'],
                                        'Pastel' => ['icon' => 'fa-cloud', 'desc' => 'Nhẹ nhàng, mơ màng – tone hot trend của giới trẻ và phong cách Hàn Quốc.'],
                                        'Kem' => ['icon' => 'fa-feather', 'desc' => 'Sang trọng, cổ điển và đầy tinh tế. Lựa chọn lý tưởng cho dịp trang trọng.'],
                                        'Mix' => ['icon' => 'fa-palette', 'desc' => 'Sự đa dạng và niềm vui rực rỡ – như chính cuộc sống muôn màu.'],
                                    ];
                                @endphp
                                @foreach($product->colors as $color)
                                    @php $cm = $colorMeanings[$color] ?? ['icon' => 'fa-spa', 'desc' => 'Mang vẻ đẹp riêng và thông điệp tinh tế.']; @endphp
                                    <div class="pdp-meaning-card">
                                        <div class="pdp-meaning-icon" style="background: {{ $colorDots[$color] ?? '#ccc' }};"><i class="fas {{ $cm['icon'] }}"></i></div>
                                        <div>
                                            <strong>Tone {{ $color }}</strong>
                                            <p class="mb-0 text-muted small">{{ $cm['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <h5 class="mt-4 mb-3">Phù hợp tặng trong dịp nào?</h5>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($occasions as $occ)
                                <span class="pdp-occasion-tag pdp-occasion-tag-lg"><i class="far fa-calendar-check me-1"></i>{{ $occ }}</span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Nguyên liệu -->
                    <div class="tab-pane fade" id="tabIngredients">
                        <h4 class="pdp-section-title">Nguyên liệu sử dụng</h4>
                        @if(! empty($product->materials))
                            @php
                                $materialDetails = [
                                    'Hoa hồng' => 'Loài hoa biểu tượng của tình yêu và sự lãng mạn, độ bền 5–7 ngày khi được chăm sóc tốt.',
                                    'Hoa hồng kem' => 'Tone kem nhẹ nhàng, biểu tượng cho sự chân thành và lòng biết ơn.',
                                    'Hoa hồng môn' => 'Cánh đỏ rực, dáng tim – mang ý nghĩa tài lộc và may mắn.',
                                    'Hoa lan' => 'Đẳng cấp và sang trọng, thể hiện sự tinh tế của người tặng.',
                                    'Hoa lan hồ điệp' => 'Cao quý, biểu tượng của sự phú quý và thịnh vượng. Bền 2–3 tuần.',
                                    'Hoa lay ơn' => 'Tượng trưng cho sự kiên định và mạnh mẽ, thường dùng trong lễ trang trọng.',
                                    'Hoa cát tường' => 'Mang điều tốt lành, may mắn – không thể thiếu trong hoa khai trương.',
                                    'Hoa cẩm chướng' => 'Tình yêu của mẹ và lòng biết ơn. Bền và đẹp trên 7 ngày.',
                                    'Hoa cẩm tú cầu' => 'Vẻ đẹp tròn đầy, tượng trưng cho gia đình hạnh phúc và sự sung túc.',
                                    'Hoa cúc' => 'Trường thọ, may mắn và sự thuần khiết. Một trong những loài hoa bền nhất.',
                                    'Hoa cúc trắng' => 'Sự kính trọng và tiếc thương – chuyên dùng trong hoa kính viếng.',
                                    'Hoa hướng dương' => 'Lòng trung thành và sự ngưỡng mộ. Mang nắng tới mọi không gian.',
                                    'Hoa đồng tiền' => 'Tài lộc, làm ăn phát đạt – kinh điển trong kệ hoa khai trương.',
                                    'Baby' => 'Hoa baby trắng nhỏ xinh, thể hiện tình yêu thuần khiết và bất diệt.',
                                    'Lá xanh' => 'Lá monstera, lá bạc – tăng độ tươi mát và chiều sâu cho bó hoa.',
                                ];
                            @endphp
                            <div class="pdp-ingredient-list">
                                @foreach($product->materials as $material)
                                    <div class="pdp-ingredient-item">
                                        <div class="pdp-ingredient-icon"><i class="fas fa-spa"></i></div>
                                        <div class="flex-grow-1">
                                            <strong>{{ $material }}</strong>
                                            <p class="mb-1 text-muted small">{{ $materialDetails[$material] ?? 'Loại hoa cao cấp được tuyển chọn từ vườn hoa uy tín.' }}</p>
                                            <a href="{{ route('shop') }}?materials%5B%5D={{ urlencode($material) }}" class="small text-success text-decoration-none">
                                                Xem các sản phẩm cùng loại <i class="fas fa-arrow-right ms-1"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="alert alert-info mt-3 border-0 rounded-3">
                                <i class="fas fa-circle-info me-2"></i>
                                Tuỳ thuộc vào mùa vụ và nguồn cung, một số nguyên liệu có thể được thay thế bằng loại tương đương để đảm bảo độ tươi và thẩm mỹ.
                            </div>
                        @else
                            <p class="text-muted">Đang cập nhật nguyên liệu chi tiết.</p>
                        @endif

                        <h5 class="mt-4 mb-3">Thông số kỹ thuật</h5>
                        <table class="pdp-spec-table">
                            <tr><td>SKU</td><td>HHX{{ str_pad((string) $product->id, 4, '0', STR_PAD_LEFT) }}</td></tr>
                            <tr><td>Danh mục</td><td>{{ $product->category->name }}</td></tr>
                            <tr><td>Kích cỡ có sẵn</td><td>{{ $product->sizes ? collect($product->sizes)->pluck('size')->implode(' • ') : 'Tiêu chuẩn' }}</td></tr>
                            <tr><td>Tone màu</td><td>{{ ! empty($product->colors) ? implode(', ', $product->colors) : 'Đa dạng' }}</td></tr>
                            <tr><td>Số bông trung bình</td><td>{{ random_int(20, 50) }} bông</td></tr>
                            <tr><td>Độ bền</td><td>5 – 7 ngày (chăm sóc đúng cách)</td></tr>
                        </table>
                    </div>

                    <!-- Bảo quản -->
                    <div class="tab-pane fade" id="tabCare">
                        <h4 class="pdp-section-title">Hướng dẫn bảo quản</h4>
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="pdp-care-item">
                                    <div class="pdp-care-icon"><i class="fas fa-tint"></i></div>
                                    <h6>1. Đổi nước hằng ngày</h6>
                                    <p class="mb-0 small text-muted">Thay nước sạch mỗi ngày. Có thể thêm 1 thìa đường hoặc 1 viên aspirin để hoa tươi lâu hơn 30%.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="pdp-care-item">
                                    <div class="pdp-care-icon"><i class="fas fa-cut"></i></div>
                                    <h6>2. Cắt gốc theo góc 45°</h6>
                                    <p class="mb-0 small text-muted">Mỗi ngày cắt thân hoa thêm 1–2cm theo góc nghiêng để tăng diện tích hút nước.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="pdp-care-item">
                                    <div class="pdp-care-icon"><i class="fas fa-temperature-low"></i></div>
                                    <h6>3. Đặt nơi thoáng mát</h6>
                                    <p class="mb-0 small text-muted">Tránh ánh nắng trực tiếp, gió điều hoà và nơi gần trái cây chín (sinh ethylene).</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="pdp-care-item">
                                    <div class="pdp-care-icon"><i class="fas fa-leaf"></i></div>
                                    <h6>4. Tỉa lá ngập nước</h6>
                                    <p class="mb-0 small text-muted">Loại bỏ lá ngập trong nước và cánh hoa héo để tránh vi khuẩn lây lan.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Đánh giá -->
                    <div class="tab-pane fade" id="tabReviews">
                        <h4 class="pdp-section-title">{{ $reviewCount }} đánh giá cho {{ $product->name }}</h4>
                        <div class="row g-4">
                            <div class="col-lg-7">
                                @if($product->visibleReviews()->count() > 0)
                                    @foreach($product->visibleReviews as $review)
                                        <div class="pdp-review-item">
                                            <div class="pdp-review-avatar">{{ Str::substr($review->user->name ?? 'K', 0, 1) }}</div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                    <strong>{{ $review->user->name }}</strong>
                                                    <small class="text-muted">{{ $review->created_at->format('d/m/Y H:i') }}</small>
                                                </div>
                                                <div class="my-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }} small"></i>
                                                    @endfor
                                                </div>
                                                <p class="mb-0 text-muted">{{ $review->comment }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-center py-5 text-muted">
                                        <i class="far fa-comments fa-3x mb-3 opacity-50"></i>
                                        <p>Chưa có đánh giá nào — hãy là người đầu tiên!</p>
                                    </div>
                                @endif
                            </div>

                            <div class="col-lg-5">
                                <div class="pdp-review-form">
                                    <h5 class="fw-bold mb-3">Để lại đánh giá</h5>
                                    @auth
                                        @if($userReview)
                                            <div class="alert alert-success border-0 mb-0">
                                                <strong class="d-block mb-1">Bạn đã đánh giá {{ $userReview->rating }}/5</strong>
                                                <small>{{ $userReview->comment }}</small>
                                            </div>
                                        @elseif($canReview)
                                            <form action="{{ route('reviews.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Đánh giá của bạn</label>
                                                    <div class="rating-input" id="ratingStars">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star star-icon" data-rating="{{ $i }}"></i>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" id="ratingValue" name="rating" value="5" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small fw-semibold">Nội dung</label>
                                                    <textarea name="comment" rows="4" class="form-control rounded-3" placeholder="Chia sẻ trải nghiệm của bạn..." required>{{ old('comment') }}</textarea>
                                                </div>
                                                <button type="submit" class="btn pdp-btn-primary w-100">Gửi đánh giá</button>
                                            </form>
                                        @else
                                            <div class="alert alert-info border-0 mb-0 small">
                                                <i class="fas fa-shopping-bag me-2"></i>
                                                Mua và hoàn tất đơn hàng để có thể đánh giá sản phẩm.
                                            </div>
                                        @endif
                                    @else
                                        <div class="alert alert-info border-0 mb-0 small">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <a href="{{ route('login') }}" class="alert-link">Đăng nhập</a> để để lại đánh giá.
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================== RELATED ============================== -->
        @if($relatedProducts->count() > 0)
            <div class="mb-5">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h4 class="fw-bold mb-0"><i class="fas fa-spa text-success me-2"></i>Sản phẩm cùng danh mục</h4>
                    <a href="{{ route('shop') }}?category={{ $product->category->slug }}" class="text-success small text-decoration-none">Xem tất cả <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
                <div class="row g-3">
                    @foreach($relatedProducts as $related)
                        <div class="col-6 col-md-3">
                            <article class="pdp-related-card">
                                <a href="{{ route('product.show', $related->slug) }}" class="text-decoration-none">
                                    <div class="pdp-related-thumb">
                                        @if($related->image)
                                            <img src="{{ $related->image_url }}" alt="{{ $related->name }}" loading="lazy">
                                        @else
                                            <div class="pdp-image-fallback"><i class="fas fa-spa fa-2x"></i></div>
                                        @endif
                                    </div>
                                    <div class="pdp-related-body">
                                        <div class="text-muted small">{{ $related->category?->name }}</div>
                                        <h6 class="pdp-related-title">{{ Str::limit($related->name, 48) }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-success fw-bold">{{ number_format($related->price, 0, ',', '.') }}₫</span>
                                            <span class="pdp-related-arrow"><i class="fas fa-arrow-right"></i></span>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .product-detail-page { background: #f8fafc; }
    .product-detail-page .breadcrumb-item + .breadcrumb-item::before { color: #cbd5e1; }

    /* Gallery */
    .pdp-main-image { background: #fff; aspect-ratio: 1/1; }
    .pdp-main-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .pdp-main-image:hover img { transform: scale(1.04); }
    .pdp-image-fallback { width: 100%; height: 100%; display: grid; place-items: center; color: #cbd5e1; background: linear-gradient(135deg, #f1f5f9, #fff); }
    .pdp-badge-hot, .pdp-badge-soldout {
        position: absolute; top: 14px; left: 14px;
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: #fff; padding: 5px 12px; border-radius: 999px;
        font-size: 0.72rem; font-weight: 700; letter-spacing: 0.04em;
    }
    .pdp-badge-soldout { left: auto; right: 14px; background: rgba(15, 23, 42, 0.85); }
    .pdp-thumbs { display: flex; gap: 10px; }
    .pdp-thumb {
        width: 70px; height: 70px; border-radius: 12px; overflow: hidden;
        border: 2px solid transparent; padding: 0; background: #fff;
        transition: border-color 0.2s ease;
    }
    .pdp-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .pdp-thumb.active { border-color: #198754; }

    /* Info */
    .pdp-info { background: #fff; border-radius: 18px; padding: 26px 28px; box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05); }
    .pdp-title { font-size: 1.6rem; font-weight: 800; color: #0f172a; line-height: 1.3; margin-bottom: 0.6rem; }
    .pdp-stars { font-size: 0.9rem; }
    .pdp-desc { color: #64748b; line-height: 1.65; margin-bottom: 1rem; }

    .pdp-price-block { background: linear-gradient(135deg, #fff5f8 0%, #f0fdf4 100%); border-radius: 14px; padding: 14px 18px; margin-bottom: 1.2rem; border: 1px dashed rgba(25, 135, 84, 0.25); }
    .pdp-price { font-size: 2rem; font-weight: 800; color: #198754; }
    .pdp-price-old { font-size: 1rem; color: #94a3b8; text-decoration: line-through; }
    .pdp-discount { background: #dc3545; color: #fff; padding: 3px 10px; border-radius: 999px; font-size: 0.78rem; font-weight: 700; }

    /* Attributes */
    .pdp-attrs { margin-bottom: 1.2rem; }
    .pdp-attr-row { display: flex; flex-wrap: wrap; align-items: flex-start; gap: 8px; margin-bottom: 10px; }
    .pdp-attr-label { font-weight: 600; color: #0f172a; min-width: 100px; flex-shrink: 0; padding-top: 6px; font-size: 0.88rem; }
    .pdp-attr-values { display: flex; flex-wrap: wrap; gap: 6px; }
    .pdp-color-tag { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 999px; background: #f1f5f9; font-size: 0.82rem; color: #334155; }
    .pdp-color-tag .dot { width: 14px; height: 14px; border-radius: 50%; border: 1.5px solid rgba(0,0,0,0.06); }
    .pdp-material-tag {
        display: inline-flex; align-items: center; padding: 4px 12px;
        border-radius: 999px; background: rgba(25, 135, 84, 0.1);
        color: #198754; font-size: 0.82rem; text-decoration: none;
        border: 1px solid rgba(25, 135, 84, 0.2); transition: all 0.2s ease;
    }
    .pdp-material-tag:hover { background: #198754; color: #fff; transform: translateY(-1px); }
    .pdp-occasion-tag { padding: 4px 12px; border-radius: 999px; background: #fff5f8; color: #d63384; font-size: 0.78rem; border: 1px solid rgba(214, 51, 132, 0.2); }
    .pdp-occasion-tag-lg { padding: 8px 16px; font-size: 0.9rem; }

    /* Size */
    .pdp-size-row { display: flex; flex-wrap: wrap; gap: 8px; }
    .pdp-size-btn {
        background: #fff; border: 1.5px solid #e2e8f0;
        padding: 8px 16px; border-radius: 12px; cursor: pointer;
        display: flex; flex-direction: column; align-items: center;
        line-height: 1.2; transition: all 0.2s ease; min-width: 90px;
    }
    .pdp-size-btn:hover { border-color: #198754; transform: translateY(-2px); }
    .pdp-size-btn.active { border-color: #198754; background: rgba(25, 135, 84, 0.1); color: #198754; box-shadow: 0 6px 14px rgba(25, 135, 84, 0.18); }
    .pdp-size-name { font-weight: 700; font-size: 0.92rem; }
    .pdp-size-extra { font-size: 0.72rem; color: #64748b; }
    .pdp-size-btn.active .pdp-size-extra { color: #198754; }

    /* Buy row */
    .pdp-buy-row {
        display: flex; flex-wrap: wrap; gap: 10px; margin: 18px 0 10px;
        align-items: stretch;
    }
    .pdp-qty {
        display: flex; align-items: center; border: 1.5px solid #e2e8f0;
        border-radius: 12px; overflow: hidden; background: #fff;
    }
    .pdp-qty button { width: 38px; border: 0; background: #f8fafc; color: #475569; }
    .pdp-qty button:hover { background: #198754; color: #fff; }
    .pdp-qty input { width: 50px; border: 0; text-align: center; font-weight: 700; -moz-appearance: textfield; }
    .pdp-qty input::-webkit-outer-spin-button, .pdp-qty input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .pdp-btn-primary, .pdp-btn-secondary {
        border-radius: 12px; font-weight: 700; padding: 10px 18px;
        transition: all 0.2s ease; border: 0;
    }
    .pdp-btn-primary {
        background: linear-gradient(135deg, #198754 0%, #20a464 100%);
        color: #fff; box-shadow: 0 8px 18px rgba(25, 135, 84, 0.25);
    }
    .pdp-btn-primary:hover:not(:disabled) { transform: translateY(-2px); box-shadow: 0 14px 26px rgba(25, 135, 84, 0.32); color: #fff; }
    .pdp-btn-primary:disabled { opacity: 0.5; cursor: not-allowed; }
    .pdp-btn-secondary {
        background: #fff; color: #198754;
        border: 1.5px solid #198754;
    }
    .pdp-btn-secondary:hover:not(:disabled) { background: rgba(25, 135, 84, 0.08); transform: translateY(-2px); color: #198754; }

    /* Meta row */
    .pdp-meta-row {
        display: flex; align-items: center; flex-wrap: wrap; gap: 16px;
        padding: 12px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1rem; font-size: 0.88rem; color: #475569;
    }
    .pdp-meta-link {
        background: none; border: 0; color: #475569; padding: 0;
        font-size: 0.88rem; cursor: pointer; transition: color 0.2s ease;
    }
    .pdp-meta-link:hover { color: #d63384; }

    /* Services */
    .pdp-services {
        display: grid; grid-template-columns: repeat(4, 1fr);
        gap: 8px; padding: 12px; background: #f8fafc;
        border-radius: 12px;
    }
    .pdp-svc { display: flex; align-items: center; gap: 10px; }
    .pdp-svc i { font-size: 1.3rem; color: #198754; flex-shrink: 0; }
    .pdp-svc strong { display: block; font-size: 0.82rem; color: #0f172a; }
    .pdp-svc span { display: block; font-size: 0.72rem; color: #64748b; }

    /* Tabs */
    .pdp-tabs {
        gap: 8px; margin-bottom: 0; padding: 0; flex-wrap: wrap;
        border-bottom: 2px solid #e2e8f0;
    }
    .pdp-tabs .nav-link {
        border: 0; border-radius: 12px 12px 0 0;
        padding: 12px 20px; color: #475569; font-weight: 600;
        background: transparent; transition: all 0.2s ease;
        border-bottom: 3px solid transparent; margin-bottom: -2px;
    }
    .pdp-tabs .nav-link:hover { color: #198754; }
    .pdp-tabs .nav-link.active {
        color: #198754; background: #fff;
        border-bottom-color: #198754;
    }
    .pdp-tab-body {
        background: #fff; border-radius: 0 18px 18px 18px;
        padding: 28px 32px; box-shadow: 0 6px 22px rgba(15, 23, 42, 0.05);
    }
    .pdp-section-title { font-size: 1.3rem; font-weight: 700; color: #0f172a; margin-bottom: 1rem; padding-bottom: 0.6rem; border-bottom: 2px solid #f0fdf4; }
    .pdp-list { padding-left: 1.2rem; }
    .pdp-list li { margin-bottom: 0.5rem; color: #475569; }
    .pdp-list li::marker { color: #198754; }

    .pdp-quote {
        background: linear-gradient(135deg, #fff5f8 0%, #f0fdf4 100%);
        border-left: 4px solid #198754; padding: 16px 20px;
        border-radius: 0 12px 12px 0; font-style: italic;
        color: #334155; margin-bottom: 1.2rem;
    }

    .pdp-meaning-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; }
    .pdp-meaning-card { display: flex; align-items: flex-start; gap: 12px; background: #f8fafc; padding: 14px; border-radius: 14px; border: 1px solid #e2e8f0; }
    .pdp-meaning-icon { width: 44px; height: 44px; border-radius: 12px; display: grid; place-items: center; color: #fff; font-size: 1.1rem; flex-shrink: 0; box-shadow: 0 6px 14px rgba(0,0,0,0.08); }

    .pdp-ingredient-list { display: grid; gap: 12px; }
    .pdp-ingredient-item { display: flex; align-items: flex-start; gap: 14px; background: #fff; padding: 16px; border-radius: 14px; border: 1px solid #e2e8f0; transition: all 0.2s ease; }
    .pdp-ingredient-item:hover { border-color: #198754; transform: translateX(4px); box-shadow: 0 6px 14px rgba(25, 135, 84, 0.08); }
    .pdp-ingredient-icon { width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, rgba(25,135,84,0.15), rgba(214,51,132,0.12)); color: #198754; display: grid; place-items: center; font-size: 1.1rem; flex-shrink: 0; }

    .pdp-spec-table { width: 100%; }
    .pdp-spec-table tr { border-bottom: 1px solid #f1f5f9; }
    .pdp-spec-table tr:last-child { border-bottom: 0; }
    .pdp-spec-table td { padding: 10px 4px; font-size: 0.92rem; }
    .pdp-spec-table td:first-child { color: #94a3b8; width: 40%; }
    .pdp-spec-table td:last-child { font-weight: 600; color: #0f172a; }

    .pdp-care-item { padding: 18px; border-radius: 14px; background: #f8fafc; border: 1px solid #e2e8f0; height: 100%; }
    .pdp-care-icon { width: 48px; height: 48px; border-radius: 12px; background: #fff; color: #198754; display: grid; place-items: center; font-size: 1.2rem; margin-bottom: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); }

    .pdp-review-item { display: flex; gap: 14px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 14px; margin-bottom: 12px; background: #fff; }
    .pdp-review-avatar { width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #198754, #20a464); color: #fff; display: grid; place-items: center; font-weight: 700; flex-shrink: 0; }

    .pdp-review-form { background: linear-gradient(135deg, #f0fdf4, #fff); padding: 22px; border-radius: 16px; border: 1px solid #d1fadf; }
    .rating-input { display: flex; gap: 8px; }
    .star-icon { font-size: 22px; color: #ddd; cursor: pointer; transition: color 0.15s ease; }
    .star-icon.active, .star-icon.hover { color: #ffc107; }

    /* Related */
    .pdp-related-card { background: #fff; border-radius: 14px; overflow: hidden; box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05); transition: transform 0.3s ease, box-shadow 0.3s ease; height: 100%; }
    .pdp-related-card:hover { transform: translateY(-6px); box-shadow: 0 16px 36px rgba(25, 135, 84, 0.15); }
    .pdp-related-thumb { height: 200px; overflow: hidden; }
    .pdp-related-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .pdp-related-card:hover .pdp-related-thumb img { transform: scale(1.07); }
    .pdp-related-body { padding: 12px 14px 14px; }
    .pdp-related-title { font-size: 0.92rem; font-weight: 700; color: #0f172a; margin: 4px 0 8px; min-height: 2.6em; line-height: 1.4;
        display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .pdp-related-arrow { width: 26px; height: 26px; border-radius: 50%; background: #f1f5f9; color: #198754; display: grid; place-items: center; font-size: 0.7rem; transition: all 0.25s ease; }
    .pdp-related-card:hover .pdp-related-arrow { background: #198754; color: #fff; transform: rotate(-30deg); }

    @media (max-width: 991.98px) {
        .pdp-info { padding: 20px; }
        .pdp-services { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 575.98px) {
        .pdp-title { font-size: 1.25rem; }
        .pdp-price { font-size: 1.5rem; }
        .pdp-tabs .nav-link { padding: 10px 14px; font-size: 0.85rem; }
        .pdp-tab-body { padding: 18px; }
        .pdp-attr-label { min-width: auto; }
        .pdp-buy-row { flex-direction: column; }
        .pdp-buy-row .pdp-qty { align-self: stretch; justify-content: center; }
    }
</style>

<script>
    const basePrice = {{ $product->price }};
    const productSizes = @json($product->sizes ?? []);

    function selectSize(btn) {
        document.querySelectorAll('.pdp-size-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const sizeData = { size: btn.dataset.size, price: parseInt(btn.dataset.price) || 0 };
        document.getElementById('selectedSize').value = JSON.stringify(sizeData);
        updatePrice();
    }
    function updatePrice() {
        const v = document.getElementById('selectedSize').value;
        let p = basePrice;
        if (v) p = basePrice + (JSON.parse(v).price || 0);
        document.getElementById('priceDisplay').textContent = new Intl.NumberFormat('vi-VN').format(p) + '₫';
    }

    document.getElementById('increaseQty').addEventListener('click', () => {
        const q = document.getElementById('quantity'); const max = {{ max(1, (int) $product->stock) }};
        if (parseInt(q.value) < max) q.value = parseInt(q.value) + 1;
    });
    document.getElementById('decreaseQty').addEventListener('click', () => {
        const q = document.getElementById('quantity');
        if (parseInt(q.value) > 1) q.value = parseInt(q.value) - 1;
    });

    function getSelectedVariant() {
        const v = document.getElementById('selectedSize')?.value || '';
        @if($product->sizes && count($product->sizes) > 0)
        if (!v) { alert('Vui lòng chọn kích cỡ trước khi tiếp tục!'); return null; }
        @endif
        let unitPrice = basePrice, variant = '';
        if (v) { const d = JSON.parse(v); unitPrice = basePrice + (d.price || 0); variant = d.size || ''; }
        return { unitPrice, variant };
    }
    function postAddToCartThen(nextUrl = null) {
        const quantity = parseInt(document.getElementById('quantity').value || '1', 10) || 1;
        const sel = getSelectedVariant(); if (!sel) return;
        const payload = { product_id: {{ $product->id }}, quantity, unit_price: sel.unitPrice, variant: sel.variant };
        fetch('/cart/add', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: JSON.stringify(payload)
        })
        .then(r => r.json().then(data => ({ ok: r.ok, data })))
        .then(({ ok, data }) => {
            if (!ok || !data.success) { alert(data.message || 'Không thể thêm vào giỏ hàng.'); return; }
            if (typeof updateCartCount === 'function') updateCartCount();
            if (typeof showNotification === 'function') showNotification(data.message || 'Đã thêm vào giỏ hàng', 'success');
            if (nextUrl) window.location.href = nextUrl;
        }).catch(() => alert('Có lỗi khi thêm vào giỏ hàng. Vui lòng thử lại.'));
    }
    document.getElementById('addToCartBtn').addEventListener('click', () => postAddToCartThen(null));
    document.getElementById('orderNowBtn').addEventListener('click', () => postAddToCartThen('{{ route('checkout.index') }}'));
    document.getElementById('addToWishlistBtn').addEventListener('click', () => { if (typeof toggleWishlist === 'function') toggleWishlist({{ $product->id }}); });

    (function () {
        const wrap = document.getElementById('ratingStars'); const val = document.getElementById('ratingValue');
        if (!wrap || !val) return;
        const stars = wrap.querySelectorAll('.star-icon');
        const paint = (n) => stars.forEach(s => s.classList.toggle('active', parseInt(s.dataset.rating) <= n));
        paint(parseInt(val.value) || 0);
        stars.forEach(s => {
            s.addEventListener('click', () => { val.value = s.dataset.rating; paint(parseInt(s.dataset.rating)); });
            s.addEventListener('mouseover', () => paint(parseInt(s.dataset.rating)));
        });
        wrap.addEventListener('mouseleave', () => paint(parseInt(val.value) || 0));
    })();

    function shareOnFacebook() { window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(window.location.href)}`, '_blank', 'width=600,height=400'); }
    function shareOnTwitter() { window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent('{{ $product->name }}')}`, '_blank', 'width=600,height=400'); }
    function copyLink() { navigator.clipboard.writeText(window.location.href).then(() => alert('Đã copy link sản phẩm!')); }
</script>
@endsection
