@extends('frontend.layouts.app')

@section('title', 'Cửa hàng - Hương Hoa Xinh')

@section('content')
<div class="container py-5">

    <div class="row">
        <!-- Sidebar Danh mục -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Danh mục Hoa</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="{{ route('shop') }}" class="text-decoration-none {{ !request('category') ? 'fw-bold text-success' : '' }}">
                                Tất cả sản phẩm
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li class="mb-3">
                                <a href="{{ route('shop') }}?category={{ $category->slug }}" 
                                   class="text-decoration-none {{ request('category') == $category->slug ? 'fw-bold text-success' : '' }}">
                                    {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="col-lg-9">
            <!-- Bộ lọc & Sắp xếp -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
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
                            <select name="price_range" class="form-select" onchange="this.form.submit()" form="filterForm">
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
            <div class="row">
                @forelse($products as $product)
                <div class="col-md-4 col-6 mb-4">
                    <div class="product-card card h-100 border-0 shadow-sm position-relative" style="cursor:pointer;" onclick="window.location='{{ route('product.show', $product->slug) }}'">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $product->name }}">
                        @else
                            <img src="https://via.placeholder.com/300x220" class="card-img-top" alt="{{ $product->name }}">
                        @endif

                        <div class="product-overlay" onclick="event.stopPropagation();">
                            <div class="d-flex justify-content-center gap-2">
                                <form method="POST" action="{{ route('favorites.store') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button type="button" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Yêu thích" onclick="event.stopPropagation(); this.closest('form').submit();">
                                        <i class="fas fa-heart text-danger"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('cart.store') }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <button class="btn btn-light btn-sm rounded-circle shadow-sm" title="Thêm vào giỏ hàng" type="button" onclick="event.stopPropagation(); this.closest('form').submit();">
                                        <i class="fas fa-shopping-cart text-success"></i>
                                    </button>
                                </form>
                                <a href="{{ route('product.show', $product->slug) }}" class="btn btn-light btn-sm rounded-circle shadow-sm" title="Xem chi tiết" onclick="event.stopPropagation();">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body text-center">
                            <h6 class="card-title">
                                <a href="{{ route('product.show', $product->slug) }}" class="text-decoration-none text-dark">
                                    {{ $product->name }}
                                </a>
                            </h6>
                            <p class="text-danger fw-bold mb-2">{{ number_format($product->price) }} đ</p>
                            <a href="{{ route('product.show', $product->slug) }}" class="btn btn-outline-success btn-sm w-100">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Không tìm thấy sản phẩm nào phù hợp.</p>
                </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>
@endsection