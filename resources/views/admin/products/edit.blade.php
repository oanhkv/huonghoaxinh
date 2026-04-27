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

                <div class="admin-section-title">Màu sắc & Nguyên liệu</div>
                <div class="admin-form-panel mb-4">
                    @php
                        $currentColors = (array) old('colors', $product->colors ?? []);
                        $currentMaterials = (array) old('materials', $product->materials ?? []);
                    @endphp
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <label class="form-label d-block mb-2">
                                <i class="fas fa-palette me-2"></i>Màu sắc của sản phẩm
                            </label>
                            <small class="d-block text-muted mb-3">Chọn các tone màu chủ đạo để khách lọc nhanh trên trang Shop.</small>
                            <div class="color-swatch-grid">
                                @foreach($colorOptions as $name => $swatch)
                                    <label class="color-swatch">
                                        <input type="checkbox" name="colors[]" value="{{ $name }}" {{ in_array($name, $currentColors, true) ? 'checked' : '' }}>
                                        <span class="swatch-dot" style="background: {{ $swatch }};"></span>
                                        <span class="swatch-label">{{ $name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('colors') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            @error('colors.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-lg-6">
                            <label class="form-label d-block mb-2">
                                <i class="fas fa-leaf me-2"></i>Nguyên liệu / Loại hoa
                            </label>
                            <small class="d-block text-muted mb-3">Tick chọn các loại hoa, lá, baby... có trong sản phẩm.</small>
                            <div class="chip-grid">
                                @foreach($materialOptions as $material)
                                    <label class="chip">
                                        <input type="checkbox" name="materials[]" value="{{ $material }}" {{ in_array($material, $currentMaterials, true) ? 'checked' : '' }}>
                                        <span>{{ $material }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('materials') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                            @error('materials.*') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
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
                                        <img src="{{ $product->image_url }}" class="img-fluid rounded-4 border" style="max-height: 210px; object-fit: cover;">
                                    @else
                                        <div class="py-5 text-muted rounded-4 border bg-white">Chưa có ảnh</div>
                                    @endif
                                </div>
                            </div>

                            <label class="form-label">Chọn ảnh mới</label>
                            <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                            <label class="form-label mt-3">Hoặc nhập tên ảnh trong `public/img`</label>
                            <input type="text" name="image_name" class="form-control @error('image_name') is-invalid @enderror" value="{{ old('image_name', $product->image) }}" placeholder="Ví dụ: hoa-hong-do.jpg hoặc img/hoa-hong-do.jpg">
                            <div class="form-text mt-2">Chỉ chọn ảnh mới nếu muốn thay đổi hình đại diện sản phẩm.</div>
                            @error('image') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            @error('image_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

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

    .color-swatch-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
        gap: 10px;
    }
    .color-swatch {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        border: 1.5px solid #e3e7ef;
        border-radius: 999px;
        background: #fff;
        cursor: pointer;
        transition: all .15s ease;
        margin: 0;
    }
    .color-swatch:hover { border-color: #f0a5c1; }
    .color-swatch input { display: none; }
    .color-swatch .swatch-dot {
        width: 18px; height: 18px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,.08);
        flex: 0 0 auto;
    }
    .color-swatch .swatch-label { font-size: 14px; color: #455167; font-weight: 500; }
    .color-swatch input:checked ~ .swatch-label { color: #c7345b; font-weight: 600; }
    .color-swatch:has(input:checked) {
        border-color: #f06595;
        background: #fff0f6;
        box-shadow: 0 4px 14px rgba(240,101,149,.15);
    }

    .chip-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .chip {
        display: inline-flex;
        align-items: center;
        padding: 7px 14px;
        border: 1.5px solid #e3e7ef;
        border-radius: 999px;
        background: #fff;
        cursor: pointer;
        font-size: 13.5px;
        color: #455167;
        transition: all .15s ease;
        margin: 0;
    }
    .chip:hover { border-color: #9ec5fe; color: #1d4ed8; }
    .chip input { display: none; }
    .chip:has(input:checked) {
        border-color: #6ea8fe;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(110,168,254,.15);
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