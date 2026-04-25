@extends('frontend.layouts.app')

@section('title', 'Cửa hàng - Hương Hoa Xinh')

@section('content')
<div class="shop-page py-4 py-lg-5">
    <div class="container">
        <!-- Heading -->
        {{-- <div class="shop-heading text-center mb-4">
            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2 mb-2">
                <i class="fas fa-store me-1"></i> Cửa hàng
            </span>
            <h1 class="fw-bold mb-1">Tất cả sản phẩm</h1>
            <p class="text-muted small mb-0">Hoa tươi mỗi ngày — chọn theo dịp, màu sắc và nguyên liệu yêu thích.</p>
        </div> --}}

        <form id="shopFilterForm" method="GET" action="{{ route('shop') }}">
            <input type="hidden" name="search" value="{{ request('search') }}">

            <div class="row g-4">
                <!-- Sidebar lọc -->
                <aside class="col-lg-3">
                    <div class="shop-filter-card p-4 rounded-4 shadow-sm position-sticky" style="top: 95px;">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="fw-bold mb-0"><i class="fas fa-sliders-h me-2 text-success"></i> Bộ lọc</h5>
                            @if(request()->hasAny(['category','price_range','colors','materials','sort']))
                                <a href="{{ route('shop') }}" class="small text-danger text-decoration-none">
                                    <i class="fas fa-times-circle me-1"></i> Xoá
                                </a>
                            @endif
                        </div>

                        <!-- Danh mục -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-title">Danh mục</h6>
                            <div class="filter-radio-list">
                                <label class="filter-radio">
                                    <input type="radio" name="category" value="" {{ ! request('category') ? 'checked' : '' }} onchange="document.getElementById('shopFilterForm').submit()">
                                    <span>Tất cả</span>
                                </label>
                                @foreach($categories as $category)
                                    <label class="filter-radio">
                                        <input type="radio" name="category" value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'checked' : '' }} onchange="document.getElementById('shopFilterForm').submit()">
                                        <span>{{ $category->name }} <small class="text-muted">({{ $category->products_count }})</small></span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Giá -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-title">Khoảng giá</h6>
                            <div class="filter-radio-list">
                                @foreach([
                                    '' => 'Tất cả mức giá',
                                    'under_500' => 'Dưới 500.000₫',
                                    '500_1000' => '500.000₫ – 1.000.000₫',
                                    'over_1000' => 'Trên 1.000.000₫',
                                ] as $val => $label)
                                    <label class="filter-radio">
                                        <input type="radio" name="price_range" value="{{ $val }}" {{ (string) request('price_range', '') === (string) $val ? 'checked' : '' }} onchange="document.getElementById('shopFilterForm').submit()">
                                        <span>{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Màu sắc -->
                        <div class="filter-group mb-4">
                            <h6 class="filter-title">Màu sắc</h6>
                            <div class="color-swatch-grid">
                                @foreach($colorOptions as $color => $css)
                                    @php $checked = in_array($color, $selectedColors, true); @endphp
                                    <label class="color-swatch" title="{{ $color }}">
                                        <input type="checkbox" name="colors[]" value="{{ $color }}" {{ $checked ? 'checked' : '' }} onchange="document.getElementById('shopFilterForm').submit()">
                                        <span class="swatch-dot" style="background: {{ $css }};"></span>
                                        <span class="swatch-label">{{ $color }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Nguyên liệu -->
                        <div class="filter-group mb-2">
                            <h6 class="filter-title">Nguyên liệu</h6>
                            <div class="material-chip-grid">
                                @foreach($materialOptions as $material)
                                    @php $checked = in_array($material, $selectedMaterials, true); @endphp
                                    <label class="material-chip {{ $checked ? 'is-checked' : '' }}">
                                        <input type="checkbox" name="materials[]" value="{{ $material }}" {{ $checked ? 'checked' : '' }} onchange="document.getElementById('shopFilterForm').submit()">
                                        <span><i class="fas fa-leaf me-1 small"></i>{{ $material }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </aside>

                <!-- Danh sách sản phẩm -->
                <div class="col-lg-9">
                    <div class="shop-toolbar d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                        <div>
                            <span class="text-muted small">Hiển thị</span>
                            <strong class="mx-1">{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                            <span class="text-muted small">trên {{ $products->total() }} sản phẩm</span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="text-muted small mb-0">Sắp xếp:</label>
                            <select name="sort" class="form-select form-select-sm shop-sort-select" onchange="document.getElementById('shopFilterForm').submit()">
                                <option value="newest" {{ request('sort','newest') === 'newest' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Nổi bật</option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>Giá giảm dần</option>
                            </select>
                        </div>
                    </div>

                    @if(count($selectedColors) || count($selectedMaterials))
                        <div class="active-filter-chips mb-3">
                            <span class="text-muted small me-1">Đang lọc:</span>
                            @foreach($selectedColors as $c)
                                <span class="active-chip">
                                    <span class="dot" style="background: {{ $colorOptions[$c] ?? '#ccc' }};"></span> {{ $c }}
                                </span>
                            @endforeach
                            @foreach($selectedMaterials as $m)
                                <span class="active-chip"><i class="fas fa-leaf small me-1"></i>{{ $m }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($products->count() > 0)
                        <div class="row g-3">
                            @foreach($products as $product)
                                <div class="col-6 col-md-4">
                                    <article class="product-card h-100">
                                        <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none">
                                            <div class="product-thumb">
                                                @if($product->image)
                                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                                                @else
                                                    <div class="product-thumb-fallback"><i class="fas fa-spa"></i></div>
                                                @endif
                                                @if($product->is_featured)
                                                    <span class="badge-featured"><i class="fas fa-star me-1"></i>HOT</span>
                                                @endif
                                                @if($product->stock <= 0)
                                                    <span class="badge-soldout">Hết hàng</span>
                                                @endif
                                            </div>
                                            <div class="product-info">
                                                <div class="product-cat-tag">{{ $product->category?->name ?? 'Hoa tươi' }}</div>
                                                <h3 class="product-name">{{ $product->name }}</h3>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <span class="product-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                    <span class="product-action">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="empty-state text-center py-5 rounded-4">
                            <i class="fas fa-search fa-3x text-muted opacity-50 mb-3"></i>
                            <h5 class="fw-bold">Không tìm thấy sản phẩm phù hợp</h5>
                            <p class="text-muted">Thử bỏ bớt bộ lọc hoặc xem toàn bộ sản phẩm.</p>
                            <a href="{{ route('shop') }}" class="btn btn-success rounded-pill px-4">Xem tất cả</a>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .shop-page { background: linear-gradient(180deg, #f8fafc 0%, #fff 200px); }
    .shop-filter-card { background: #fff; border: 1px solid rgba(15, 23, 42, 0.05); }

    .filter-title {
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.7rem;
        padding-bottom: 0.4rem;
        border-bottom: 2px solid #f1f5f9;
    }

    /* Radio list */
    .filter-radio-list { display: flex; flex-direction: column; gap: 0.4rem; }
    .filter-radio {
        display: flex; align-items: center; gap: 0.5rem;
        padding: 0.4rem 0.55rem; border-radius: 8px; cursor: pointer;
        transition: background 0.15s ease;
        font-size: 0.88rem; color: #475569; margin-bottom: 0;
    }
    .filter-radio:hover { background: #f1f5f9; }
    .filter-radio input { accent-color: #198754; cursor: pointer; }
    .filter-radio input:checked + span { color: #198754; font-weight: 600; }

    /* Color swatch */
    .color-swatch-grid {
        display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;
    }
    .color-swatch {
        display: flex; align-items: center; gap: 8px;
        padding: 6px 8px; border-radius: 8px; cursor: pointer;
        transition: background 0.15s ease, transform 0.15s ease;
        font-size: 0.82rem; color: #475569;
        border: 1.5px solid transparent;
        margin: 0;
    }
    .color-swatch:hover { background: #f1f5f9; transform: translateY(-1px); }
    .color-swatch input { display: none; }
    .color-swatch input:checked + .swatch-dot {
        box-shadow: 0 0 0 2px #fff, 0 0 0 4px #198754;
    }
    .color-swatch input:checked ~ .swatch-label { font-weight: 700; color: #198754; }
    .swatch-dot {
        width: 22px; height: 22px; border-radius: 50%;
        flex-shrink: 0; border: 1.5px solid rgba(15, 23, 42, 0.1);
        transition: box-shadow 0.2s ease;
    }

    /* Material chips */
    .material-chip-grid {
        display: flex; flex-wrap: wrap; gap: 6px;
    }
    .material-chip {
        cursor: pointer; margin: 0;
        font-size: 0.78rem; color: #475569;
        padding: 6px 12px; border-radius: 999px;
        background: #f1f5f9; border: 1.5px solid transparent;
        transition: all 0.2s ease;
    }
    .material-chip:hover { background: #e2e8f0; }
    .material-chip input { display: none; }
    .material-chip.is-checked {
        background: rgba(25, 135, 84, 0.12);
        color: #198754;
        border-color: rgba(25, 135, 84, 0.3);
        font-weight: 600;
    }

    /* Toolbar */
    .shop-toolbar {
        background: #fff; padding: 12px 16px;
        border-radius: 12px; border: 1px solid #f1f5f9;
    }
    .shop-sort-select { min-width: 160px; border-radius: 999px; }

    /* Active filter chips */
    .active-filter-chips { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; }
    .active-chip {
        background: #fff; border: 1px solid #e2e8f0;
        padding: 4px 12px; border-radius: 999px;
        font-size: 0.78rem; display: inline-flex; align-items: center; gap: 6px;
        color: #475569;
    }
    .active-chip .dot { width: 12px; height: 12px; border-radius: 50%; }

    /* Product card */
    .product-card {
        background: #fff; border-radius: 14px; overflow: hidden;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid rgba(15, 23, 42, 0.04);
    }
    .product-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 16px 36px rgba(25, 135, 84, 0.15);
    }
    .product-thumb {
        position: relative; height: 220px; overflow: hidden; background: #f8fafc;
    }
    .product-thumb img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.5s ease;
    }
    .product-card:hover .product-thumb img { transform: scale(1.07); }
    .product-thumb-fallback {
        width: 100%; height: 100%; display: grid; place-items: center;
        font-size: 2.5rem; color: #cbd5e1;
        background: linear-gradient(135deg, #f1f5f9, #fff);
    }
    .badge-featured, .badge-soldout {
        position: absolute; top: 10px; left: 10px;
        background: linear-gradient(135deg, #dc3545, #ff6b6b);
        color: #fff; font-size: 0.68rem; font-weight: 700;
        padding: 4px 10px; border-radius: 999px;
        text-transform: uppercase; letter-spacing: 0.05em;
    }
    .badge-soldout {
        left: auto; right: 10px;
        background: rgba(15, 23, 42, 0.85);
    }
    .product-info { padding: 14px 16px 16px; }
    .product-cat-tag {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #94a3b8;
        margin-bottom: 4px;
    }
    .product-name {
        font-size: 0.95rem; font-weight: 700; line-height: 1.35;
        color: #0f172a; min-height: 2.7em; margin-bottom: 8px;
        display: -webkit-box; -webkit-line-clamp: 2;
        -webkit-box-orient: vertical; overflow: hidden;
    }
    .product-price { color: #198754; font-weight: 800; font-size: 1rem; }
    .product-action {
        width: 30px; height: 30px; border-radius: 50%;
        background: #f1f5f9; color: #198754;
        display: grid; place-items: center;
        transition: all 0.25s ease;
        font-size: 0.78rem;
    }
    .product-card:hover .product-action {
        background: #198754; color: #fff;
        transform: rotate(-30deg);
    }

    /* Empty */
    .empty-state {
        background: #fff;
        border: 2px dashed #e2e8f0;
    }

    @media (max-width: 991.98px) {
        .shop-filter-card { position: static !important; }
    }
    @media (max-width: 575.98px) {
        .product-thumb { height: 180px; }
        .product-name { font-size: 0.85rem; }
    }
</style>
@endsection
