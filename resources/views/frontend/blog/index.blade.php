@extends('frontend.layouts.app')

@section('title', 'Blog - Hương Hoa Xinh')

@section('content')
{{-- ===== Hero ===== --}}
<div class="blog-hero">
    <div class="container py-5 position-relative">
        <div class="text-center mx-auto" style="max-width:760px;">
            <span class="blog-hero-badge"><i class="fas fa-leaf me-1"></i> Blog Hương Hoa Xinh</span>
            <h1 class="blog-hero-title">Chuyện hoa, quà và những ngày đáng nhớ</h1>
            <p class="blog-hero-sub">
                Mẹo chọn hoa theo dịp, ý nghĩa từng loài hoa, ý tưởng cắm hoa & gói quà — viết bởi đội ngũ Hương Hoa Xinh.
            </p>
        </div>
    </div>
</div>

<div class="container py-5 blog-page">

    {{-- ===== Category pills (filter) ===== --}}
    <div class="blog-cat-pills mb-4">
        <a href="{{ route('blog.index') }}" class="blog-cat-pill {{ ! request('category') ? 'is-active' : '' }}">
            <i class="fas fa-bars me-1"></i> Tất cả
        </a>
        @foreach($categories as $category)
            @if($category->posts_count > 0)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                   class="blog-cat-pill {{ request('category') === $category->slug ? 'is-active' : '' }}">
                    <i class="fas fa-tag me-1"></i>{{ $category->name }}
                    <span class="blog-cat-pill-count">{{ $category->posts_count }}</span>
                </a>
            @endif
        @endforeach
    </div>

    {{-- ===== Featured ===== --}}
    @if(! request('category') && isset($featuredPost) && $featuredPost)
        <article class="blog-featured mb-5">
            <div class="row g-0">
                <div class="col-lg-7">
                    <a href="{{ route('blog.show', $featuredPost->slug) }}" class="blog-featured-img-link">
                        @if($featuredPost->image)
                            <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->title }}">
                        @else
                            <div class="blog-featured-placeholder">
                                <i class="fas fa-star"></i>
                            </div>
                        @endif
                        <span class="blog-featured-pin"><i class="fas fa-star me-1"></i>Bài nổi bật</span>
                    </a>
                </div>
                <div class="col-lg-5">
                    <div class="p-4 p-lg-5 d-flex flex-column h-100 justify-content-center">
                        @if($featuredPost->category)
                            <a href="{{ route('blog.index', ['category' => $featuredPost->category->slug]) }}"
                               class="blog-featured-cat">
                                <i class="fas fa-tag me-1"></i>{{ $featuredPost->category->name }}
                            </a>
                        @endif
                        <h2 class="blog-featured-title">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}">{{ $featuredPost->title }}</a>
                        </h2>
                        <div class="blog-featured-excerpt">
                            {{ \Illuminate\Support\Str::limit(strip_tags($featuredPost->excerpt ?: $featuredPost->content), 220) }}
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-3 mt-3">
                            <a href="{{ route('blog.show', $featuredPost->slug) }}" class="btn btn-success rounded-pill px-4">
                                <i class="fas fa-arrow-right me-1"></i>Đọc bài
                            </a>
                            <span class="blog-meta-small">
                                <i class="far fa-calendar me-1"></i>
                                {{ optional($featuredPost->published_at)->format('d/m/Y') ?? $featuredPost->created_at->format('d/m/Y') }}
                            </span>
                            <span class="blog-meta-small">
                                <i class="far fa-clock me-1"></i>
                                {{ max(1, intval(str_word_count(strip_tags($featuredPost->content)) / 200)) }} phút đọc
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    @endif

    {{-- ===== Posts grid ===== --}}
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h3 class="fw-bold mb-1">
                @if(request('category'))
                    {{ optional($categories->firstWhere('slug', request('category')))->name ?? 'Bài viết' }}
                @else
                    Bài viết mới nhất
                @endif
            </h3>
            <div class="text-muted small">{{ $posts->total() }} bài viết</div>
        </div>
    </div>

    @if($posts->isEmpty())
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3 d-block opacity-50"></i>
                <h5 class="text-muted">Chưa có bài viết trong mục này.</h5>
                <a href="{{ route('blog.index') }}" class="btn btn-success rounded-pill mt-2">
                    <i class="fas fa-arrow-left me-1"></i>Xem tất cả bài viết
                </a>
            </div>
        </div>
    @else
        <div class="row g-4">
            @foreach($posts as $post)
                <div class="col-md-6 col-xl-4">
                    <article class="blog-card">
                        <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-img-link">
                            @if($post->image)
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }}">
                            @else
                                <div class="blog-card-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                            @endif
                            @if($post->category)
                                <span class="blog-card-cat">{{ $post->category->name }}</span>
                            @endif
                        </a>
                        <div class="blog-card-body">
                            <h5 class="blog-card-title">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h5>
                            <p class="blog-card-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 110) }}
                            </p>
                            <div class="blog-card-foot">
                                <span class="blog-meta-small">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ optional($post->published_at)->format('d/m/Y') ?? $post->created_at->format('d/m/Y') }}
                                </span>
                                <a href="{{ route('blog.show', $post->slug) }}" class="blog-card-link">
                                    Đọc tiếp <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        @if($posts->hasPages())
            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        @endif
    @endif
</div>

