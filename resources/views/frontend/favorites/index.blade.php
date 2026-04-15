@extends('frontend.layouts.app')

@section('title', 'Sản phẩm yêu thích - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h1 class="fw-bold mb-4">Sản phẩm yêu thích</h1>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    @if(!empty($favoriteItems) && count($favoriteItems))
                        <div class="row g-4">
                            @foreach($favoriteItems as $item)
                                <div class="col-md-4">
                                    <div class="card h-100 border-0 shadow-sm">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="card-img-top" style="height: 220px; object-fit: cover;" alt="{{ $item['name'] }}">
                                        @else
                                            <img src="https://via.placeholder.com/300x220" class="card-img-top" alt="{{ $item['name'] }}">
                                        @endif
                                        <div class="card-body text-center">
                                            <h6 class="card-title">{{ $item['name'] }}</h6>
                                            <p class="text-danger fw-bold">{{ number_format($item['price']) }} đ</p>
                                            <a href="{{ route('product.show', $item['slug']) }}" class="btn btn-outline-success btn-sm w-100 mb-2">Xem chi tiết</a>
                                            <form method="POST" action="{{ route('favorites.destroy', $item['id']) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm w-100">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Chưa có sản phẩm yêu thích</h5>
                            <p>Hãy thêm sản phẩm vào danh sách yêu thích để xem lại sau.</p>
                            <a href="{{ route('shop') }}" class="btn btn-success mt-2">Đi đến cửa hàng</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection