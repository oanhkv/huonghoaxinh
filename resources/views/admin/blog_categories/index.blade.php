@extends('admin.layouts.admin')

@section('title', 'Danh mục Blog')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-tags text-success me-2"></i>Danh mục Blog</h4>
            <div class="text-muted small">Phân loại bài viết: Cẩm nang, Ý nghĩa hoa, Cách chăm sóc, Dịp lễ…</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-success">
                <i class="fas fa-newspaper me-1"></i> Quay lại Bài viết
            </a>
            <a href="{{ route('admin.blog-categories.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Thêm danh mục
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.blog-categories.index') }}" class="row g-2 align-items-center">
                <div class="col-md-10">
                    <input type="text" name="q" value="{{ request('q') }}"
                           class="form-control"
                           placeholder="Tìm theo tên / slug / mô tả...">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-magnifying-glass me-1"></i>Tìm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="60">#</th>
                            <th>Tên danh mục</th>
                            <th>Mô tả</th>
                            <th width="120">Số bài viết</th>
                            <th width="160">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $c)
                            <tr>
                                <td class="text-muted">{{ $c->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $c->name }}</div>
                                </td>
                                <td>
                                    <span class="text-muted small">
                                        {{ \Illuminate\Support\Str::limit($c->description, 80) ?: '—' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success">
                                        {{ $c->posts_count }} bài
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.blog-categories.edit', $c) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-pen"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.blog-categories.destroy', $c) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Xoá danh mục này? (Chỉ xoá được khi không còn bài viết nào.)');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Xoá
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fas fa-tags fa-2x mb-2 d-block opacity-50"></i>
                                    Chưa có danh mục blog nào.
                                    <a href="{{ route('admin.blog-categories.create') }}">Tạo danh mục đầu tiên</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($categories->hasPages())
            <div class="card-footer bg-white border-top">
                {{ $categories->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
