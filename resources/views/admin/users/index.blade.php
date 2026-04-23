@extends('admin.layouts.admin')

@php
    $isAdminList = ($accountType ?? 'customers') === 'admins';
    $currentListRoute = $isAdminList ? 'admin.users.admins' : 'admin.users.index';
@endphp

@section('title', $isAdminList ? 'Quản lý Admin' : 'Quản lý Khách hàng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">{{ $isAdminList ? 'Quản lý Admin' : 'Quản lý Khách hàng' }}</h3>
            <small class="text-muted">{{ $isAdminList ? 'Danh sách tài khoản quản trị viên' : 'Danh sách tài khoản khách hàng' }}</small>
        </div>
        <div class="d-flex align-items-center gap-2">
            @if($isAdminList)
                <a href="{{ route('admin.users.create') }}" class="btn btn-success btn-sm">
                    <i class="fas fa-plus"></i> Thêm Admin
                </a>
            @endif
            <span class="badge bg-primary px-3 py-2 fs-6">
                Tổng số: {{ $users->total() }} {{ $isAdminList ? 'admin' : 'khách hàng' }}
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="mb-3 d-flex gap-2">
        <a href="{{ route('admin.users.admins') }}" class="btn btn-sm {{ $isAdminList ? 'btn-primary' : 'btn-outline-primary' }}">Admin</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-sm {{ ! $isAdminList ? 'btn-primary' : 'btn-outline-primary' }}">Khách hàng</a>
    </div>

    <!-- Thanh tìm kiếm -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route($currentListRoute) }}" class="row g-3">
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Tìm theo tên, email hoặc số điện thoại..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route($currentListRoute) }}" class="btn btn-secondary w-100">
                        Làm mới
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bảng dữ liệu -->
    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover align-middle admin-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="25%">Tên tài khoản</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày đăng ký</th>
                        <th width="180px">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="fw-medium">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone ?? 'Chưa cập nhật' }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if(! $isAdminList)
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info me-1" title="Xem chi tiết">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-1" title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @else
                                <span class="text-muted small">Được lưu ở bảng admins</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Modal Xóa -->
                    @if(! $isAdminList)
                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Xác nhận xóa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc muốn xóa tài khoản <strong>"{{ $user->name }}"</strong> không?
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
                    @endif

                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            {{ $isAdminList ? 'Chưa có tài khoản admin nào.' : 'Chưa có khách hàng nào.' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection