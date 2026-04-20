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

                <div class="admin-section-title">Kích cỡ & Giá tương ứng</div>
                <div class="admin-form-panel mb-4">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-ruler-combined me-2"></i>Quản lý kích cỡ sản phẩm
                        </label>
                        <small class="d-block text-muted mb-3">Thêm các kích cỡ khác nhau với mức giá riêng (40cm, 50cm, 60cm, ...)</small>
                        
                        <div id="sizeContainer" class="mb-3">
                            <!-- Size rows will be added here -->
                        </div>

                        <button type="button" class="btn btn-outline-success btn-sm" onclick="addSizeRow()">
                            <i class="fas fa-plus me-1"></i> Thêm kích cỡ
                        </button>
                    </div>

                    <!-- Hidden input to store sizes as JSON -->
                    <input type="hidden" name="sizes" id="sizesInput" value="{{ json_encode($product->sizes ?? []) }}">
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

<style>
    .size-row {
        display: flex;
        gap: 10px;
        margin-bottom: 12px;
        align-items: flex-end;
    }
    .size-row input {
        margin-bottom: 0;
    }
    .size-row .form-control, .size-row .btn {
        height: 38px;
    }
    .size-row .btn-danger {
        padding: 0.375rem 0.75rem;
    }
</style>

<script>
    let sizeRowCount = 0;

    // Initialize size rows from existing data
    document.addEventListener('DOMContentLoaded', function() {
        const sizesInput = document.getElementById('sizesInput');
        try {
            const sizes = JSON.parse(sizesInput.value);
            if (Array.isArray(sizes) && sizes.length > 0) {
                sizes.forEach(size => {
                    addSizeRow(size.size, size.price);
                });
            } else {
                addSizeRow();
            }
        } catch(e) {
            addSizeRow();
        }
    });

    function addSizeRow(sizeValue = '', priceValue = '') {
        const container = document.getElementById('sizeContainer');
        const rowId = 'size-row-' + (sizeRowCount++);
        
        const row = document.createElement('div');
        row.className = 'size-row';
        row.id = rowId;
        
        row.innerHTML = `
            <div style="flex: 1;">
                <input type="text" class="form-control size-input" placeholder="Kích cỡ (ví dụ: 40cm, 50cm, 60cm)" 
                       value="${sizeValue}" required>
            </div>
            <div style="width: 150px;">
                <div class="input-group">
                    <input type="number" class="form-control price-input" placeholder="Giá thêm" 
                           value="${priceValue}" min="0" step="1000">
                    <span class="input-group-text">₫</span>
                </div>
            </div>
            <button type="button" class="btn btn-danger btn-sm" onclick="removeSizeRow('${rowId}')">
                <i class="fas fa-trash"></i>
            </button>
        `;
        
        container.appendChild(row);
        updateSizesInput();
    }

    function removeSizeRow(rowId) {
        const row = document.getElementById(rowId);
        if (row) {
            row.remove();
            updateSizesInput();
        }
    }

    function updateSizesInput() {
        const container = document.getElementById('sizeContainer');
        const sizes = [];
        
        container.querySelectorAll('.size-row').forEach(row => {
            const size = row.querySelector('.size-input').value.trim();
            const price = parseInt(row.querySelector('.price-input').value) || 0;
            
            if (size) {
                sizes.push({ size, price });
            }
        });
        
        document.getElementById('sizesInput').value = JSON.stringify(sizes);
    }

    // Update sizes input when any size field changes
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('size-input') || e.target.classList.contains('price-input')) {
            updateSizesInput();
        }
    });
</script>
@endsection