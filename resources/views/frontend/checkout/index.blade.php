@extends('frontend.layouts.app')

@section('title', 'Thanh toán')

@section('content')
<div class="hh-checkout-page py-5" id="checkoutPage"
     data-subtotal="{{ $subtotal }}"
     data-shipping="{{ $shipping }}"
     data-discount="{{ $discount }}"
     data-estimate-url="{{ route('shipping.estimate') }}"
     data-voucher-url="{{ route('checkout.apply-voucher') }}"
     data-vouchers-list-url="{{ route('checkout.available-vouchers') }}"
     data-checkout-source="{{ ! empty($isBuyNow) ? 'buy_now' : '' }}"
     data-prefill-voucher="{{ request('voucher', $voucherCode ?? '') }}"
     data-csrf="{{ csrf_token() }}">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                @if(empty($isBuyNow))
                    <li class="breadcrumb-item"><a href="{{ route('cart.index') }}" class="text-decoration-none">Giỏ hàng</a></li>
                @endif
                <li class="breadcrumb-item active" aria-current="page">Thanh toán</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <p class="text-success fw-semibold mb-1 small">💳 Bước cuối</p>
                <h1 class="display-6 fw-bold mb-0">Thanh toán đơn hàng</h1>
                <p class="text-muted mb-0 mt-1">Điền thông tin giao hàng — chọn mã giảm giá phù hợp — chọn cách thanh toán.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
            @csrf
            @if(! empty($isBuyNow))
                <input type="hidden" name="checkout_source" value="buy_now">
            @endif
            <input type="hidden" name="voucher_code" id="voucherCode" value="{{ $voucherCode ?? '' }}">

            <div class="row g-4">
                <div class="col-lg-7">
                    @if(isset($pendingOrder) && $pendingOrder && $pendingOrder->status === 'pending')
                        <div class="hh-alert hh-alert-info mb-4">
                            <i class="fas fa-circle-info me-2"></i>Bạn có đơn hàng chờ thanh toán thẻ. Bấm "Tiếp tục" để mở lại trang thanh toán.
                        </div>
                    @endif

                    <div class="hh-co-card mb-4">
                        <div class="hh-co-card-head d-flex align-items-center justify-content-between">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-truck me-2 text-success"></i>Thông tin giao hàng</h6>
                            @auth
                                <a href="{{ route('profile.edit') }}" class="small text-success text-decoration-none" target="_blank" rel="noopener">
                                    <i class="fas fa-user-pen me-1"></i>Sửa hồ sơ
                                </a>
                            @endauth
                        </div>
                        <div class="p-4">
                            @auth
                                @if(! empty($hasProfileSuggestion))
                                    <div class="hh-profile-suggest mb-3" id="profileSuggestBox"
                                         data-profile-name="{{ $profileName }}"
                                         data-profile-phone="{{ $profilePhone }}"
                                         data-profile-address="{{ $profileAddress }}">
                                        <div class="hh-profile-suggest-icon"><i class="fas fa-id-card"></i></div>
                                        <div class="flex-grow-1 min-w-0">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-bold">Đề xuất từ hồ sơ của bạn</span>
                                                <span class="hh-suggest-badge"><i class="fas fa-circle-check me-1"></i>Đã lưu sẵn</span>
                                            </div>
                                            @if($profileName !== '')
                                                <div class="small text-muted"><i class="fas fa-user me-1"></i>{{ $profileName }}</div>
                                            @endif
                                            @if($profilePhone !== '')
                                                <div class="small text-muted"><i class="fas fa-phone me-1"></i>{{ $profilePhone }}</div>
                                            @endif
                                            @if($profileAddress !== '')
                                                <div class="small text-muted"><i class="fas fa-location-dot me-1"></i>{{ $profileAddress }}</div>
                                            @endif
                                        </div>
                                        <div class="d-flex flex-column gap-1 align-self-stretch justify-content-center">
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3" id="useProfileBtn">
                                                <i class="fas fa-wand-magic-sparkles me-1"></i>Dùng thông tin này
                                            </button>
                                            <button type="button" class="btn btn-sm btn-link text-muted p-0" id="dismissSuggestBtn">
                                                Tự nhập mới
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            @endauth

                            <div class="mb-3">
                                <label for="shippingAddress" class="form-label fw-semibold">Địa chỉ giao hàng *</label>
                                <textarea name="shipping_address" id="shippingAddress" class="form-control hh-input @error('shipping_address') is-invalid @enderror" rows="3" required placeholder="Số nhà, ngõ, phường/xã, quận/huyện, Hà Nội">{{ $shippingAddress ?? '' }}</textarea>
                                <div class="form-text small mt-1">
                                    <i class="fas fa-store me-1 text-success"></i>
                                    Phí ship tính từ cửa hàng: <strong>{{ config('shop.address_line') }}</strong>
                                </div>
                                <div id="distanceInfo" class="small mt-2 text-muted {{ trim((string)($shippingAddress ?? '')) !== '' ? '' : 'd-none' }}">
                                    Khoảng cách ước tính: <strong id="distanceKmLabel">{{ number_format($distanceKm ?? 0, 1, ',', '.') }}</strong> km
                                    @if(isset($distanceGeocoded) && ! $distanceGeocoded)
                                        <span class="text-warning">(chưa xác định chính xác địa chỉ — dùng mức phí an toàn)</span>
                                    @endif
                                </div>
                                @error('shipping_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold">Số điện thoại *</label>
                                <input type="text" name="phone" id="phone" value="{{ $phone ?? '' }}" class="form-control hh-input @error('phone') is-invalid @enderror" required placeholder="VD: 0859 773 086">
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-0">
                                <label for="note" class="form-label fw-semibold">Ghi chú đơn hàng</label>
                                <textarea name="note" id="note" class="form-control hh-input" rows="3" placeholder="Lời nhắn, thời gian giao mong muốn...">{{ $note ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="hh-co-card mb-4">
                        <div class="hh-co-card-head">
                            <h6 class="mb-0 fw-bold"><i class="fas fa-credit-card me-2 text-success"></i>Phương thức thanh toán</h6>
                        </div>
                        <div class="p-4">
                            <label class="hh-pay-option {{ ($paymentMethod ?? 'cod') === 'cod' ? 'is-active' : '' }}">
                                <input type="radio" name="payment_method" value="cod" {{ ($paymentMethod ?? 'cod') === 'cod' ? 'checked' : '' }}>
                                <div class="hh-pay-icon"><i class="fas fa-money-bill-wave"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Thanh toán khi nhận hàng (COD)</div>
                                    <div class="small text-muted">Trả tiền trực tiếp cho shipper khi nhận hoa.</div>
                                </div>
                                <i class="fas fa-circle-check hh-pay-tick"></i>
                            </label>
                            <label class="hh-pay-option {{ ($paymentMethod ?? '') === 'card' ? 'is-active' : '' }}">
                                <input type="radio" name="payment_method" value="card" {{ ($paymentMethod ?? '') === 'card' ? 'checked' : '' }}>
                                <div class="hh-pay-icon"><i class="fas fa-qrcode"></i></div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">Chuyển khoản / VietQR · MoMo</div>
                                    <div class="small text-muted">Hệ thống hiển thị mã QR & thông tin thanh toán ngay bước sau.</div>
                                </div>
                                <i class="fas fa-circle-check hh-pay-tick"></i>
                            </label>
                            @error('payment_method')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="hh-summary-sticky">
                        <div class="hh-co-card mb-3">
                            <div class="hh-co-card-head d-flex align-items-center justify-content-between">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-ticket me-2 text-success"></i>Mã giảm giá</h6>
                                <button type="button" class="btn btn-sm btn-success rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#voucherPickerModal" id="openVoucherPickerBtn">
                                    <i class="fas fa-gift me-1"></i>Chọn mã có sẵn
                                </button>
                            </div>
                            <div class="p-4">
                                <div class="input-group hh-voucher-input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-tag text-success"></i></span>
                                    <input type="text" id="voucherInput" class="form-control border-start-0 hh-input" placeholder="Nhập mã giảm giá..." value="{{ $voucherCode ?? '' }}">
                                    <button class="btn btn-success" type="button" id="applyVoucherBtn">Áp dụng</button>
                                </div>
                                <div id="voucherFeedback" class="small mt-2"></div>
                                <div id="voucherActive" class="hh-voucher-active d-none mt-3">
                                    <div class="d-flex align-items-center justify-content-between gap-2">
                                        <div>
                                            <span class="hh-variant-pill"><i class="fas fa-check me-1"></i><span id="voucherActiveCode"></span></span>
                                            <div class="small text-muted mt-1" id="voucherActiveName"></div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill" id="removeVoucherBtn">
                                            <i class="fas fa-xmark me-1"></i>Bỏ mã
                                        </button>
                                    </div>
                                </div>
                                @error('voucher_code')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="hh-co-card mb-3">
                            <div class="hh-co-card-head">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-success"></i>Tóm tắt đơn hàng</h6>
                            </div>
                            <div class="p-4">
                                @php $groupedItems = $cartItems->groupBy('product_id'); @endphp
                                <div class="hh-summary-items">
                                    @foreach($groupedItems as $items)
                                        @php $firstItem = $items->first(); @endphp
                                        <div class="hh-summary-product">
                                            <div class="hh-summary-thumb">
                                                @if($firstItem->product->image)
                                                    <img src="{{ $firstItem->product->image_url }}" alt="">
                                                @else
                                                    <i class="fas fa-image text-muted"></i>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="fw-semibold text-truncate">{{ $firstItem->product->name }}</div>
                                                @foreach($items as $item)
                                                    <div class="d-flex justify-content-between align-items-center small mt-1">
                                                        <span class="text-muted">{{ $item->variant ?: 'Mặc định' }} × {{ $item->quantity }}</span>
                                                        <span class="fw-semibold">{{ number_format($item->quantity * $item->price, 0, ',', '.') }} ₫</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="hh-summary-divider"></div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tạm tính</span>
                                    <span class="fw-semibold">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí vận chuyển</span>
                                    <span class="fw-semibold text-success" id="shippingPreview">{{ $shipping === 0 ? 'Miễn phí' : number_format($shipping, 0, ',', '.') . ' ₫' }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Giảm giá</span>
                                    <span class="fw-semibold text-danger" id="discountPreview">-{{ number_format($discount ?? 0, 0, ',', '.') }} ₫</span>
                                </div>
                                <div class="hh-summary-divider"></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Tổng cộng</span>
                                    <span class="hh-total" id="totalPreview">{{ number_format($total, 0, ',', '.') }} ₫</span>
                                </div>

                                <button type="submit" class="btn btn-success btn-lg w-100 mt-4 rounded-pill hh-checkout-btn">
                                    <i class="fas fa-lock me-2"></i>Tiếp tục thanh toán
                                </button>
                                <p class="small text-muted mt-3 mb-0">
                                    <i class="fas fa-shield-halved me-1 text-success"></i>
                                    Đơn hàng được xác nhận ngay sau khi hoàn tất thanh toán.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal chọn voucher -->
<div class="modal fade" id="voucherPickerModal" tabindex="-1" aria-labelledby="voucherPickerLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-0 hh-modal-head">
                <div>
                    <h5 class="modal-title fw-bold mb-1" id="voucherPickerLabel"><i class="fas fa-gift me-2"></i>Chọn mã giảm giá</h5>
                    <p class="small text-muted mb-0">Mã đủ điều kiện sẽ áp dụng ngay khi bạn bấm "Dùng mã".</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body p-3 p-md-4">
                <div class="input-group mb-3">
                    <span class="input-group-text bg-white"><i class="fas fa-magnifying-glass text-muted"></i></span>
                    <input type="text" id="voucherSearchInput" class="form-control hh-input" placeholder="Tìm theo mã hoặc tên...">
                </div>
                <div id="voucherListContainer">
                    <div class="text-center text-muted py-5">
                        <div class="spinner-border text-success mb-2" role="status"></div>
                        <div>Đang tải danh sách mã...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hh-checkout-page {
        background:
            radial-gradient(circle at 0% 0%, rgba(25,135,84,0.07), transparent 40%),
            radial-gradient(circle at 100% 0%, rgba(233,30,140,0.06), transparent 35%),
            linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        min-height: 80vh;
    }
    .hh-co-card {
        background: #fff; border-radius: var(--hh-radius);
        box-shadow: var(--hh-shadow); border: 1px solid rgba(15,23,42,0.04);
        overflow: hidden;
    }
    .hh-co-card-head {
        padding: 16px 22px;
        border-bottom: 1px dashed rgba(15,23,42,0.08);
        background: linear-gradient(135deg, rgba(25,135,84,0.05), rgba(233,30,140,0.04));
    }
    .hh-input { border-radius: 12px; padding: .65rem .9rem; }
    .hh-input:focus { border-color: var(--hh-primary); box-shadow: 0 0 0 .2rem rgba(25,135,84,0.18); }

    .hh-pay-option {
        display: flex; align-items: center; gap: 14px;
        padding: 14px 16px; margin-bottom: 10px;
        border: 1.5px solid rgba(15,23,42,0.08);
        border-radius: 14px; background: #fff;
        cursor: pointer; transition: all .2s ease;
    }
    .hh-pay-option:hover { border-color: rgba(25,135,84,0.4); }
    .hh-pay-option input { display: none; }
    .hh-pay-option.is-active {
        border-color: var(--hh-primary);
        background: linear-gradient(135deg, rgba(25,135,84,0.06), rgba(233,30,140,0.04));
    }
    .hh-pay-icon {
        width: 44px; height: 44px; border-radius: 12px;
        background: rgba(25,135,84,0.1); color: var(--hh-primary);
        display: grid; place-items: center; font-size: 1.2rem;
    }
    .hh-pay-tick { color: var(--hh-primary); opacity: 0; transition: opacity .2s ease; }
    .hh-pay-option.is-active .hh-pay-tick { opacity: 1; }

    .hh-summary-sticky { position: sticky; top: 96px; }
    .hh-summary-items { display: grid; gap: 12px; max-height: 260px; overflow-y: auto; padding-right: 4px; }
    .hh-summary-product {
        display: flex; gap: 10px; align-items: flex-start;
        padding: 10px; background: #f8fafc; border-radius: 12px;
    }
    .hh-summary-thumb {
        width: 50px; height: 50px; border-radius: 10px; overflow: hidden;
        background: #e2e8f0; flex-shrink: 0; display: grid; place-items: center;
    }
    .hh-summary-thumb img { width: 100%; height: 100%; object-fit: cover; }
    .min-w-0 { min-width: 0; }
    .hh-summary-divider {
        margin: 14px 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(15,23,42,0.12), transparent);
    }
    .hh-total {
        font-size: 1.5rem; font-weight: 800;
        background: linear-gradient(135deg, var(--hh-primary), var(--hh-accent));
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .hh-checkout-btn { box-shadow: 0 12px 24px rgba(25,135,84,0.25); }
    .hh-checkout-btn:hover { box-shadow: 0 16px 28px rgba(25,135,84,0.32); transform: translateY(-1px); }

    .hh-variant-pill {
        display: inline-block; font-size: .8rem; font-weight: 700;
        padding: 4px 12px; border-radius: 999px;
        background: rgba(25,135,84,0.1); color: var(--hh-primary);
    }
    .hh-voucher-active {
        padding: 12px 14px; border-radius: 12px;
        background: linear-gradient(135deg, rgba(25,135,84,0.08), rgba(233,30,140,0.06));
        border: 1px dashed rgba(25,135,84,0.4);
    }

    .hh-alert { padding: 14px 18px; border-radius: 14px; }
    .hh-alert-info {
        background: linear-gradient(135deg, #e0f2fe, #f0f9ff);
        border: 1px solid rgba(59,130,246,0.3); color: #0c4a6e;
    }

    .hh-profile-suggest {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 14px 16px; border-radius: 14px;
        background: linear-gradient(135deg, rgba(25,135,84,0.08), rgba(233,30,140,0.05));
        border: 1px dashed rgba(25,135,84,0.4);
        animation: hhSuggestIn .3s ease;
    }
    .hh-profile-suggest.is-applied {
        background: linear-gradient(135deg, rgba(25,135,84,0.05), rgba(233,30,140,0.03));
        border-style: solid; border-color: rgba(25,135,84,0.3);
    }
    .hh-profile-suggest-icon {
        width: 42px; height: 42px; border-radius: 12px;
        background: linear-gradient(135deg, var(--hh-primary), var(--hh-accent));
        color: #fff; display: grid; place-items: center;
        font-size: 1.1rem; flex-shrink: 0;
    }
    .hh-suggest-badge {
        display: inline-flex; align-items: center;
        font-size: .7rem; font-weight: 700;
        padding: 2px 10px; border-radius: 999px;
        background: rgba(25,135,84,0.15); color: var(--hh-primary);
    }
    @keyframes hhSuggestIn {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 575.98px) {
        .hh-profile-suggest { flex-wrap: wrap; }
        .hh-profile-suggest-icon { width: 36px; height: 36px; }
    }

    .hh-modal-head {
        padding: 22px 22px 12px;
        background: linear-gradient(135deg, rgba(25,135,84,0.06), rgba(233,30,140,0.04));
    }
    .hh-voucher-card {
        position: relative;
        display: flex; align-items: stretch; gap: 0;
        background: #fff; border-radius: 14px; overflow: hidden;
        border: 1.5px dashed rgba(25,135,84,0.3);
        transition: all .2s ease;
    }
    .hh-voucher-card.is-disabled { opacity: .65; border-color: rgba(15,23,42,0.12); }
    .hh-voucher-card.is-active { border-color: var(--hh-primary); box-shadow: 0 8px 18px rgba(25,135,84,0.15); }
    .hh-voucher-card:hover:not(.is-disabled) { border-color: var(--hh-primary); transform: translateY(-2px); }
    .hh-voucher-left {
        width: 110px; flex-shrink: 0;
        background: linear-gradient(135deg, #198754, #0d9488);
        color: #fff; display: grid; place-items: center;
        text-align: center; padding: 10px;
        position: relative;
    }
    .hh-voucher-card.is-disabled .hh-voucher-left { background: linear-gradient(135deg, #94a3b8, #64748b); }
    .hh-voucher-left::before, .hh-voucher-left::after {
        content: ''; position: absolute; right: -8px;
        width: 16px; height: 16px; background: #fff; border-radius: 50%;
    }
    .hh-voucher-left::before { top: -8px; }
    .hh-voucher-left::after { bottom: -8px; }
    .hh-voucher-amount { font-size: 1.4rem; font-weight: 800; line-height: 1.1; }
    .hh-voucher-amount-sub { font-size: .7rem; opacity: .85; margin-top: 2px; }
    .hh-voucher-body { flex-grow: 1; padding: 14px 16px 14px 22px; min-width: 0; }
    .hh-voucher-code {
        display: inline-block; font-family: 'Courier New', monospace;
        font-weight: 800; color: var(--hh-primary);
        background: rgba(25,135,84,0.08); padding: 2px 10px;
        border-radius: 6px; font-size: .85rem;
    }
    .hh-voucher-name { font-weight: 700; margin-top: 6px; margin-bottom: 4px; }
    .hh-voucher-meta { display: flex; flex-wrap: wrap; gap: 6px 12px; font-size: .75rem; color: #64748b; margin-bottom: 8px; }
    .hh-voucher-status {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px; border-radius: 999px;
        font-size: .72rem; font-weight: 700;
    }
    .hh-voucher-status.eligible { background: rgba(25,135,84,0.12); color: var(--hh-primary); }
    .hh-voucher-status.ineligible { background: rgba(220,53,69,0.1); color: #dc3545; }
    .hh-voucher-actions { display: flex; align-items: center; gap: 8px; }
    .hh-voucher-progress { height: 4px; background: rgba(15,23,42,0.08); border-radius: 999px; overflow: hidden; margin-top: 8px; }
    .hh-voucher-progress > div { height: 100%; background: linear-gradient(90deg, var(--hh-primary), var(--hh-accent)); }

    @media (max-width: 575.98px) {
        .hh-voucher-left { width: 90px; }
        .hh-voucher-amount { font-size: 1.15rem; }
        .hh-summary-sticky { position: static; }
    }
</style>

<script>
(function () {
    const root = document.getElementById('checkoutPage');
    if (!root) return;

    const csrf = root.dataset.csrf;
    const subtotal = parseFloat(root.dataset.subtotal || '0');
    let currentDiscount = parseFloat(root.dataset.discount || '0');
    let currentShipping = parseFloat(root.dataset.shipping || '0');
    const checkoutSource = root.dataset.checkoutSource;

    const addr = document.getElementById('shippingAddress');
    const shippingPreview = document.getElementById('shippingPreview');
    const discountPreview = document.getElementById('discountPreview');
    const totalPreview = document.getElementById('totalPreview');
    const distanceInfo = document.getElementById('distanceInfo');
    const distanceKmLabel = document.getElementById('distanceKmLabel');

    const voucherInput = document.getElementById('voucherInput');
    const voucherCodeHidden = document.getElementById('voucherCode');
    const applyVoucherBtn = document.getElementById('applyVoucherBtn');
    const voucherFeedback = document.getElementById('voucherFeedback');
    const voucherActive = document.getElementById('voucherActive');
    const voucherActiveCode = document.getElementById('voucherActiveCode');
    const voucherActiveName = document.getElementById('voucherActiveName');
    const removeVoucherBtn = document.getElementById('removeVoucherBtn');

    const voucherListContainer = document.getElementById('voucherListContainer');
    const voucherSearchInput = document.getElementById('voucherSearchInput');
    const openPickerBtn = document.getElementById('openVoucherPickerBtn');
    const pickerModalEl = document.getElementById('voucherPickerModal');

    let allVouchers = [];
    let activeVoucherCode = (voucherInput?.value || '').trim();

    const fmt = (n) => new Intl.NumberFormat('vi-VN').format(Math.max(0, Math.round(n))) + ' ₫';

    function renderSummary() {
        shippingPreview.textContent = currentShipping === 0 ? 'Miễn phí' : fmt(currentShipping);
        discountPreview.textContent = '-' + fmt(currentDiscount);
        totalPreview.textContent = fmt(Math.max(0, subtotal + currentShipping - currentDiscount));
    }

    function setFeedback(msg, type) {
        voucherFeedback.textContent = msg || '';
        voucherFeedback.className = 'small mt-2';
        if (type === 'success') voucherFeedback.classList.add('text-success');
        else if (type === 'error') voucherFeedback.classList.add('text-danger');
        else voucherFeedback.classList.add('text-muted');
    }

    function showActiveVoucher(code, name) {
        voucherActive.classList.remove('d-none');
        voucherActiveCode.textContent = code;
        voucherActiveName.textContent = name || '';
        voucherCodeHidden.value = code;
        activeVoucherCode = code;
    }

    function hideActiveVoucher() {
        voucherActive.classList.add('d-none');
        voucherCodeHidden.value = '';
        voucherInput.value = '';
        activeVoucherCode = '';
        currentDiscount = 0;
        renderSummary();
        setFeedback('', 'muted');
    }

    function applyVoucher(code) {
        const voucherCode = (code !== undefined ? code : (voucherInput.value || '')).trim();
        const shippingAddress = (addr.value || '').trim();
        if (! voucherCode) { setFeedback('Vui lòng nhập mã.', 'error'); return Promise.resolve(); }

        applyVoucherBtn.setAttribute('disabled', 'disabled');
        setFeedback('Đang kiểm tra mã...', 'muted');

        return fetch(root.dataset.voucherUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ voucher_code: voucherCode, shipping_address: shippingAddress, checkout_source: checkoutSource })
        })
        .then(r => r.json().then(d => ({ ok: r.ok, data: d })))
        .then(res => {
            if (! res.ok || ! res.data.success) throw new Error(res.data.message || 'Mã giảm giá không hợp lệ.');
            currentShipping = parseFloat(res.data.shipping || 0);
            currentDiscount = parseFloat(res.data.discount || 0);
            renderSummary();
            const found = allVouchers.find(v => v.code === voucherCode);
            voucherInput.value = voucherCode;
            showActiveVoucher(voucherCode, found?.name || '');
            setFeedback(res.data.message || 'Áp dụng mã thành công.', 'success');
        })
        .catch(err => {
            currentDiscount = 0;
            renderSummary();
            voucherCodeHidden.value = '';
            voucherActive.classList.add('d-none');
            setFeedback(err.message || 'Không áp dụng được mã.', 'error');
        })
        .finally(() => applyVoucherBtn.removeAttribute('disabled'));
    }

    function estimate() {
        const text = (addr.value || '').trim();
        if (text.length < 8) return;
        fetch(root.dataset.estimateUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ shipping_address: text })
        })
        .then(r => r.json())
        .then(data => {
            if (! data.success) return;
            distanceInfo?.classList.remove('d-none');
            if (distanceKmLabel) distanceKmLabel.textContent = new Intl.NumberFormat('vi-VN', { maximumFractionDigits: 1 }).format(data.distance_km);
            currentShipping = parseInt(data.shipping, 10) || 0;
            renderSummary();
            // Re-apply voucher to recompute under new shipping
            if (activeVoucherCode) applyVoucher(activeVoucherCode);
        })
        .catch(() => {});
    }

    function renderVoucherList(list) {
        if (! list.length) {
            voucherListContainer.innerHTML = '<div class="text-center text-muted py-5"><i class="fas fa-ticket fa-2x mb-2 opacity-50"></i><div>Chưa có mã giảm giá nào khả dụng.</div></div>';
            return;
        }
        const html = list.map(v => {
            const left = v.type === 'percent'
                ? `<div><div class="hh-voucher-amount">${parseFloat(v.value)}%</div><div class="hh-voucher-amount-sub">GIẢM</div></div>`
                : `<div><div class="hh-voucher-amount">${new Intl.NumberFormat('vi-VN').format(v.value)}</div><div class="hh-voucher-amount-sub">VND</div></div>`;

            const meta = [];
            if (v.min_order_amount) meta.push(`<span><i class="fas fa-cart-shopping me-1"></i>Đơn từ ${new Intl.NumberFormat('vi-VN').format(v.min_order_amount)}₫</span>`);
            if (v.max_discount_amount) meta.push(`<span><i class="fas fa-arrow-down me-1"></i>Giảm tối đa ${new Intl.NumberFormat('vi-VN').format(v.max_discount_amount)}₫</span>`);
            if (v.ends_at) meta.push(`<span><i class="far fa-calendar me-1"></i>HSD ${v.ends_at}</span>`);
            if (v.usage_limit) meta.push(`<span><i class="fas fa-users me-1"></i>${v.used_count}/${v.usage_limit}</span>`);

            const progressPct = v.usage_limit ? Math.min(100, (v.used_count / v.usage_limit) * 100) : 0;
            const isActive = v.code === activeVoucherCode;
            const cardCls = ['hh-voucher-card'];
            if (! v.eligible) cardCls.push('is-disabled');
            if (isActive) cardCls.push('is-active');

            const status = v.eligible
                ? `<span class="hh-voucher-status eligible"><i class="fas fa-check-circle"></i>Đủ điều kiện · giảm ~${new Intl.NumberFormat('vi-VN').format(v.estimated_discount)}₫</span>`
                : `<span class="hh-voucher-status ineligible"><i class="fas fa-circle-info"></i>${v.reason || 'Chưa đủ điều kiện'}</span>`;

            const action = isActive
                ? `<button type="button" class="btn btn-outline-danger btn-sm rounded-pill" data-action="remove"><i class="fas fa-xmark me-1"></i>Đang dùng — Bỏ</button>`
                : `<button type="button" class="btn btn-success btn-sm rounded-pill" ${v.eligible ? '' : 'disabled'} data-action="use" data-code="${v.code}"><i class="fas fa-bolt me-1"></i>Dùng mã</button>`;

            return `
            <div class="${cardCls.join(' ')} mb-3" data-voucher-code="${v.code}">
                <div class="hh-voucher-left">${left}</div>
                <div class="hh-voucher-body">
                    <div class="d-flex flex-wrap align-items-center gap-2 justify-content-between">
                        <span class="hh-voucher-code">${v.code}</span>
                        ${status}
                    </div>
                    <div class="hh-voucher-name">${v.name}</div>
                    <div class="hh-voucher-meta">${meta.join('')}</div>
                    ${v.usage_limit ? `<div class="hh-voucher-progress"><div style="width:${progressPct}%"></div></div>` : ''}
                    <div class="hh-voucher-actions mt-2">${action}</div>
                </div>
            </div>`;
        }).join('');
        voucherListContainer.innerHTML = html;

        voucherListContainer.querySelectorAll('[data-action="use"]').forEach(btn => {
            btn.addEventListener('click', function () {
                const code = this.dataset.code;
                applyVoucher(code).then(() => {
                    bootstrap.Modal.getInstance(pickerModalEl)?.hide();
                });
            });
        });
        voucherListContainer.querySelectorAll('[data-action="remove"]').forEach(btn => {
            btn.addEventListener('click', function () {
                hideActiveVoucher();
                bootstrap.Modal.getInstance(pickerModalEl)?.hide();
            });
        });
    }

    function loadVouchers() {
        const url = new URL(root.dataset.vouchersListUrl, window.location.origin);
        if (checkoutSource) url.searchParams.set('checkout_source', checkoutSource);
        return fetch(url, { headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(data => {
                allVouchers = data.vouchers || [];
                renderVoucherList(allVouchers);
                return allVouchers;
            })
            .catch(() => {
                voucherListContainer.innerHTML = '<div class="text-danger text-center py-4">Không tải được danh sách mã. Vui lòng thử lại.</div>';
                return [];
            });
    }

    let debounceTimer = null;
    addr?.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(estimate, 550);
    });

    applyVoucherBtn?.addEventListener('click', () => applyVoucher());
    voucherInput?.addEventListener('keydown', function (ev) {
        if (ev.key === 'Enter') { ev.preventDefault(); applyVoucher(); }
    });
    removeVoucherBtn?.addEventListener('click', hideActiveVoucher);

    voucherSearchInput?.addEventListener('input', function () {
        const q = this.value.trim().toLowerCase();
        const filtered = ! q ? allVouchers : allVouchers.filter(v =>
            v.code.toLowerCase().includes(q) || v.name.toLowerCase().includes(q)
        );
        renderVoucherList(filtered);
    });

    pickerModalEl?.addEventListener('show.bs.modal', () => {
        if (! allVouchers.length) loadVouchers();
        else renderVoucherList(allVouchers);
    });

    // Payment method active state
    document.querySelectorAll('.hh-pay-option input[type="radio"]').forEach(input => {
        input.addEventListener('change', function () {
            document.querySelectorAll('.hh-pay-option').forEach(opt => opt.classList.remove('is-active'));
            if (this.checked) this.closest('.hh-pay-option').classList.add('is-active');
        });
    });

    // Profile suggest box
    const suggestBox = document.getElementById('profileSuggestBox');
    if (suggestBox) {
        const useBtn = document.getElementById('useProfileBtn');
        const dismissBtn = document.getElementById('dismissSuggestBtn');
        const phoneInput = document.getElementById('phone');

        useBtn?.addEventListener('click', function () {
            const pAddr = suggestBox.dataset.profileAddress || '';
            const pPhone = suggestBox.dataset.profilePhone || '';
            if (pAddr) {
                addr.value = pAddr;
                addr.dispatchEvent(new Event('input', { bubbles: true }));
            }
            if (pPhone && phoneInput) phoneInput.value = pPhone;
            suggestBox.classList.add('is-applied');
            useBtn.innerHTML = '<i class="fas fa-check me-1"></i>Đã áp dụng';
            useBtn.disabled = true;
            // Trigger ngay estimate fee ship
            estimate();
        });

        dismissBtn?.addEventListener('click', function () {
            suggestBox.style.transition = 'opacity .25s, transform .25s';
            suggestBox.style.opacity = '0';
            suggestBox.style.transform = 'translateY(-4px)';
            setTimeout(() => suggestBox.remove(), 240);
            addr.focus();
        });

        // Tự động áp nếu cả 2 trường địa chỉ + phone đang trống
        if (! addr.value.trim() && ! (phoneInput?.value || '').trim()) {
            useBtn?.click();
        }
    }

    // Initial render
    renderSummary();
    if ((addr?.value || '').trim().length >= 8) estimate();

    // Auto-apply voucher từ ?voucher=CODE hoặc giá trị server render sẵn
    const prefill = (root.dataset.prefillVoucher || '').trim();
    if (prefill) {
        voucherInput.value = prefill;
        loadVouchers().then(() => applyVoucher(prefill));
    }
})();
</script>
@endsection
