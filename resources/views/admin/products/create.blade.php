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
                    <input type="hidden" name="sizes" id="sizesInput" value="{{ old('sizes', '[]') }}">
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
                            <label class="form-label mt-3">Hoặc nhập tên ảnh trong `public/img`</label>
                            <input type="text" name="image_name" class="form-control @error('image_name') is-invalid @enderror" value="{{ old('image_name') }}" placeholder="Ví dụ: hoa-hong-do.jpg hoặc img/hoa-hong-do.jpg">
                            <div class="form-text mt-2">Tối ưu nhất là ảnh vuông, sáng và rõ sản phẩm.</div>
                            <div class="form-text">Nếu nhập tên ảnh, hệ thống sẽ tự lấy từ thư mục `public/img`.</div>
                            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            @error('image_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

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

    // Initialize size rows from old data or add one empty row
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