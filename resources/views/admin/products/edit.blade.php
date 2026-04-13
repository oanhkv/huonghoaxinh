@extends('admin.layouts.admin')

@section('title', 'Sửa Sản Phẩm')

@section('content')
<div class="container-fluid admin-form-shell">
    <div class="card admin-form-card shadow-sm">
        <div class="admin-form-hero p-4 p-lg-5">
            <div class="d-flex align-items-start gap-3 flex-wrap">
                <div class="admin-form-icon">
                    <i class="fas fa-pen-to-square fs-4"></i>
                </div>
                <div class="flex-grow-1">
                    <h3 class="fw-bold mb-2">Sửa Sản Phẩm</h3>
                    <p class="admin-form-subtitle">Cập nhật thông tin sản phẩm {{ $product->name }} với bố cục gọn, dễ đọc và tập trung thao tác.</p>
                </div>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
                </a>
            </div>
        </div>

        <div class="p-4 p-lg-5">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="admin-section-title">Thông tin cơ bản</div>
                <div class="admin-form-panel mb-4">
                    <div class="row g-4">
                        <div class="col-lg-8">
                            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Giá bán (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
                            @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                            <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Số lượng trong kho</label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}" required>
                            @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <div class="admin-section-title">Mô tả & hình ảnh</div>
                <div class="admin-form-panel mb-4">
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-8">
                            <label class="form-label">Mô tả</label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="6">{{ old('description', $product->description) }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-4">
                            <div class="p-3 rounded-4 mb-3" style="background: #f7fbff; border: 1px solid #dbe7f6;">
                                <label class="form-label">Hình ảnh hiện tại</label>
                                <div class="mt-2 text-center">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded-4 border" style="max-height: 210px; object-fit: cover;">
                                    @else
                                        <div class="py-5 text-muted rounded-4 border bg-white">Chưa có ảnh</div>
                                    @endif
                                </div>
                            </div>

                            <label class="form-label">Chọn ảnh mới</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <div class="form-text mt-2">Chỉ chọn ảnh mới nếu muốn thay đổi hình đại diện sản phẩm.</div>
                            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

                            <div class="form-check mt-4 p-3 rounded-4" style="background: #f7fbff; border: 1px solid #dbe7f6;">
                                <input type="checkbox" name="is_featured" class="form-check-input" id="featured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="featured">Sản phẩm nổi bật</label>
                                <div class="form-text">Tăng ưu tiên hiển thị trên website.</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 justify-content-end flex-wrap">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary px-4 py-2">Hủy</a>
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="fas fa-save me-1"></i> Cập nhật sản phẩm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection