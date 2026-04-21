@extends('admin.layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . ($order->order_code ?? $order->id))

@section('content')
@php
    $status = $order->status;
    $statusClass = match ($status) {
        'completed' => 'success',
        'delivered' => 'success',
        'shipping' => 'primary',
        'paid' => 'info',
        'cod' => 'secondary',
        'cancelled' => 'danger',
        'confirmed' => 'info',
        default => 'warning',
    };
@endphp

<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold">Chi tiết đơn hàng #{{ $order->order_code ?? $order->id }}</h5>
                        <small class="text-muted">Đơn được tạo lúc {{ $order->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <span class="badge bg-{{ $statusClass }} px-3 py-2">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Thông tin khách hàng</h6>
                            <div class="mb-2"><strong>Tên:</strong> {{ $order->user->name ?? 'Khách vãng lai' }}</div>
                            <div class="mb-2"><strong>Điện thoại:</strong> {{ $order->phone }}</div>
                            <div class="mb-2"><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-3">Thông tin đơn hàng</h6>
                            <div class="mb-2"><strong>Tổng tiền:</strong> <span class="text-danger fw-bold">{{ number_format($order->total_amount) }}đ</span></div>
                            <div class="mb-2">
                                <strong>Trạng thái hiện tại:</strong>
                                <span class="badge bg-{{ $statusClass }} ms-1">{{ ucfirst($status) }}</span>
                            </div>
                            @if($order->note)
                                <div class="mb-2"><strong>Ghi chú:</strong> {{ $order->note }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Cập nhật trạng thái</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ $status === 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="shipping" {{ $status === 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="delivered" {{ $status === 'delivered' ? 'selected' : '' }}>Đã giao thành công</option>
                                <option value="paid" {{ $status === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="cod" {{ $status === 'cod' ? 'selected' : '' }}>Chờ thu COD</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h6 class="mb-0 fw-bold">Ghi chú</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0 text-muted">{{ $order->note ?? 'Không có ghi chú.' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mt-4">
        <div class="card-header bg-white">
            <h6 class="mb-0 fw-bold">Danh sách sản phẩm</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price) }}đ</td>
                                <td>{{ number_format($item->price * $item->quantity) }}đ</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">Đơn hàng chưa có sản phẩm.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection