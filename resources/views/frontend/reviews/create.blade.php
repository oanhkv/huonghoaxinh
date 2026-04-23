@extends('frontend.layouts.app')

@section('title', 'Đánh giá sản phẩm - Hương Hoa Xinh')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.history') }}" class="text-decoration-none">Lịch sử đơn hàng</a></li>
            <li class="breadcrumb-item active" aria-current="page">Đánh giá sản phẩm</li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Sản phẩm của bạn</h5>
                    <div class="d-flex gap-3">
                        <div class="flex-shrink-0" style="width: 88px; height: 88px;">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-100 h-100 rounded" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 rounded bg-light d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <div class="small text-muted mb-1">{{ $product->category->name }}</div>
                            <div class="text-success fw-bold">{{ number_format($product->price, 0, ',', '.') }} ₫</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-star me-2 text-success"></i>Viết đánh giá của bạn</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Đánh giá của bạn <span class="text-danger">*</span></label>
                            <div id="ratingStars" class="d-flex gap-2 fs-4">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star star-icon" data-rating="{{ $i }}" style="cursor:pointer; color:#ddd;"></i>
                                @endfor
                            </div>
                            <input type="hidden" id="ratingValue" name="rating" value="{{ old('rating', 5) }}">
                            @error('rating')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="comment" class="form-label fw-semibold">Nội dung đánh giá <span class="text-danger">*</span></label>
                            <textarea
                                id="comment"
                                name="comment"
                                rows="5"
                                class="form-control @error('comment') is-invalid @enderror"
                                placeholder="Hãy chia sẻ trải nghiệm của bạn về sản phẩm..."
                                required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane me-2"></i>Gửi đánh giá
                            </button>
                            <a href="{{ route('product.show', $product->slug) }}#reviews" class="btn btn-outline-secondary">
                                Quay lại sản phẩm
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const ratingWrap = document.getElementById('ratingStars');
        const ratingVal = document.getElementById('ratingValue');
        if (!ratingWrap || !ratingVal) return;

        function renderStars(activeRating) {
            ratingWrap.querySelectorAll('.star-icon').forEach(function (star) {
                const current = parseInt(star.getAttribute('data-rating'), 10);
                star.style.color = current <= activeRating ? '#FFC107' : '#ddd';
            });
        }

        let selected = parseInt(ratingVal.value || '5', 10);
        renderStars(selected);

        ratingWrap.querySelectorAll('.star-icon').forEach(function (star) {
            star.addEventListener('click', function () {
                selected = parseInt(this.getAttribute('data-rating'), 10);
                ratingVal.value = String(selected);
                renderStars(selected);
            });

            star.addEventListener('mouseenter', function () {
                const hover = parseInt(this.getAttribute('data-rating'), 10);
                renderStars(hover);
            });
        });

        ratingWrap.addEventListener('mouseleave', function () {
            renderStars(selected);
        });
    })();
</script>
@endsection
