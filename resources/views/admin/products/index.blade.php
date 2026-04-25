@extends('admin.layouts.admin')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- <h3 class="fw-bold">Quản lý Sản phẩm</h3> --}}
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.products.export') }}" class="btn btn-outline-success">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
            <form id="product-import-form" action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="product-import-file" name="file" class="d-none" accept=".xlsx,.xls,.csv" required>
                <button type="button" class="btn btn-outline-primary btn-sm" id="product-import-button">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
            </form>
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm sản phẩm mới
            </a>
        </div>
    </div>

    @if(session('warning'))
        <div class="alert alert-warning">
            <div>{{ session('warning') }}</div>
            @if(session('import_errors'))
                <ul class="mb-0 mt-2">
                    @foreach(session('import_errors') as $importError)
                        <li>{{ $importError }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <!-- Form Tìm kiếm + Lọc -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.products.index') }}">
                <div class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm theo tên sản phẩm..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="category_id" class="form-select">
                            <option value="">-- Tất cả danh mục --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="stock_status" class="form-select">
                            <option value="">-- Trạng thái kho --</option>
                            <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng sản phẩm -->
    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover admin-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="80">Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá bán</th>
                        <th>Kho hàng</th>
                        <th>Trạng thái</th>
                        <th width="150">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            @if($product->image)
                                <img src="{{ $product->image_url }}" 
                                     alt="{{ $product->name }}" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <img src="{{ asset('images/product-placeholder.svg') }}" alt="Placeholder" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            @endif
                        </td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->is_featured)
                                <span class="badge bg-warning ms-2">Nổi bật</span>
                            @endif
                        </td>
                        <td>{{ $product->category->name ?? 'Chưa phân loại' }}</td>
                        <td class="fw-bold text-danger">{{ number_format($product->price) }}đ</td>
                        <td>
                            @if($product->stock > 0)
                                <span class="badge bg-success">{{ $product->stock }}</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success">Đang bán</span>
                            @else
                                <span class="badge bg-secondary">Ngừng bán</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Xóa -->
                    <div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Xác nhận xóa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc muốn xóa sản phẩm <strong>"{{ $product->name }}"</strong> không?
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">Chưa có sản phẩm nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const importButton = document.getElementById('product-import-button');
        const importFile = document.getElementById('product-import-file');
        const importForm = document.getElementById('product-import-form');

        if (importButton && importFile && importForm) {
            importButton.addEventListener('click', function () {
                importFile.click();
            });

            importFile.addEventListener('change', function () {
                if (importFile.files.length > 0) {
                    importForm.submit();
                }
            });
        }
    });
</script>
@endsection