@extends('frontend.layouts.app')

@section('title', $blog['title'] . ' - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                    <li class="breadcrumb-item active">{{ $blog['title'] }}</li>
                </ol>
            </nav>

            <article>
                <h1 class="fw-bold mb-3">{{ $blog['title'] }}</h1>

                <div class="d-flex align-items-center gap-3 mb-4 text-muted small">
                    <span><i class="bi bi-person"></i> {{ $blog['author'] }}</span>
                    <span><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($blog['date'])->format('d/m/Y') }}</span>
                    <span><i class="bi bi-clock"></i> {{ $blog['read_time'] }}</span>
                    <span class="badge bg-primary">{{ $blog['category'] }}</span>
                </div>

                <div class="mb-4" style="height: 400px; background-color: #f8f9fa; border-radius: 12px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    @if($blog['image'])
                        <img src="{{ asset($blog['image']) }}" alt="{{ $blog['title'] }}" class="img-fluid h-100 w-100" style="object-fit: cover;">
                    @else
                        <i class="bi bi-image fs-1 text-muted"></i>
                    @endif
                </div>

                <div class="blog-content">
                    {!! nl2br(e($blog['content'])) !!}
                </div>

                <hr class="my-5">

                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold mb-2">Tags</h6>
                        <div>
                            <a href="#" class="badge bg-light text-dark me-2 mb-2">{{ $blog['category'] }}</a>
                            <a href="#" class="badge bg-light text-dark me-2 mb-2">Hương hoa</a>
                            <a href="#" class="badge bg-light text-dark me-2 mb-2">Tương kiến</a>
                        </div>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <h6 class="fw-bold mb-2">Chia sẻ</h6>
                        <div>
                            <a href="#" class="btn btn-sm btn-outline-primary me-2"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-info me-2"><i class="bi bi-twitter"></i></a>
                            <a href="#" class="btn btn-sm btn-outline-danger"><i class="bi bi-pinterest"></i></a>
                        </div>
                    </div>
                </div>
            </article>

            <hr class="my-5">

            <div class="card border-0 bg-light">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="rounded-circle bg-primary" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; color: white; font-size: 24px;">
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                        <div class="col">
                            <h6 class="mb-0 fw-bold">{{ $blog['author'] }}</h6>
                            <small class="text-muted">Tác giả tại Hương Hoa Xinh</small>
                            <p class="mt-2 mb-0 small">Chuyên viết về kiến thức về cây hoa, mẹo chăm sóc và những xu hướng mới nhất trong ngành hoa tươi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Bài viết liên quan</h5>
                    @foreach($blogs as $relatedBlog)
                        @if($relatedBlog['slug'] !== $blog['slug'])
                            <div class="mb-3 pb-3 border-bottom">
                                <h6 class="mb-1">
                                    <a href="{{ route('blog.show', $relatedBlog['slug']) }}" class="text-decoration-none">
                                        {{ $relatedBlog['title'] }}
                                    </a>
                                </h6>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($relatedBlog['date'])->format('d/m/Y') }}</small>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">Danh mục</h5>
                    <div class="list-group list-group-flush">
                        <a href="#" class="list-group-item list-group-item-action">Kiến thức</a>
                        <a href="#" class="list-group-item list-group-item-action">Mẹo & Kinh nghiệm</a>
                        <a href="#" class="list-group-item list-group-item-action">Tin tức</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .blog-content {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }

    .blog-content p {
        margin-bottom: 1rem;
    }
</style>
@endsection
