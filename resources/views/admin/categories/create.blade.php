@extends('admin.layouts.admin')

@section('title', 'Thêm Danh Mục Mới')

@section('content')
<div class="container-fluid admin-form-shell">
    <div class="card admin-form-card shadow-sm">
        <div class="admin-form-hero p-4 p-lg-5">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="admin-form-icon">
                    <i class="fas fa-tags fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-2">Thêm Danh Mục Mới</h3>
                    <p class="admin-form-subtitle">Tạo danh mục mới với bố cục thoáng, màu sắc dịu và thao tác rõ ràng hơn.</p>
                </div>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="admin-section-title">Thông tin danh mục</div>
                <div class="admin-form-panel">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Hoa Hồng" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label">Danh mục cha</label>
                            <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                <option value="">-- Đây là danh mục chính --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" {{ old('parent_id') == $parent->id ? 'selected' : '' }}>{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Mô tả ngắn về danh mục...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end flex-wrap mt-4">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary px-4 py-2">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-save me-1"></i> Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection