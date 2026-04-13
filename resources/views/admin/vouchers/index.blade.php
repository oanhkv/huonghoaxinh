@extends('admin.layouts.admin')

@section('title', 'Quản lý Mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0">Quản lý Mã giảm giá</h3>
            <small class="text-muted">Tạo mã giảm theo phần trăm hoặc số tiền cố định</small>
        </div>
        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">+ Tạo mã mới</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.vouchers.index') }}" class="row g-3">
                <div class="col-md-9">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo mã hoặc tên voucher..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã</th>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Điều kiện</th>
                            <th>Hiệu lực</th>
                            <th>Trạng thái</th>
                            <th width="140">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $voucher)
                            <tr>
                                <td><strong>{{ $voucher->code }}</strong></td>
                                <td>{{ $voucher->name }}</td>
                                <td>
                                    <span class="badge bg-{{ $voucher->type === 'percent' ? 'success' : 'primary' }}">
                                        {{ $voucher->type === 'percent' ? 'Phần trăm' : 'Số tiền cố định' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $voucher->type === 'percent' ? $voucher->value . '%' : number_format($voucher->value) . 'đ' }}
                                </td>
                                <td>
                                    @if($voucher->min_order_amount)
                                        Đơn tối thiểu {{ number_format($voucher->min_order_amount) }}đ
                                    @else
                                        Không yêu cầu
                                    @endif
                                </td>
                                <td>
                                    {{ $voucher->starts_at ? $voucher->starts_at->format('d/m/Y') : 'Không giới hạn' }}
                                    -
                                    {{ $voucher->ends_at ? $voucher->ends_at->format('d/m/Y') : 'Không giới hạn' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $voucher->is_active ? 'success' : 'secondary' }}">
                                        {{ $voucher->is_active ? 'Đang hoạt động' : 'Tạm tắt' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa voucher này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">Chưa có mã giảm giá nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $vouchers->links() }}
        </div>
    </div>
</div>
@endsection