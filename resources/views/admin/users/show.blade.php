@extends('admin.layouts.admin')

@section('title', 'Chi tiết tài khoản - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">Thông tin tài khoản: {{ $user->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th>Tên đầy đủ:</th><td>{{ $user->name }}</td></tr>
                                <tr><th>Email:</th><td>{{ $user->email }}</td></tr>
                                <tr><th>Số điện thoại:</th><td>{{ $user->phone ?? 'Chưa cập nhật' }}</td></tr>
                                <tr><th>Địa chỉ:</th><td>{{ $user->address ?? 'Chưa cập nhật' }}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th>Ngày đăng ký:</th><td>{{ $user->created_at->format('d/m/Y H:i') }}</td></tr>
                                <tr>
                                    <th>Vai trò:</th>
                                    <td>
                                        <span class="badge bg-{{ $user->hasRole('admin') ? 'danger' : 'primary' }}">
                                            {{ $user->hasRole('admin') ? 'Admin' : 'Khách hàng' }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Hành động</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning w-100 mb-2">
                        ✏️ Chỉnh sửa thông tin
                    </a>
                    @if(! $user->hasRole('admin'))
                        <button type="button" class="btn btn-danger w-100" 
                                data-bs-toggle="modal" data-bs-target="#deleteModal">
                            🗑️ Xóa tài khoản
                        </button>
                    @else
                        <div class="alert alert-info mb-0">Tài khoản admin không cho phép xóa từ màn hình này.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Xóa -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc muốn xóa tài khoản <strong>{{ $user->name }}</strong> không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection