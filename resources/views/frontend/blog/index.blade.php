@extends('frontend.layouts.app')

@section('title', 'Blog - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <h1 class="fw-bold mb-5">Blog Hương Hoa Xinh</h1>

            @if($selectedCategory)
                <div class="alert alert-info mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Danh mục: <strong>{{ $selectedCategory }}</strong></span>
                        <a href="{{ route('blog.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                    </div>
                </div>
            @endif

            @forelse($blogs as $blog)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div style="height: 250px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center; border-radius: 8px 0 0 8px;">
                                @if($blog['image'])
                                    <img src="{{ asset($blog['image']) }}" alt="{{ $blog['title'] }}" class="img-fluid h-100 w-100" style="object-fit: cover; border-radius: 8px 0 0 8px;">
                                @else
                                    <i class="bi bi-image fs-1 text-muted"></i>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{ $blog['category'] }}</span>
                                </div>
                                <h5 class="card-title fw-bold mb-2">
                                    <a href="{{ route('blog.show', $blog['slug']) }}" class="text-decoration-none text-dark">
                                        {{ $blog['title'] }}
                                    </a>
                                </h5>

                                <p class="card-text text-muted mb-3">{{ $blog['excerpt'] }}</p>

                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <div>
                                        <i class="bi bi-person"></i> {{ $blog['author'] }} • 
                                        <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($blog['date'])->format('d/m/Y') }} • 
                                        <i class="bi bi-clock"></i> {{ $blog['read_time'] }}
                                    </div>
                                    <a href="{{ route('blog.show', $blog['slug']) }}" class="btn btn-sm btn-primary">
                                        Đọc tiếp →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Chưa có bài viết nào.</div>
            @endforelse
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Danh mục</h5>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('blog.index') }}" class="list-group-item list-group-item-action @if(!$selectedCategory) active @endif">
                            Tất cả bài viết
                        </a>
                        @foreach($categories as $category)
                            @php
                                $count = count(array_filter($blogs, function($blog) use ($category) {
                                    return $blog['category'] === $category;
                                }));
                            @endphp
                            <a href="{{ route('blog.index') }}?category={{ urlencode($category) }}" class="list-group-item list-group-item-action @if($selectedCategory === $category) active @endif">
                                {{ $category }} <span class="badge bg-light text-dark float-end">{{ count(array_filter(json_decode(json_encode([
                                    ['category' => 'Kiến thức', 'id' => 1],
                                    ['category' => 'Kiến thức', 'id' => 4],
                                    ['category' => 'Mẹo & Kinh nghiệm', 'id' => 2],
                                    ['category' => 'Mẹo & Kinh nghiệm', 'id' => 3],
                                    ['category' => 'Tin tức', 'id' => 5],
                                ]), true), function($blog) use ($category) {
                                    return $blog['category'] === $category;
                                })) }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Bài viết mới nhất</h5>
                    @foreach(array_slice($blogs, 0, 3) as $blog)
                        <div class="mb-3 pb-3 border-bottom">
                            <h6 class="mb-1">
                                <a href="{{ route('blog.show', $blog['slug']) }}" class="text-decoration-none">
                                    {{ $blog['title'] }}
                                </a>
                            </h6>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($blog['date'])->format('d/m/Y') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
