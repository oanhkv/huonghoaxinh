@extends('frontend.layouts.app')

@section('title', 'Thanh toán - Hương Hoa Xinh')

@section('content')
<div class="container py-5" id="checkoutPage"
     data-subtotal="{{ $subtotal }}"
     data-shipping="{{ $shipping }}"
     data-discount="{{ $discount }}"
     data-estimate-url="{{ route('shipping.estimate') }}"
     data-voucher-url="{{ route('checkout.apply-voucher') }}"
     data-csrf="{{ csrf_token() }}">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
            @if(empty($isBuyNow))
                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Giỏ hàng</a></li>
            @endif
            <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
        </ol>
    </nav>

    <h2 class="mb-4"><i class="fas fa-credit-card me-2 text-success"></i>Thanh toán đơn hàng</h2>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0">Thông tin giao hàng</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('checkout.process') }}">
                        @csrf
                        @if(!empty($isBuyNow))
                            <input type="hidden" name="checkout_source" value="buy_now">
                        @endif

                        @if(isset($pendingOrder) && $pendingOrder->status === 'pending')
                            <div class="alert alert-info">
                                Bạn đang có đơn hàng chờ thanh toán thẻ. Nhấn "Tiếp tục" để mở lại trang thanh toán.
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="shippingAddress" class="form-label">Địa chỉ giao hàng</label>
                            <textarea name="shipping_address" id="shippingAddress" class="form-control @error('shipping_address') is-invalid @enderror" rows="3" required placeholder="Ví dụ: Số nhà, ngõ, phường/xã, quận/huyện, Hà Nội">{{ $shippingAddress ?? '' }}</textarea>
                            <div class="form-text">
                                <i class="fas fa-store me-1 text-success"></i>
                                Tính phí ship từ cửa hàng: <strong>{{ config('shop.address_line') }}</strong>
                            </div>
                            <div id="distanceInfo" class="small mt-2 {{ trim((string)($shippingAddress ?? '')) !== '' ? '' : 'd-none' }}">
                                Khoảng cách ước tính: <strong id="distanceKmLabel">{{ number_format($distanceKm ?? 0, 1, ',', '.') }}</strong> km
                                @if(isset($distanceGeocoded) && ! $distanceGeocoded)
                                    <span class="text-warning">(hệ thống chưa xác định chính xác địa chỉ — dùng mức phí an toàn)</span>
                                @endif
                            </div>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" name="phone" id="phone" value="{{ $phone ?? '' }}" class="form-control @error('phone') is-invalid @enderror" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="voucherCode" class="form-label">Mã giảm giá</label>
                            <div class="input-group">
                                <input type="text" name="voucher_code" id="voucherCode" value="{{ $voucherCode ?? '' }}" class="form-control @error('voucher_code') is-invalid @enderror" placeholder="Nhập mã giảm giá nếu có">
                                <button class="btn btn-outline-success" type="button" id="applyVoucherBtn">Áp dụng</button>
                            </div>
                            <div id="voucherFeedback" class="small mt-2"></div>
                            @error('voucher_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phương thức thanh toán</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paymentCod" value="cod" {{ ($paymentMethod ?? 'cod') === 'cod' ? 'checked' : '' }}>
                                <label class="form-check-label" for="paymentCod">Thanh toán khi nhận hàng (COD)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="paymentCard" value="card" {{ ($paymentMethod ?? '') === 'card' ? 'checked' : '' }}>
                                <label class="form-check-label" for="paymentCard">Thanh toán bằng thẻ (qua liên kết cổng thanh toán)</label>
                            </div>
                            @error('payment_method')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú đơn hàng</label>
                            <textarea name="note" id="note" class="form-control" rows="3">{{ $note ?? '' }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-check-circle me-2"></i>Tiếp tục thanh toán</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-light border-bottom py-3">
                    <h6 class="mb-0">Tóm tắt đơn hàng</h6>
                </div>
                <div class="card-body">
                    @php $groupedItems = $cartItems->groupBy('product_id'); @endphp
                    @foreach($groupedItems as $items)
                        @php $firstItem = $items->first(); @endphp
                        <div class="mb-3">
                            <div class="fw-bold">{{ $firstItem->product->name }}</div>
                            @foreach($items as $item)
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <div>
                                        <div class="small text-muted">{{ $item->variant ?: 'Mặc định' }}</div>
                                        <div class="small">{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }} ₫</div>
                                    </div>
                                    <div class="fw-bold text-success">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} ₫</div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach

                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính</span>
                        <span class="fw-bold">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển</span>
                        <span class="fw-bold text-success" id="shippingPreview">{{ $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . ' ₫' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Giảm giá</span>
                        <span class="fw-bold text-danger" id="discountPreview">-{{ number_format($discount ?? 0, 0, ',', '.') }} ₫</span>
                    </div>
                    <div class="d-flex justify-content-between fs-5 fw-bold pt-2 border-top">
                        <span>Tổng cộng</span>
                        <span class="text-success" id="totalPreview">{{ number_format($total, 0, ',', '.') }} ₫</span>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm bg-light p-3">
                <p class="mb-2"><strong>Ghi chú:</strong></p>
                <p class="small text-muted mb-1"><i class="fas fa-info-circle me-2"></i>Khi chọn thanh toán thẻ, hệ thống sẽ hiển thị liên kết thanh toán và hóa đơn chi tiết.</p>
                <p class="small text-muted mb-0"><i class="fas fa-shield-alt me-2"></i>Đơn hàng sẽ chuyển trạng thái xác nhận ngay sau khi hoàn tất thanh toán.</p>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const root = document.getElementById('checkoutPage');
    const addr = document.getElementById('shippingAddress');
    const shippingPreview = document.getElementById('shippingPreview');
    const discountPreview = document.getElementById('discountPreview');
    const totalPreview = document.getElementById('totalPreview');
    const distanceInfo = document.getElementById('distanceInfo');
    const distanceKmLabel = document.getElementById('distanceKmLabel');
    const voucherInput = document.getElementById('voucherCode');
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const voucherFeedback = document.getElementById('voucherFeedback');
    const checkoutSource = document.querySelector('input[name="checkout_source"]')?.value || '';
    if (!root || !addr || !shippingPreview || !discountPreview || !totalPreview) return;

    const subtotal = parseFloat(root.dataset.subtotal || '0');
    let currentDiscount = parseFloat(root.dataset.discount || '0');
    let currentShipping = parseFloat(root.dataset.shipping || '0');
    const estimateUrl = root.dataset.estimateUrl;
    const voucherUrl = root.dataset.voucherUrl;
    const csrf = root.dataset.csrf;
    let debounceTimer = null;

    function formatMoney(n) {
        return new Intl.NumberFormat('vi-VN').format(n) + ' ₫';
    }

    function renderSummary() {
        shippingPreview.textContent = currentShipping === 0 ? 'Miễn phí' : formatMoney(currentShipping);
        discountPreview.textContent = '-' + formatMoney(currentDiscount);
        const total = Math.max(0, subtotal + currentShipping - currentDiscount);
        totalPreview.textContent = formatMoney(total);
    }

    function setVoucherFeedback(message, type) {
        if (!voucherFeedback) return;
        voucherFeedback.textContent = message || '';
        voucherFeedback.className = 'small mt-2';
        if (type === 'success') {
            voucherFeedback.classList.add('text-success');
        } else if (type === 'error') {
            voucherFeedback.classList.add('text-danger');
        } else {
            voucherFeedback.classList.add('text-muted');
        }
    }

    function estimate() {
        const text = (addr.value || '').trim();
        if (text.length < 8) {
            return;
        }
        fetch(estimateUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ shipping_address: text })
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            if (!data.success) return;
            if (distanceInfo && distanceKmLabel) {
                distanceInfo.classList.remove('d-none');
                distanceKmLabel.textContent = new Intl.NumberFormat('vi-VN', { maximumFractionDigits: 1 }).format(data.distance_km);
            }
            currentShipping = parseInt(data.shipping, 10) || 0;
            renderSummary();
        })
        .catch(function () { /* giữ giá trị server render */ });
    }

    function applyVoucher() {
        if (!voucherInput || !voucherUrl) return;
        const voucherCode = (voucherInput.value || '').trim();
        const shippingAddress = (addr.value || '').trim();

        applyVoucherBtn?.setAttribute('disabled', 'disabled');
        setVoucherFeedback('Đang kiểm tra mã giảm giá...', 'muted');

        fetch(voucherUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                voucher_code: voucherCode,
                shipping_address: shippingAddress,
                checkout_source: checkoutSource
            })
        })
        .then(function (r) {
            return r.json().then(function (data) {
                return { ok: r.ok, data: data };
            });
        })
        .then(function (res) {
            if (!res.ok || !res.data.success) {
                throw new Error(res.data.message || 'Mã giảm giá không hợp lệ.');
            }

            currentShipping = parseFloat(res.data.shipping || 0);
            currentDiscount = parseFloat(res.data.discount || 0);
            renderSummary();
            setVoucherFeedback(res.data.message || 'Áp dụng mã thành công.', 'success');
        })
        .catch(function (err) {
            currentDiscount = 0;
            renderSummary();
            setVoucherFeedback(err.message || 'Không áp dụng được mã giảm giá.', 'error');
        })
        .finally(function () {
            applyVoucherBtn?.removeAttribute('disabled');
        });
    }

    addr.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(estimate, 550);
    });

    applyVoucherBtn?.addEventListener('click', applyVoucher);
    voucherInput?.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter') {
            ev.preventDefault();
            applyVoucher();
        }
    });

    if ((addr.value || '').trim().length >= 8) {
        estimate();
    }
    renderSummary();
})();
</script>
@endsection