<style>
/* ===== Hero ===== */
.blog-hero {
    background:
        radial-gradient(circle at 10% 0%, rgba(214,51,132,.08), transparent 50%),
        radial-gradient(circle at 90% 100%, rgba(25,135,84,.08), transparent 50%),
        linear-gradient(180deg, #ffffff 0%, #fafbfc 100%);
    border-bottom: 1px solid rgba(15,23,42,.05);
}
.blog-hero-badge {
    display: inline-block; padding: 6px 16px; border-radius: 999px;
    background: rgba(25,135,84,.12); color: #198754;
    font-weight: 700; font-size: 13px;
    margin-bottom: 16px;
}
.blog-hero-title {
    font-weight: 800; font-size: 2.2rem; line-height: 1.2;
    margin-bottom: 14px;
    background: linear-gradient(135deg, #1f2937, #198754);
    -webkit-background-clip: text; background-clip: text;
    color: transparent;
}
.blog-hero-sub { color: #6b7280; font-size: 1.05rem; }

/* ===== Category pills ===== */
.blog-cat-pills {
    display: flex; flex-wrap: wrap; gap: 8px;
    padding: 16px;
    background: #fff;
    border: 1px solid #f1f2f6;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(15,23,42,.04);
}
.blog-cat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 8px 16px;
    border-radius: 999px;
    background: #f3f4f6;
    color: #4b5563;
    font-weight: 600; font-size: 13px;
    text-decoration: none;
    border: 1.5px solid transparent;
    transition: all .15s ease;
}
.blog-cat-pill:hover {
    background: #ecfdf5; color: #065f46; border-color: #6ee7b7;
}
.blog-cat-pill.is-active {
    background: linear-gradient(135deg, #198754, #20a464);
    color: #fff;
    box-shadow: 0 6px 14px rgba(25,135,84,.3);
}
.blog-cat-pill.is-active .blog-cat-pill-count {
    background: rgba(255,255,255,.25); color: #fff;
}
.blog-cat-pill-count {
    background: #fff; color: #1f2937;
    font-size: 11px; font-weight: 700;
    padding: 1px 8px; border-radius: 999px;
}

/* ===== Featured ===== */
.blog-featured {
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 14px 40px rgba(15,23,42,.08);
    border: 1px solid #f1f2f6;
}
.blog-featured-img-link {
    position: relative; display: block;
    height: 100%; min-height: 360px;
}
.blog-featured-img-link img {
    width: 100%; height: 100%;
    min-height: 360px;
    object-fit: cover;
    display: block;
}
.blog-featured-placeholder {
    width: 100%; height: 100%; min-height: 360px;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706; font-size: 80px;
}
.blog-featured-pin {
    position: absolute; top: 16px; left: 16px;
    background: linear-gradient(135deg, #f59e0b, #f97316);
    color: #fff;
    padding: 6px 14px; border-radius: 999px;
    font-weight: 700; font-size: 12px;
    box-shadow: 0 6px 14px rgba(245,158,11,.4);
}
.blog-featured-cat {
    align-self: flex-start;
    display: inline-flex; align-items: center;
    background: #ecfdf5; color: #065f46;
    padding: 4px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 700;
    text-decoration: none;
    margin-bottom: 14px;
}
.blog-featured-cat:hover { background: #d1fae5; }
.blog-featured-title {
    font-weight: 800; font-size: 1.85rem; line-height: 1.25;
    margin-bottom: 16px;
}
.blog-featured-title a { color: #111827; text-decoration: none; }
.blog-featured-title a:hover { color: #198754; }
.blog-featured-excerpt { color: #6b7280; line-height: 1.7; }

.blog-meta-small {
    font-size: 13px; color: #9ca3af;
}

/* ===== Blog card ===== */
.blog-card {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 14px rgba(15,23,42,.05);
    border: 1px solid #f1f2f6;
    height: 100%;
    display: flex; flex-direction: column;
    transition: transform .25s ease, box-shadow .25s ease;
}
.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(15,23,42,.10);
}
.blog-card-img-link {
    position: relative; display: block;
    aspect-ratio: 16/10;
    overflow: hidden;
    background: #f3f4f6;
}
.blog-card-img-link img {
    width: 100%; height: 100%; object-fit: cover;
    transition: transform .35s ease;
}
.blog-card:hover .blog-card-img-link img { transform: scale(1.06); }
.blog-card-placeholder {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #d97706; font-size: 50px;
}
.blog-card-cat {
    position: absolute; bottom: 12px; left: 12px;
    background: rgba(0,0,0,.7);
    color: #fff;
    padding: 4px 12px; border-radius: 999px;
    font-size: 11px; font-weight: 700;
    backdrop-filter: blur(4px);
}
.blog-card-body {
    padding: 18px;
    flex: 1;
    display: flex; flex-direction: column;
}
.blog-card-title {
    font-weight: 700; font-size: 1.05rem; line-height: 1.4;
    margin-bottom: 10px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.blog-card-title a { color: #111827; text-decoration: none; }
.blog-card-title a:hover { color: #198754; }
.blog-card-excerpt {
    color: #6b7280; font-size: 13.5px; line-height: 1.6;
    margin-bottom: 14px;
    display: -webkit-box; -webkit-line-clamp: 3;
    -webkit-box-orient: vertical; overflow: hidden;
    flex: 1;
}
.blog-card-foot {
    display: flex; align-items: center; justify-content: space-between;
    padding-top: 10px; border-top: 1px dashed #e5e7eb;
}
.blog-card-link {
    color: #198754;
    font-weight: 600; font-size: 13px;
    text-decoration: none;
    transition: gap .2s ease;
}
.blog-card-link:hover { color: #065f46; }
.blog-card-link i { transition: transform .2s ease; }
.blog-card-link:hover i { transform: translateX(3px); }
</style>
@endsection
