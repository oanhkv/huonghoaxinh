@extends('admin.layouts.admin')

@section('title', 'Quản lý Đơn hàng')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold">Quản lý Đơn hàng</h3>
    </div>

    <!-- Tìm kiếm và lọc -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="row g-3">
                    <div class="col-md-5">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Tìm theo mã đơn hoặc tên khách hàng..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4">
                        <select name="status" class="form-select">
                            <option value="">-- Tất cả trạng thái --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body p-0">
            <table class="table table-hover admin-table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->order_code ?? 'HH'.$order->id }}</strong></td>
                        <td>{{ $order->user->name ?? 'Khách vãng lai' }}</td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="fw-bold text-danger">{{ number_format($order->total_amount) }}đ</td>
                        <td>
                            <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'shipping' ? 'primary' : 'warning') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> Chi tiết
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-4">Chưa có đơn hàng nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection