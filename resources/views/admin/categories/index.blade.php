@extends('admin.layouts.admin')

@section('title', 'Quản lý Danh mục')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
       <h3 class="fw-bold">Quản lý Danh mục Hoa</h3>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('admin.categories.export') }}" class="btn btn-outline-success">
                <i class="fas fa-file-excel"></i> Xuất Excel
            </a>
            <form id="category-import-form" action="{{ route('admin.categories.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="category-import-file" name="file" class="d-none" accept=".xlsx,.xls,.csv" required>
                <button type="button" class="btn btn-outline-primary btn-sm" id="category-import-button">
                    <i class="fas fa-file-import"></i> Import Excel
                </button>
            </form>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Thêm danh mục mới
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

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover admin-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Số sản phẩm</th>
                        <th>Danh mục cha</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td><code>{{ $category->slug }}</code></td>
                        <td>
                            <span class="badge bg-primary">{{ $category->products_count }}</span>
                        </td>
                        <td>{{ $category->parent ? $category->parent->name : '—' }}</td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-danger" 
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $category->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Xóa -->
                    <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Xác nhận xóa</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    Bạn có chắc muốn xóa danh mục <strong>"{{ $category->name }}"</strong> không?
                                    @if($category->products_count > 0)
                                        <br><span class="text-danger">Cảnh báo: Danh mục này đang có {{ $category->products_count }} sản phẩm!</span>
                                    @endif
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    @empty
                    <tr><td colspan="5" class="text-center py-4">Chưa có danh mục nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $categories->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const importButton = document.getElementById('category-import-button');
        const importFile = document.getElementById('category-import-file');
        const importForm = document.getElementById('category-import-form');

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