@extends('admin.layouts.admin')

@section('title', ($mode === 'create' ? 'Tạo' : 'Sửa') . ' danh mục Blog')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h4 class="fw-bold mb-1">
                <i class="fas fa-{{ $mode === 'create' ? 'plus' : 'pen' }} text-success me-2"></i>
                {{ $mode === 'create' ? 'Tạo danh mục Blog' : 'Sửa danh mục Blog' }}
            </h4>
        </div>
        <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <form method="POST"
                          action="{{ $mode === 'create'
                              ? route('admin.blog-categories.store')
                              : route('admin.blog-categories.update', $category) }}">
                        @csrf
                        @if($mode === 'edit')@method('PUT')@endif

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên danh mục *</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $category->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   required placeholder="VD: Cẩm nang chăm sóc hoa">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Slug (đường dẫn)</label>
                            <div class="input-group">
                                <span class="input-group-text">/blog?category=</span>
                                <input type="text" name="slug"
                                       value="{{ old('slug', $category->slug) }}"
                                       class="form-control @error('slug') is-invalid @enderror"
                                       placeholder="tu-dong-sinh-tu-ten">
                                @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-text small">Để trống → tự sinh từ tên.</div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Mô tả</label>
                            <textarea name="description" rows="4" maxlength="1000"
                                      class="form-control @error('description') is-invalid @enderror"
                                      placeholder="Mô tả ngắn về danh mục (tuỳ chọn)">{{ old('description', $category->description) }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-success">
                                <i class="fas fa-{{ $mode === 'create' ? 'plus' : 'save' }} me-1"></i>
                                {{ $mode === 'create' ? 'Tạo danh mục' : 'Lưu thay đổi' }}
                            </button>
                            <a href="{{ route('admin.blog-categories.index') }}" class="btn btn-outline-secondary">
                                Huỷ
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
