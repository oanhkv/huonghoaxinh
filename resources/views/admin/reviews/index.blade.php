@extends('admin.layouts.admin')

@section('title', 'Quản lý Đánh giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Quản lý Đánh giá</h3>
            <small class="text-muted">Xem và xóa các comment không phù hợp</small>
        </div>
        <span class="badge bg-primary px-3 py-2 fs-6">Tổng số: {{ $reviews->total() }} đánh giá</span>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.reviews.index') }}" class="row g-3">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo bình luận, khách hàng hoặc sản phẩm..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="rating" class="form-select">
                        <option value="">-- Tất cả sao --</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} sao</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary w-100">Làm mới</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Khách hàng</th>
                            <th>Sản phẩm</th>
                            <th>Đánh giá</th>
                            <th>Bình luận</th>
                            <th>Ngày</th>
                            <th width="120">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="fw-medium">{{ $review->user->name ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $review->user->email ?? '' }}</small>
                                </td>
                                <td>{{ $review->product->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">{{ $review->rating }}/5</span>
                                </td>
                                <td style="max-width: 420px; white-space: normal;">{{ $review->comment }}</td>
                                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" onsubmit="return confirm('Xóa comment này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Chưa có đánh giá nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $reviews->links() }}
        </div>
    </div>
</div>
@endsection