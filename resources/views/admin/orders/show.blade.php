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
                        {{-- ============ Người gửi ============ --}}
                        <div class="col-md-6">
                            <div class="hhx-info-card hhx-info-sender">
                                <div class="hhx-info-head">
                                    <span class="hhx-info-icon"><i class="fas fa-user-pen"></i></span>
                                    <span>Người gửi</span>
                                </div>
                                <div class="hhx-info-body">
                                    <div class="hhx-info-line"><span>Họ tên:</span> <strong>{{ $order->sender_name ?: ($order->user->name ?? '—') }}</strong></div>
                                    <div class="hhx-info-line"><span>Điện thoại:</span> <strong>{{ $order->sender_phone ?: '—' }}</strong></div>
                                    @if($order->user)
                                        <div class="hhx-info-line"><span>Email TK:</span> <strong>{{ $order->user->email }}</strong></div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ============ Người nhận ============ --}}
                        <div class="col-md-6">
                            <div class="hhx-info-card hhx-info-recipient">
                                <div class="hhx-info-head">
                                    <span class="hhx-info-icon"><i class="fas fa-gift"></i></span>
                                    <span>Người nhận</span>
                                </div>
                                <div class="hhx-info-body">
                                    <div class="hhx-info-line"><span>Họ tên:</span> <strong>{{ $order->recipient_name ?: '—' }}</strong></div>
                                    <div class="hhx-info-line"><span>Điện thoại:</span> <strong>{{ $order->phone ?: '—' }}</strong></div>
                                    <div class="hhx-info-line"><span>Địa chỉ:</span> <strong>{{ $order->shipping_address ?: '—' }}</strong></div>
                                    @if($order->delivery_date)
                                        <div class="hhx-info-line">
                                            <span>Giao lúc:</span>
                                            <strong>{{ $order->delivery_date->format('d/m/Y') }}@if($order->delivery_time_slot) · {{ $order->delivery_time_slot }} @endif</strong>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ============ Lời nhắn cho người nhận ============ --}}
                        @if($order->recipient_message)
                            <div class="col-12">
                                <div class="hhx-message-card">
                                    <div class="hhx-message-label">
                                        <i class="far fa-envelope me-1"></i>Lời nhắn gửi người nhận
                                    </div>
                                    <div class="hhx-message-body">{{ $order->recipient_message }}</div>
                                </div>
                            </div>
                        @endif

                        {{-- ============ Tổng tiền & ghi chú shop ============ --}}
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Tổng tiền</h6>
                            <div class="hhx-total-big">{{ number_format($order->total_amount) }}đ</div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold mb-2">Ghi chú dành cho shop</h6>
                            <p class="mb-0 text-muted small" style="white-space:pre-line;">{{ $order->note ?: 'Không có ghi chú.' }}</p>
                        </div>
                    </div>
                </div>

                <style>
                    .hhx-info-card {
                        border-radius: 12px; padding: 16px;
                        background: #f9fafb;
                        border: 1px solid #f1f2f6;
                        height: 100%;
                    }
                    .hhx-info-sender { background: linear-gradient(135deg, #eef2ff, #f5f3ff); border-color: #e0e7ff; }
                    .hhx-info-recipient { background: linear-gradient(135deg, #ecfdf5, #f0fdf4); border-color: #d1fae5; }
                    .hhx-info-head {
                        display: flex; align-items: center; gap: 8px;
                        font-weight: 700; font-size: 14px; color: #1f2937;
                        margin-bottom: 10px;
                    }
                    .hhx-info-icon {
                        width: 28px; height: 28px; border-radius: 8px;
                        display: inline-flex; align-items: center; justify-content: center;
                        background: rgba(0,0,0,.05); color: #1f2937;
                    }
                    .hhx-info-sender .hhx-info-icon { background: rgba(99,102,241,.15); color: #4f46e5; }
                    .hhx-info-recipient .hhx-info-icon { background: rgba(25,135,84,.15); color: #198754; }
                    .hhx-info-line {
                        font-size: 13px; color: #374151;
                        margin-bottom: 4px;
                    }
                    .hhx-info-line span { color: #6b7280; min-width: 78px; display: inline-block; }
                    .hhx-message-card {
                        padding: 14px 16px;
                        border-radius: 12px;
                        background: linear-gradient(135deg, #fef9c3, #fef3c7);
                        border: 1px dashed #fbbf24;
                    }
                    .hhx-message-label {
                        font-weight: 700; font-size: 13px; color: #92400e;
                        margin-bottom: 6px;
                    }
                    .hhx-message-body {
                        font-style: italic; color: #1f2937;
                        white-space: pre-line;
                    }
                    .hhx-total-big {
                        font-size: 24px; font-weight: 800;
                        color: #d63384;
                    }
                </style>
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