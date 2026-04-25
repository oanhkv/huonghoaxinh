@extends('frontend.layouts.app')

@section('title', $post->title)

@section('content')
<div class="blog-detail-page">
    <header class="blog-detail-hero">
        <div class="container">
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-white-50 text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}" class="text-white-50 text-decoration-none">Blog</a></li>
                    @if($post->category)
                        <li class="breadcrumb-item"><a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="text-white-50 text-decoration-none">{{ $post->category->name }}</a></li>
                    @endif
                </ol>
            </nav>
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="badge bg-light text-danger text-decoration-none mb-3 px-3 py-2 rounded-pill">
                    <i class="fas fa-tag me-1"></i> {{ $post->category->name }}
                </a>
            @endif
            <h1 class="blog-detail-title">{{ $post->title }}</h1>
            @if($post->excerpt)
                <p class="blog-detail-excerpt">{{ $post->excerpt }}</p>
            @endif
            <div class="blog-detail-meta">
                <span><i class="far fa-calendar-alt me-1"></i> {{ $post->published_at?->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}</span>
                <span class="mx-2">•</span>
                <span><i class="far fa-user me-1"></i> Hương Hoa Xinh</span>
                <span class="mx-2">•</span>
                <span><i class="far fa-clock me-1"></i> {{ max(2, (int) ceil(str_word_count(strip_tags($post->content)) / 220)) }} phút đọc</span>
            </div>
        </div>
    </header>

    <div class="container my-5">
        <div class="row gx-lg-5">
            <div class="col-lg-8">
                @if($post->image)
                    <figure class="blog-detail-cover mb-4">
                        <img src="{{ $post->image_url }}" class="img-fluid w-100 rounded-4 shadow-sm" alt="{{ $post->title }}">
                    </figure>
                @endif

                <article class="blog-detail-content">
                    {!! $post->content !!}
                </article>

                <div class="blog-detail-share mt-5 p-4 rounded-4">
                    <div class="row align-items-center g-3">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-1"><i class="fas fa-share-alt text-danger me-2"></i> Chia sẻ bài viết</h6>
                            <p class="text-muted small mb-0">Lan toả những câu chuyện hoa đẹp đến bạn bè.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm rounded-pill px-3"><i class="fab fa-facebook-f me-1"></i> Facebook</a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($post->title) }}" target="_blank" rel="noopener" class="btn btn-outline-info btn-sm rounded-pill px-3"><i class="fab fa-twitter me-1"></i> Twitter</a>
                            <a href="https://zalo.me/share/url?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener" class="btn btn-outline-success btn-sm rounded-pill px-3"><i class="fas fa-comment-dots me-1"></i> Zalo</a>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <a href="{{ route('blog.index') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-angle-left me-2"></i> Quay lại danh sách
                    </a>
                    <span class="text-muted small">Cập nhật: {{ $post->updated_at->diffForHumans() }}</span>
                </div>
            </div>

            <aside class="col-lg-4">
                <div class="position-sticky" style="top: 90px;">
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 blog-aside-card">
                        <h6 class="fw-bold mb-3"><i class="fas fa-fire text-danger me-2"></i> Bài viết liên quan</h6>
                        @forelse($relatedPosts as $rp)
                            <a href="{{ route('blog.show', $rp->slug) }}" class="blog-related-item d-flex gap-3 text-decoration-none mb-3">
                                <div class="blog-related-thumb">
                                    @if($rp->image)
                                        <img src="{{ $rp->image_url }}" alt="{{ $rp->title }}" loading="lazy">
                                    @else
                                        <div class="blog-related-fallback"><i class="fas fa-spa"></i></div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="blog-related-title">{{ Str::limit($rp->title, 70) }}</div>
                                    <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $rp->published_at?->format('d/m/Y') ?? $rp->created_at->format('d/m/Y') }}</small>
                                </div>
                            </a>
                        @empty
                            <p class="text-muted small mb-0">Chưa có bài viết liên quan.</p>
                        @endforelse
                    </div>

                    <div class="card border-0 shadow-sm rounded-4 p-4 blog-cta-card">
                        <div class="text-center">
                            <i class="fas fa-gift fa-2x text-danger mb-2"></i>
                            <h6 class="fw-bold mb-2">Đặt hoa ngay hôm nay</h6>
                            <p class="text-muted small mb-3">Hoa tươi mỗi ngày — giao nhanh nội thành, có thiệp tay miễn phí.</p>
                            <a href="{{ route('shop') }}" class="btn btn-danger rounded-pill px-4">Khám phá shop</a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<style>
    .blog-detail-hero {
        background: linear-gradient(135deg, #d63384 0%, #f06595 60%, #ff8fab 100%);
        color: #fff;
        padding: 70px 0 60px;
        position: relative;
        overflow: hidden;
    }
    .blog-detail-hero::before {
        content: '🌸';
        position: absolute;
        font-size: 280px;
        right: -60px;
        top: -60px;
        opacity: 0.08;
        transform: rotate(-15deg);
    }
    .blog-detail-hero::after {
        content: '🌹';
        position: absolute;
        font-size: 220px;
        left: -50px;
        bottom: -70px;
        opacity: 0.08;
        transform: rotate(20deg);
    }
    .blog-detail-hero .container { position: relative; z-index: 2; }
    .blog-detail-title {
        font-size: 2.4rem;
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 14px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.18);
    }
    .blog-detail-excerpt {
        font-size: 1.1rem;
        opacity: 0.95;
        max-width: 720px;
        margin-bottom: 16px;
    }
    .blog-detail-meta { font-size: 0.9rem; opacity: 0.92; }
    .blog-detail-cover img { max-height: 480px; object-fit: cover; }
    .blog-detail-content {
        font-size: 1.05rem;
        line-height: 1.85;
        color: #334155;
    }
    .blog-detail-content h2 {
        font-size: 1.6rem;
        font-weight: 700;
        color: #0f172a;
        margin: 2.2rem 0 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 3px solid #ffd6e3;
        position: relative;
    }
    .blog-detail-content h2::before {
        content: '';
        display: inline-block;
        width: 6px;
        height: 22px;
        background: #d63384;
        margin-right: 10px;
        vertical-align: middle;
        border-radius: 3px;
    }
    .blog-detail-content h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #d63384;
        margin: 1.6rem 0 0.8rem;
    }
    .blog-detail-content p { margin-bottom: 1.1rem; }
    .blog-detail-content ul, .blog-detail-content ol { margin-bottom: 1.2rem; padding-left: 1.4rem; }
    .blog-detail-content ul li, .blog-detail-content ol li { margin-bottom: 0.5rem; }
    .blog-detail-content ul li::marker { color: #d63384; }
    .blog-detail-content img {
        max-width: 100%;
        height: auto;
        border-radius: 14px;
        margin: 1.4rem 0;
        box-shadow: 0 12px 32px rgba(15, 23, 42, 0.1);
    }
    .blog-detail-content figure {
        margin: 1.6rem 0;
        text-align: center;
    }
    .blog-detail-content figure img { margin: 0 0 0.6rem; }
    .blog-detail-content figcaption {
        font-size: 0.85rem;
        color: #94a3b8;
        font-style: italic;
    }
    .blog-detail-content blockquote {
        background: linear-gradient(135deg, #fff5f8 0%, #fff 100%);
        border-left: 4px solid #d63384;
        padding: 18px 22px;
        margin: 1.6rem 0;
        border-radius: 8px;
        font-style: italic;
        color: #475569;
    }
    .blog-detail-content strong { color: #0f172a; }
    .blog-detail-content a { color: #d63384; text-decoration: none; border-bottom: 1px dashed #d63384; }
    .blog-detail-content a:hover { color: #b02a6c; }

    .blog-detail-share { background: linear-gradient(135deg, #fff5f8 0%, #f8fafc 100%); }

    .blog-related-item { transition: transform 0.25s ease; }
    .blog-related-item:hover { transform: translateX(4px); }
    .blog-related-thumb {
        width: 80px; height: 80px; border-radius: 12px; overflow: hidden; flex-shrink: 0;
    }
    .blog-related-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .blog-related-fallback {
        width: 100%; height: 100%; display: grid; place-items: center;
        background: linear-gradient(135deg, #ffe5ef, #fff5e5); color: #d63384;
    }
    .blog-related-title {
        font-size: 0.92rem;
        font-weight: 600;
        color: #0f172a;
        line-height: 1.4;
        margin-bottom: 4px;
    }
    .blog-related-item:hover .blog-related-title { color: #d63384; }

    .blog-cta-card { background: linear-gradient(135deg, #fff5f8 0%, #fff 100%); }

    @media (max-width: 767.98px) {
        .blog-detail-title { font-size: 1.7rem; }
        .blog-detail-hero { padding: 50px 0 40px; }
    }
</style>
@endsection
