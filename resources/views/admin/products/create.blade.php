@extends('admin.layouts.admin')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
<div class="container-fluid admin-form-shell">
    <div class="card admin-form-card shadow-sm">
        <div class="admin-form-hero p-4 p-lg-5">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="admin-form-icon">
                    <i class="fas fa-seedling fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-2">Thêm Sản Phẩm Mới</h3>
                    <p class="admin-form-subtitle">Tạo sản phẩm mới với bố cục rõ ràng, dễ nhập liệu và nhìn chuyên nghiệp hơn.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="admin-section-title">Thông tin cơ bản</div>
                <div class="admin-form-panel mb-4">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ví dụ: Hoa hồng đỏ 20 bông" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" placeholder="Ví dụ: 450000" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Chọn danh mục --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">Số lượng trong kho <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="admin-section-title">Mô tả & hình ảnh</div>
                <div class="admin-form-panel mb-4">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <label class="form-label">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" placeholder="Mô tả ngắn về sản phẩm, chất liệu, dịp tặng...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4">
                            <label class="form-label">Hình ảnh sản phẩm</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <div class="form-text mt-2">Tối ưu nhất là ảnh vuông, sáng và rõ sản phẩm.</div>
                            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                            <div class="form-check mt-4 p-3 rounded-4" style="background: #f7fbff; border: 1px solid #dbe7f6;">
                                <input type="checkbox" name="is_featured" class="form-check-input" id="featured" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="featured">Đánh dấu là sản phẩm nổi bật</label>
                                <div class="form-text">Hiển thị ở khu vực ưu tiên trên website.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end flex-wrap">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 py-2">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-save me-1"></i> Lưu sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection