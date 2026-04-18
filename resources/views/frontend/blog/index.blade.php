@extends('frontend.layouts.app')

@section('title', 'Blog - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Blog Hương Hoa Xinh</h1>
        <p class="text-muted">Cập nhật tin tức, mẹo chọn hoa và ý tưởng quà tặng đẹp.</p>
    </div>

    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 rounded-4 p-4 h-100">
                <h5 class="mb-3">Danh mục blog</h5>
                <div class="list-group list-group-flush">
                    <a href="{{ route('blog.index') }}" class="list-group-item list-group-item-action px-0 border-0 @if(!request('category')) active @endif">
                        Tất cả
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="list-group-item list-group-item-action px-0 border-0 @if(request('category') == $category->slug) active @endif">
                            <div class="d-flex align-items-center justify-content-between">
                                <span>{{ $category->name }}</span>
                                <span class="badge bg-success rounded-pill">{{ $category->posts_count }}</span>
                            </div>
                            <small class="text-muted">{{ Str::limit($category->description, 40) }}</small>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="mb-4 d-flex flex-wrap align-items-center gap-2 justify-content-between">
                <div>
                    <span class="badge bg-success">{{ $posts->total() }} bài viết</span>
                    @if(request('category'))
                        <span class="badge bg-secondary">Lọc: {{ optional($categories->firstWhere('slug', request('category')))->name ?? 'Không rõ' }}</span>
                    @endif
                </div>
                <div class="text-muted small">Cập nhật mới nhất: {{ $posts->first()?->published_at?->format('d/m/Y') ?? 'Chưa rõ' }}</div>
            </div>

            <div class="row g-4">
                @forelse($posts as $post)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm border-0 overflow-hidden rounded-4">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $post->title }}">
                            @else
                                <div class="bg-success bg-opacity-10 d-flex align-items-center justify-content-center" style="height: 220px;">
                                    <i class="fas fa-newspaper fa-3x text-success"></i>
                                </div>
                            @endif

                            <div class="card-body d-flex flex-column">
                                @if($post->category)
                                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="badge bg-success text-decoration-none align-self-start mb-2">{{ $post->category->name }}</a>
                                @endif
                                <h5 class="card-title">{{ Str::limit($post->title, 70) }}</h5>
                                <p class="text-muted mb-3">{{ Str::limit($post->excerpt ?? strip_tags($post->content), 110) }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <small class="text-muted">{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</small>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-success btn-sm">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted">Chưa có bài viết nào.</h4>
                        <p class="text-muted">Vui lòng quay lại sau để xem thêm tin tức.</p>
                    </div>
                @endforelse
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
