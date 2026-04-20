@extends('frontend.layouts.app')

@section('title', $post->title)

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto text-center">
            @if($post->category)
                <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="badge bg-success text-decoration-none mb-3">{{ $post->category->name }}</a>
            @endif
            <h1 class="fw-bold">{{ $post->title }}</h1>
            <p class="text-muted">{{ $post->published_at ? $post->published_at->format('d/m/Y') : $post->created_at->format('d/m/Y') }}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            @if($post->image)
                <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid rounded mb-4 shadow-sm" alt="{{ $post->title }}">
            @endif

            <div class="card shadow-sm border-0 p-4 rounded-4">
                <div class="blog-content text-muted fs-5">
                    {!! nl2br(e($post->content)) !!}
                </div>
            </div>

            <div class="mt-4 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <a href="{{ route('blog.index') }}" class="btn btn-outline-success">
                    <i class="fas fa-angle-left me-2"></i> Quay lại blog
                </a>
                <span class="text-muted">Cập nhật: {{ $post->created_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
