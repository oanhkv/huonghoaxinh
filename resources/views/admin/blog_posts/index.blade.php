@extends('admin.layouts.admin')

@section('title', 'Quản lý Blog')

@section('content')
<div class="container-fluid hhx-blog-admin">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1"><i class="fas fa-newspaper text-success me-2"></i>Quản lý bài viết Blog</h4>
            <div class="text-muted small">Đăng / sửa / xoá bài viết về hoa, cẩm nang chăm sóc, ý nghĩa hoa…</div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-success">
                <i class="fas fa-tags me-1"></i> Danh mục blog
            </a>
            <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-1"></i> Thêm bài viết
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- ===== Stats cards ===== --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="hhx-stat hhx-stat-1">
                <div class="hhx-stat-icon"><i class="fas fa-newspaper"></i></div>
                <div class="hhx-stat-info">
                    <div class="hhx-stat-label">Tổng bài viết</div>
                    <div class="hhx-stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="hhx-stat hhx-stat-2">
                <div class="hhx-stat-icon"><i class="fas fa-eye"></i></div>
                <div class="hhx-stat-info">
                    <div class="hhx-stat-label">Đang hiển thị</div>
                    <div class="hhx-stat-value">{{ $stats['active'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="hhx-stat hhx-stat-3">
                <div class="hhx-stat-icon"><i class="fas fa-eye-slash"></i></div>
                <div class="hhx-stat-info">
                    <div class="hhx-stat-label">Ẩn / Nháp</div>
                    <div class="hhx-stat-value">{{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="hhx-stat hhx-stat-4">
                <div class="hhx-stat-icon"><i class="fas fa-tags"></i></div>
                <div class="hhx-stat-info">
                    <div class="hhx-stat-label">Danh mục</div>
                    <div class="hhx-stat-value">{{ $stats['categories'] }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Filter card ===== --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.blog-posts.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-magnifying-glass text-muted"></i></span>
                        <input type="text" name="q" value="{{ request('q') }}"
                               class="form-control"
                               placeholder="Tìm theo tiêu đề, slug, tóm tắt...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="category" class="form-select">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $c)
                            <option value="{{ $c->id }}" {{ (int) request('category') === $c->id ? 'selected' : '' }}>
                                {{ $c->name }} ({{ $c->posts_count }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hiển thị</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ẩn</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-success w-100"><i class="fas fa-filter me-1"></i>Lọc</button>
                </div>
            </form>

            {{-- Quick category pills --}}
            <div class="hhx-cat-pills mt-3">
                <a href="{{ route('admin.blog-posts.index') }}"
                   class="hhx-cat-pill {{ ! request('category') ? 'is-active' : '' }}">
                    <i class="fas fa-bars me-1"></i> Tất cả
                </a>
                @foreach($categories as $c)
                    <a href="{{ route('admin.blog-posts.index', ['category' => $c->id]) }}"
                       class="hhx-cat-pill {{ (int) request('category') === $c->id ? 'is-active' : '' }}">
                        {{ $c->name }}
                        <span class="hhx-cat-pill-count">{{ $c->posts_count }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ===== Blog cards grid ===== --}}
    @if($posts->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-newspaper fa-3x text-muted mb-3 d-block opacity-50"></i>
                <h6 class="text-muted">Chưa có bài viết nào khớp với bộ lọc.</h6>
                <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-success mt-2">
                    <i class="fas fa-plus me-1"></i> Tạo bài viết đầu tiên
                </a>
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($posts as $post)
                <div class="col-xl-6">
                    <div class="hhx-blog-row">
                        <div class="hhx-blog-thumb">
                            @if($post->image_url)
                                <img src="{{ $post->image_url }}" alt="">
                            @else
                                <div class="hhx-blog-thumb-empty"><i class="fas fa-image"></i></div>
                            @endif
                            @if($post->is_active)
                                <span class="hhx-blog-status hhx-status-active">Hiển thị</span>
                            @else
                                <span class="hhx-blog-status hhx-status-inactive">Ẩn</span>
                            @endif
                        </div>
                        <div class="hhx-blog-body">
                            <div class="hhx-blog-meta">
                                @if($post->category)
                                    <span class="hhx-blog-cat"><i class="fas fa-tag me-1"></i>{{ $post->category->name }}</span>
                                @else
                                    <span class="text-muted small">— Chưa phân loại —</span>
                                @endif
                                <span class="hhx-blog-date">
                                    <i class="far fa-calendar me-1"></i>
                                    {{ optional($post->published_at)->format('d/m/Y') ?? 'Chưa đăng' }}
                                </span>
                            </div>
                            <h6 class="hhx-blog-title">{{ $post->title }}</h6>
                            <p class="hhx-blog-excerpt">
                                {{ \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 130) }}
                            </p>
                            <div class="hhx-blog-actions">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="hhx-blog-btn hhx-btn-view">
                                    <i class="fas fa-eye me-1"></i> Xem
                                </a>
                                <a href="{{ route('admin.blog-posts.edit', $post) }}" class="hhx-blog-btn hhx-btn-edit">
                                    <i class="fas fa-pen me-1"></i> Sửa
                                </a>
                                <form action="{{ route('admin.blog-posts.destroy', $post) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xoá bài viết &quot;{{ addslashes($post->title) }}&quot;?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="hhx-blog-btn hhx-btn-del">
                                        <i class="fas fa-trash me-1"></i> Xoá
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($posts->hasPages())
            <div class="mt-4 d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        @endif
    @endif
</div>

<style>
/* ===== Stats cards ===== */
.hhx-stat {
    display: flex; align-items: center; gap: 16px;
    padding: 18px 20px;
    border-radius: 14px;
    background: #fff;
    box-shadow: 0 4px 14px rgba(15,23,42,.06);
    border: 1px solid #f1f2f6;
    height: 100%;
}
.hhx-stat-icon {
    width: 50px; height: 50px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 22px;
}
.hhx-stat-1 .hhx-stat-icon { background: linear-gradient(135deg, #198754, #20a464); }
.hhx-stat-2 .hhx-stat-icon { background: linear-gradient(135deg, #3b82f6, #06b6d4); }
.hhx-stat-3 .hhx-stat-icon { background: linear-gradient(135deg, #9ca3af, #6b7280); }
.hhx-stat-4 .hhx-stat-icon { background: linear-gradient(135deg, #d63384, #f06595); }
.hhx-stat-label { color: #6b7280; font-size: 12px; font-weight: 600; }
.hhx-stat-value { font-size: 28px; font-weight: 800; color: #111827; line-height: 1.1; }

/* ===== Category pills ===== */
.hhx-cat-pills {
    display: flex; flex-wrap: wrap; gap: 6px;
    padding-top: 12px; border-top: 1px dashed #e5e7eb;
}
.hhx-cat-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 5px 12px; border-radius: 999px;
    background: #f3f4f6; color: #4b5563;
    font-size: 12px; font-weight: 600;
    text-decoration: none;
    transition: all .15s ease;
}
.hhx-cat-pill:hover { background: #e5e7eb; color: #1f2937; }
.hhx-cat-pill.is-active {
    background: linear-gradient(135deg, #198754, #20a464);
    color: #fff;
    box-shadow: 0 3px 8px rgba(25, 135, 84, .25);
}
.hhx-cat-pill-count {
    background: #fff; color: #1f2937;
    font-size: 10px; font-weight: 700;
    padding: 1px 7px; border-radius: 999px;
}
.hhx-cat-pill.is-active .hhx-cat-pill-count {
    background: rgba(255,255,255,.25); color: #fff;
}

/* ===== Blog card grid ===== */
.hhx-blog-row {
    display: flex; gap: 16px;
    background: #fff;
    border: 1px solid #f1f2f6;
    border-radius: 14px;
    padding: 14px;
    box-shadow: 0 2px 8px rgba(15,23,42,.04);
    transition: transform .15s ease, box-shadow .15s ease;
    height: 100%;
}
.hhx-blog-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 24px rgba(15,23,42,.08);
}

.hhx-blog-thumb {
    position: relative;
    width: 180px; flex-shrink: 0;
    border-radius: 10px;
    overflow: hidden;
    background: #f3f4f6;
    aspect-ratio: 4/3;
}
.hhx-blog-thumb img { width: 100%; height: 100%; object-fit: cover; }
.hhx-blog-thumb-empty {
    width: 100%; height: 100%;
    display: flex; align-items: center; justify-content: center;
    color: #9ca3af; font-size: 24px;
}
.hhx-blog-status {
    position: absolute; top: 8px; left: 8px;
    padding: 3px 10px; border-radius: 999px;
    font-size: 10px; font-weight: 700;
    color: #fff;
}
.hhx-status-active   { background: #198754; }
.hhx-status-inactive { background: #6b7280; }

.hhx-blog-body {
    flex: 1; min-width: 0;
    display: flex; flex-direction: column;
}
.hhx-blog-meta {
    display: flex; align-items: center; gap: 12px;
    font-size: 11px; color: #9ca3af;
    margin-bottom: 6px;
}
.hhx-blog-cat {
    background: #ecfdf5; color: #065f46;
    padding: 2px 10px; border-radius: 999px;
    font-weight: 700;
}
.hhx-blog-date { font-size: 11px; }
.hhx-blog-title {
    font-size: 15px; font-weight: 700;
    color: #111827; margin-bottom: 6px;
    line-height: 1.35;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.hhx-blog-excerpt {
    font-size: 13px; color: #6b7280;
    margin-bottom: 12px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.hhx-blog-actions {
    display: flex; gap: 6px; margin-top: auto;
}
.hhx-blog-btn {
    padding: 5px 12px; border-radius: 999px;
    font-size: 12px; font-weight: 600;
    border: 1.5px solid transparent;
    background: #fff;
    cursor: pointer;
    text-decoration: none;
    transition: all .15s ease;
}
.hhx-btn-view { color: #198754; border-color: #6ee7b7; }
.hhx-btn-view:hover { background: #ecfdf5; color: #065f46; }
.hhx-btn-edit { color: #d97706; border-color: #fcd34d; }
.hhx-btn-edit:hover { background: #fef3c7; color: #92400e; }
.hhx-btn-del { color: #dc2626; border-color: #fca5a5; }
.hhx-btn-del:hover { background: #fee2e2; color: #991b1b; }

@media (max-width: 575.98px) {
    .hhx-blog-row { flex-direction: column; }
    .hhx-blog-thumb { width: 100%; aspect-ratio: 16/9; }
}
</style>
@endsection
