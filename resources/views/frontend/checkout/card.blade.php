@extends('frontend.layouts.app')

@section('title', 'Thanh toán chuyển khoản - Hương Hoa Xinh')

@section('content')
@php
    $amountFormatted = number_format($order->total_amount, 0, ',', '.');
@endphp
<div class="container py-5 checkout-pay-page">
    <div class="row g-4 align-items-start">
        <div class="col-lg-7">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden pay-card">
                <div class="pay-card-head text-white p-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <div>
                            <span class="badge bg-white bg-opacity-25 rounded-pill px-3 py-2 small">Đơn {{ $order->order_code }}</span>
                            <h3 class="fw-bold mt-3 mb-1">Quét mã để thanh toán</h3>
                            <p class="mb-0 opacity-90 small">QR chuẩn VietQR — mở app ngân hàng hoặc ví có hỗ trợ quét VietQR.</p>
                        </div>
                        <div class="text-end">
                            <div class="small opacity-75">Nhà cung cấp QR</div>
                            <span class="fw-bold text-uppercase">{{ $paymentQr['provider'] ?? 'vietqr' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 p-lg-5">
                    <div class="text-center mb-4">
                        <div class="qr-frame d-inline-block p-3 rounded-4 bg-white shadow-sm">
                            <img src="{{ $paymentQr['image_url'] }}" alt="Mã QR thanh toán VietQR" class="img-fluid rounded-3" width="320" height="320" loading="lazy">
                        </div>
                        <p class="small text-muted mt-3 mb-0">Nếu không quét được, hãy chuyển khoản thủ công theo thông tin bên dưới (đúng số tiền và nội dung).</p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="small text-muted">Ngân hàng (mã BIN VietQR)</div>
                                <div class="fw-bold fs-5 font-monospace">{{ $vietqrBankId }}</div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" data-copy="{{ $vietqrBankId }}">Sao chép</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="small text-muted">Số tài khoản nhận</div>
                                <div class="fw-bold fs-5 font-monospace">{{ $vietqrAccountNo }}</div>
                                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" data-copy="{{ $vietqrAccountNo }}">Sao chép</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="small text-muted">Chủ tài khoản</div>
                                <div class="fw-bold">{{ $vietqrAccountName }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border bg-light h-100">
                                <div class="small text-muted">{{ $momoDisplayName }}</div>
                                <div class="fw-bold font-monospace">{{ $momoPhone }}</div>
                                <div class="small text-muted mt-1">Có thể chuyển qua MoMo bằng số điện thoại nếu trùng với STK nhận.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border border-success bg-success bg-opacity-10 h-100">
                                <div class="small text-muted">Số tiền</div>
                                <div class="fw-bold text-success fs-4">{{ $amountFormatted }} ₫</div>
                                <button type="button" class="btn btn-sm btn-success mt-2" data-copy="{{ (int) round($order->total_amount) }}">Sao chép số tiền (số nguyên)</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 rounded-3 border border-primary bg-primary bg-opacity-10 h-100">
                                <div class="small text-muted">Nội dung chuyển khoản</div>
                                <div class="fw-bold font-monospace">{{ $paymentQr['add_info'] ?? $order->order_code }}</div>
                                <button type="button" class="btn btn-sm btn-primary mt-2" data-copy="{{ $paymentQr['add_info'] ?? $order->order_code }}">Sao chép nội dung</button>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 small mb-4">
                        <strong>Cấu hình cho đúng tài khoản nhận tiền:</strong> trong file <code>.env</code> đặt <code>VIETQR_BANK_ID</code> (6 số BIN đúng ngân hàng của STK),
                        <code>VIETQR_ACCOUNT_NO</code>, <code>VIETQR_ACCOUNT_NAME</code>. Hoặc dùng SePay: <code>PAYMENT_QR_PROVIDER=sepay</code> kèm <code>SEPAY_BANK</code>, <code>SEPAY_ACCOUNT</code>.
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-2">
                        <form action="{{ route('checkout.card.confirm', $order) }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="btn btn-success btn-lg px-5 rounded-pill shadow">
                                <i class="fas fa-check-circle me-2"></i>Tôi đã chuyển khoản
                            </button>
                        </form>
                        <a href="{{ route('orders.history') }}" class="btn btn-outline-secondary btn-lg rounded-pill">Xem đơn hàng</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 sticky-lg-top" style="top: 1rem;">
                <div class="card-header bg-light border-0 py-3 rounded-top-4">
                    <h6 class="mb-0 fw-bold">Hóa đơn giao hàng</h6>
                </div>
                <div class="card-body">
                    @foreach($order->orderItems as $item)
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <div>
                                <div class="fw-semibold">{{ $item->product->name }}</div>
                                <div class="small text-muted">{{ $item->quantity }} × {{ number_format($item->price, 0, ',', '.') }} ₫</div>
                            </div>
                            <div class="fw-semibold text-success">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} ₫</div>
                        </div>
                    @endforeach
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Người nhận</span>
                        <span class="fw-semibold">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">SĐT</span>
                        <span class="fw-semibold">{{ $order->phone }}</span>
                    </div>
                    <div class="mb-3">
                        <div class="small text-muted">Địa chỉ giao hàng</div>
                        <div>{{ $order->shipping_address }}</div>
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold pt-3 border-top">
                        <span>Tổng thanh toán</span>
                        <span class="text-success">{{ $amountFormatted }} ₫</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-pay-page .pay-card-head {
    background: linear-gradient(135deg, #157347 0%, #0d6efd 55%, #6f42c1 100%);
}
.checkout-pay-page .qr-frame {
    border: 1px solid rgba(25, 135, 84, 0.15);
}
</style>

<script>
document.querySelectorAll('[data-copy]').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var text = this.getAttribute('data-copy') || '';
        if (!text) return;
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showNotification('Đã sao chép vào clipboard', 'success');
            }).catch(function() {
                showNotification('Không sao chép được, vui lòng chọn và copy thủ công.', 'error');
            });
        } else {
            showNotification('Trình duyệt không hỗ trợ sao chép tự động.', 'info');
        }
    });
});
</script>
@endsection
