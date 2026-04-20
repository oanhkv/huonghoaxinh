@extends('frontend.layouts.app')

@section('title', 'Blog - Hương Hoa Xinh')

@section('content')
<div class="border-bottom bg-white">
    <div class="container py-5">
        <div class="text-center mb-2">
            <span class="badge rounded-pill bg-success bg-opacity-10 text-success px-3 py-2">Blog &amp; cảm hứng</span>
        </div>
        <h1 class="fw-bold text-center mb-2">Chuyện hoa, quà và những ngày đáng nhớ</h1>
        <p class="text-muted text-center mx-auto mb-0" style="max-width: 640px;">
            Mẹo chọn hoa theo dịp, ý tưởng gói quà, xu hướng màu sắc mùa — viết ngắn gọn, dễ đọc, để bạn tự tin đặt một món quà ý nghĩa.
        </p>
    </div>
</div>

<div class="container py-5">
    @if(isset($featuredPost) && $featuredPost)
        <div class="card border-0 shadow rounded-4 overflow-hidden mb-5 blog-featured">
            <div class="row g-0 align-items-stretch">
                <div class="col-lg-6">
                    @if($featuredPost->image)
                        <div class="h-100" style="min-height: 280px;">
                            <img src="{{ asset('storage/' . $featuredPost->image) }}" class="w-100 h-100 object-fit-cover" alt="{{ $featuredPost->title }}" style="min-height: 280px; object-fit: cover;">
                        </div>
                    @else
                        <div class="h-100 d-flex align-items-center justify-content-center bg-success bg-opacity-10" style="min-height: 280px;">
                            <i class="fas fa-star fa-4x text-success opacity-75"></i>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="p-4 p-lg-5 d-flex flex-column h-100 justify-content-center">
                        <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill align-self-start mb-3">Bài nổi bật</span>
                        @if($featuredPost->category)
                            <a href="{{ route('blog.index', ['category' => $featuredPost->category->slug]) }}" class="text-success text-decoration-none small fw-semibold mb-2">{{ $featuredPost->category->name }}</a>
                        @endif
                        <h2 class="fw-bold mb-3">{{ $featuredPost->title }}</h2>
                        <p class="text-muted mb-4">{{ Str::limit($featuredPost->excerpt ?? strip_tags($featuredPost->content), 220) }}</p>
                        <div class="d-flex flex-wrap align-items-center gap-3 mt-auto">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-success rounded-pill px-4">Đọc ngay</a>
                            <span class="text-muted small">
                                <i class="far fa-calendar-alt me-1"></i>
                                {{ $featuredPost->published_at ? $featuredPost->published_at->format('d/m/Y') : $featuredPost->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <style>
            @media (min-width: 992px) {
                .blog-featured .col-lg-6:first-child { max-height: 380px; overflow: hidden; }
            }
        </style>
    @endif

    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100 sticky-lg-top" style="top: 5.5rem;">
                <h5 class="mb-3 fw-bold">Danh mục</h5>
                <p class="small text-muted mb-3">Lọc nhanh theo chủ đề bạn quan tâm.</p>
                <div class="list-group list-group-flush">
                    <a href="{{ route('blog.index') }}" class="list-group-item list-group-item-action px-0 border-0 @if(!request('category')) active @endif">
                        Tất cả bài viết
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="list-group-item list-group-item-action px-0 border-0 @if(request('category') == $category->slug) active @endif">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-success rounded-pill">{{ $category->posts_count }}</span>
                            </div>
                            @if($category->description)
                                <small class="text-muted">{{ Str::limit($category->description, 48) }}</small>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="mb-4 d-flex flex-wrap align-items-center gap-2 justify-content-between">
                <div>
                    <span class="badge bg-success">{{ $posts->total() }} bài</span>
                    @if(request('category'))
                        <span class="badge bg-secondary">Đang lọc: {{ optional($categories->firstWhere('slug', request('category')))->name ?? 'Danh mục' }}</span>
                    @endif
                </div>
                <div class="text-muted small">
                    Mới nhất: {{ $posts->first()?->published_at?->format('d/m/Y') ?? $posts->first()?->created_at?->format('d/m/Y') ?? '—' }}
                </div>
            </div>

            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6">
                        <article class="card h-100 shadow-sm border-0 overflow-hidden rounded-4 blog-card-hover">
                            @if($post->image)
                                <div class="position-relative overflow-hidden" style="height: 200px;">
                                    <img src="{{ asset('storage/' . $post->image) }}" class="w-100 h-100" style="object-fit: cover;" alt="{{ $post->title }}">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        @if($post->category)
                                            <span class="badge bg-dark bg-opacity-75 rounded-pill">{{ $post->category->name }}</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="bg-success bg-opacity-10 d-flex align-items-center justify-content-center position-relative" style="height: 200px;">
                                    <i class="fas fa-newspaper fa-3x text-success opacity-50"></i>
                                    @if($post->category)
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-dark bg-opacity-75 rounded-pill">{{ $post->category->name }}</span>
                                    @endif
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h2 class="h5 card-title fw-bold">{{ Str::limit($post->title, 72) }}</h2>
                                <p class="text-muted small mb-3 flex-grow-1">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 100) }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <time class="text-muted small" datetime="{{ $post->published_at?->toIso8601String() }}">{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</time>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-success btn-sm rounded-pill">Đọc tiếp</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted">Chưa có bài viết trong mục này.</h4>
                        <p class="text-muted mb-4">Quay lại sau hoặc xem danh mục khác nhé.</p>
                        <a href="{{ route('blog.index') }}" class="btn btn-success rounded-pill">Xem tất cả</a>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
<style>
    .blog-card-hover { transition: transform 0.25s ease, box-shadow 0.25s ease; }
    .blog-card-hover:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(15,23,42,0.1) !important; }
</style>
@endsection
