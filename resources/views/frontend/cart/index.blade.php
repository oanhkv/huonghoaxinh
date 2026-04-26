@extends('frontend.layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="hh-cart-page py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb small mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
            </ol>
        </nav>

        <div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
            <div>
                <p class="text-success fw-semibold mb-1 small">🌷 Giỏ hàng của bạn</p>
                <h1 class="display-6 fw-bold mb-0">Hoàn thiện đơn hoa xinh</h1>
                <p class="text-muted mb-0 mt-1">Mã giảm giá sẽ áp dụng ở bước thanh toán — bạn có thể chọn từ danh sách hoặc nhập mã ngay tại đó.</p>
            </div>
            @if(! $cartItems->isEmpty())
                <a href="{{ route('shop') }}" class="btn btn-outline-success rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                </a>
            @endif
        </div>

        @if(! empty($hasStockIssue) && $hasStockIssue && ! empty($stockIssue))
            <div class="hh-alert hh-alert-warning d-flex align-items-start gap-3 mb-4">
                <i class="fas fa-triangle-exclamation fa-lg mt-1"></i>
                <div>
                    <strong>Cần cập nhật giỏ hàng:</strong> {{ $stockIssue }}
                    <div class="small mt-1 opacity-75">Giảm số lượng hoặc xoá sản phẩm tương ứng để tiếp tục thanh toán.</div>
                </div>
            </div>
        @endif

        @if($cartItems->isEmpty())
            <div class="hh-empty-cart text-center py-5 px-4">
                <div class="hh-empty-icon mx-auto mb-4">
                    <i class="fas fa-shopping-basket"></i>
                </div>
                <h3 class="fw-bold mb-2">Giỏ hàng đang trống</h3>
                <p class="text-muted mb-4">Khám phá những bó hoa xinh đang có ưu đãi hôm nay nhé!</p>
                <div class="d-flex flex-wrap justify-content-center gap-2">
                    <a href="{{ route('shop') }}" class="btn btn-success btn-lg rounded-pill px-4">
                        <i class="fas fa-store me-2"></i>Khám phá cửa hàng
                    </a>
                    <a href="{{ route('vouchers') }}" class="btn btn-outline-success btn-lg rounded-pill px-4">
                        <i class="fas fa-gift me-2"></i>Xem mã giảm giá
                    </a>
                </div>
            </div>
        @else
            @php $groupedItems = $cartItems->groupBy('product_id'); @endphp
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="hh-cart-card">
                        <div class="hh-cart-card-head d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="mb-0 fw-bold"><i class="fas fa-bag-shopping me-2 text-success"></i>Sản phẩm trong giỏ</h6>
                                <small class="text-muted">{{ $groupedItems->count() }} loại sản phẩm · tổng {{ $cartItems->sum('quantity') }} cành/bó</small>
                            </div>
                            <button class="btn btn-sm btn-link text-danger text-decoration-none" id="clearCartBtn" type="button">
                                <i class="fas fa-trash-can me-1"></i>Xoá tất cả
                            </button>
                        </div>

                        <div class="hh-cart-list">
                            @foreach($groupedItems as $items)
                                @php
                                    $firstItem = $items->first();
                                    $groupSubtotal = $items->sum(fn ($i) => $i->quantity * $i->price);
                                @endphp
                                <div class="hh-cart-group">
                                    <div class="d-flex gap-3 align-items-start mb-3">
                                        <a href="{{ route('product.show', $firstItem->product->slug) }}" class="hh-cart-thumb">
                                            @if($firstItem->product->image)
                                                <img src="{{ $firstItem->product->image_url }}" alt="{{ $firstItem->product->name }}">
                                            @else
                                                <div class="hh-cart-thumb-placeholder"><i class="fas fa-image"></i></div>
                                            @endif
                                        </a>
                                        <div class="flex-grow-1">
                                            <a href="{{ route('product.show', $firstItem->product->slug) }}" class="text-decoration-none text-dark">
                                                <h6 class="fw-bold mb-1">{{ $firstItem->product->name }}</h6>
                                            </a>
                                            <div class="d-flex flex-wrap align-items-center gap-2 small text-muted">
                                                <span><i class="fas fa-tag me-1"></i>SKU #{{ $firstItem->product->id }}</span>
                                                <span class="hh-dot"></span>
                                                <span>Còn {{ $firstItem->product->stock }} trong kho</span>
                                            </div>
                                            <div class="hh-cart-grouptotal small mt-1">
                                                Tổng nhóm: <span class="fw-semibold text-success">{{ number_format($groupSubtotal, 0, ',', '.') }} ₫</span>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                        $productSizes = is_array($firstItem->product->sizes) ? $firstItem->product->sizes : [];
                                        $hasSizes = ! empty($productSizes);
                                    @endphp
                                    @foreach($items as $item)
                                        <div class="hh-cart-item cart-item"
                                             data-cart-id="{{ $item->id }}"
                                             data-base-price="{{ $firstItem->product->price }}">
                                            <div class="hh-cart-item-info">
                                                @if($hasSizes)
                                                    <label class="hh-size-label">Kích thước</label>
                                                    <div class="hh-size-chips" data-cart-id="{{ $item->id }}">
                                                        <button type="button"
                                                                class="hh-size-chip variant-chip {{ ($item->variant ?: 'Mặc định') === 'Mặc định' ? 'is-active' : '' }}"
                                                                data-variant="Mặc định"
                                                                data-price="{{ $firstItem->product->price }}">
                                                            <span class="hh-size-name">Mặc định</span>
                                                            <span class="hh-size-price">{{ number_format($firstItem->product->price, 0, ',', '.') }} ₫</span>
                                                        </button>
                                                        @foreach($productSizes as $sz)
                                                            @php
                                                                $szName = (string) ($sz['size'] ?? '');
                                                                $szDelta = (float) ($sz['price'] ?? 0);
                                                                $szPrice = (float) $firstItem->product->price + $szDelta;
                                                            @endphp
                                                            @if($szName !== '')
                                                                <button type="button"
                                                                        class="hh-size-chip variant-chip {{ $item->variant === $szName ? 'is-active' : '' }}"
                                                                        data-variant="{{ $szName }}"
                                                                        data-price="{{ $szPrice }}">
                                                                    <span class="hh-size-name">{{ $szName }}</span>
                                                                    <span class="hh-size-price">{{ number_format($szPrice, 0, ',', '.') }} ₫</span>
                                                                </button>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="hh-variant-pill">{{ $item->variant ?: 'Mặc định' }}</span>
                                                    <div class="hh-unit-price">{{ number_format($item->price, 0, ',', '.') }} ₫ / sản phẩm</div>
                                                @endif
                                            </div>

                                            <div class="hh-cart-item-controls">
                                                <div class="hh-stepper" role="group" aria-label="Số lượng">
                                                    <button type="button" class="hh-stepper-btn decrease-qty" aria-label="Giảm">
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    <input type="number" class="hh-stepper-input qty-input"
                                                           value="{{ $item->quantity }}"
                                                           min="1"
                                                           max="{{ $item->product->stock }}"
                                                           data-price="{{ $item->price }}"
                                                           inputmode="numeric">
                                                    <button type="button" class="hh-stepper-btn increase-qty" aria-label="Tăng">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>

                                                <div class="hh-cart-item-total">
                                                    <span class="item-subtotal">{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span> ₫
                                                </div>

                                                <button class="hh-cart-remove remove-item" type="button" data-cart-id="{{ $item->id }}" title="Xoá">
                                                    <i class="fas fa-trash-can"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('shop') }}" class="btn btn-outline-success rounded-pill px-4">
                            <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                        </a>
                        <a href="{{ route('vouchers') }}" class="btn btn-outline-secondary rounded-pill px-4">
                            <i class="fas fa-ticket me-2"></i>Xem mã giảm giá
                        </a>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="hh-summary-sticky">
                        <div class="hh-cart-card hh-summary-card">
                            <div class="hh-cart-card-head">
                                <h6 class="mb-0 fw-bold"><i class="fas fa-receipt me-2 text-success"></i>Tóm tắt đơn hàng</h6>
                            </div>
                            <div class="p-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Tạm tính</span>
                                    <span class="fw-semibold" id="subtotalDisplay">{{ number_format($subtotal, 0, ',', '.') }} ₫</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Phí vận chuyển</span>
                                    <span class="fw-semibold text-success" id="shippingDisplay">
                                        @if($shipping === 0) Tính ở bước thanh toán @else {{ number_format($shipping, 0, ',', '.') }} ₫ @endif
                                    </span>
                                </div>
                                <div class="hh-promo-hint">
                                    <i class="fas fa-gift text-success"></i>
                                    <span>Mã giảm giá sẽ chọn / nhập tại trang <strong>thanh toán</strong>.</span>
                                </div>
                                <div class="hh-summary-divider"></div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Tạm tính giỏ</span>
                                    <span class="hh-total" id="totalDisplay">{{ number_format($total, 0, ',', '.') }} ₫</span>
                                </div>

                                @if(! empty($hasStockIssue) && $hasStockIssue)
                                    <button class="btn btn-secondary btn-lg w-100 mt-4 rounded-pill" type="button" disabled>
                                        <i class="fas fa-ban me-2"></i>Vượt tồn kho — không thể tiếp tục
                                    </button>
                                @else
                                    <a href="{{ route('checkout.index') }}" class="btn btn-success btn-lg w-100 mt-4 rounded-pill hh-checkout-btn">
                                        <i class="fas fa-lock me-2"></i>Tiến hành thanh toán
                                    </a>
                                @endif

                                @guest
                                    <p class="small text-muted mt-3 mb-0">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Bạn có thể thêm vào giỏ mà không cần đăng nhập. Hệ thống sẽ yêu cầu đăng nhập khi thanh toán.
                                    </p>
                                @endguest
                            </div>
                        </div>

                        <div class="hh-perks mt-3">
                            <div class="hh-perk-item">
                                <i class="fas fa-truck-fast text-success"></i>
                                <div>
                                    <div class="fw-semibold small">Miễn phí ship trong 10 km</div>
                                    <div class="text-muted small">Nội thành Hà Nội từ cửa hàng</div>
                                </div>
                            </div>
                            <div class="hh-perk-item">
                                <i class="fas fa-shield-heart text-success"></i>
                                <div>
                                    <div class="fw-semibold small">Đổi trả 7 ngày</div>
                                    <div class="text-muted small">Hoa lỗi / không tươi như cam kết</div>
                                </div>
                            </div>
                            <div class="hh-perk-item">
                                <i class="fas fa-headset text-success"></i>
                                <div>
                                    <div class="fw-semibold small">Hỗ trợ 24/7</div>
                                    <div class="text-muted small">{{ config('shop.hotline', '0859 773 086') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    .hh-cart-page {
        background:
            radial-gradient(circle at 0% 0%, rgba(25,135,84,0.08), transparent 40%),
            radial-gradient(circle at 100% 0%, rgba(233,30,140,0.06), transparent 35%),
            linear-gradient(180deg, #f8fafc 0%, #ffffff 100%);
        min-height: 80vh;
    }
    .hh-cart-card {
        background: #fff;
        border-radius: var(--hh-radius);
        box-shadow: var(--hh-shadow);
        border: 1px solid rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .hh-cart-card-head {
        padding: 18px 22px;
        border-bottom: 1px dashed rgba(15, 23, 42, 0.08);
        background: linear-gradient(135deg, rgba(25,135,84,0.05), rgba(233,30,140,0.04));
    }
    .hh-cart-list { padding: 8px 22px 22px; }
    .hh-cart-group {
        padding: 18px 0;
        border-bottom: 1px dashed rgba(15, 23, 42, 0.08);
    }
    .hh-cart-group:last-child { border-bottom: 0; }

    .hh-cart-thumb {
        width: 96px; height: 96px; border-radius: 14px; overflow: hidden;
        background: #f1f5f9; flex-shrink: 0; display: block;
    }
    .hh-cart-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .35s ease; }
    .hh-cart-thumb:hover img { transform: scale(1.06); }
    .hh-cart-thumb-placeholder {
        width: 100%; height: 100%; display: grid; place-items: center;
        color: #94a3b8; font-size: 1.5rem;
    }
    .hh-dot {
        display: inline-block; width: 4px; height: 4px;
        background: #cbd5e1; border-radius: 50%;
    }
    .hh-cart-grouptotal { color: #475569; }

    .hh-cart-item {
        display: grid;
        grid-template-columns: 1fr auto;
        align-items: center;
        gap: 18px;
        padding: 16px;
        margin-top: 12px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f8fafc, #ffffff);
        border: 1px solid rgba(15, 23, 42, 0.06);
        transition: border-color .2s ease, transform .2s ease, box-shadow .2s ease;
    }
    .hh-cart-item:hover {
        border-color: rgba(25,135,84,0.25);
        box-shadow: 0 6px 18px rgba(25,135,84,0.08);
    }
    .hh-cart-item-info { min-width: 0; }
    .hh-cart-item-controls {
        display: flex; align-items: center; gap: 12px;
    }
    .hh-variant-pill {
        display: inline-block; font-size: .75rem; font-weight: 600;
        padding: 3px 10px; border-radius: 999px;
        background: rgba(25,135,84,0.08); color: var(--hh-primary);
    }
    .hh-unit-price { font-size: .85rem; color: #64748b; margin-top: 4px; }
    .hh-cart-item-total { font-weight: 700; color: var(--hh-primary); white-space: nowrap; min-width: 100px; text-align: right; }

    .hh-size-label {
        display: block; font-size: .72rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .5px;
        color: #94a3b8; margin-bottom: 6px;
    }
    .hh-size-chips {
        display: flex; flex-wrap: wrap; gap: 8px;
    }
    .hh-size-chip {
        display: inline-flex; flex-direction: column; align-items: flex-start;
        padding: 8px 14px; border-radius: 12px;
        background: #fff; border: 1.5px solid rgba(15,23,42,0.1);
        cursor: pointer; transition: all .18s ease;
        min-width: 110px;
    }
    .hh-size-chip:hover { border-color: rgba(25,135,84,0.5); transform: translateY(-1px); }
    .hh-size-chip.is-active {
        border-color: var(--hh-primary);
        background: linear-gradient(135deg, rgba(25,135,84,0.1), rgba(233,30,140,0.05));
        box-shadow: 0 4px 12px rgba(25,135,84,0.15);
    }
    .hh-size-chip .hh-size-name {
        font-weight: 700; font-size: .85rem; color: #0f172a;
    }
    .hh-size-chip.is-active .hh-size-name { color: var(--hh-primary); }
    .hh-size-chip .hh-size-price {
        font-size: .75rem; color: #64748b; margin-top: 2px;
    }
    .hh-size-chip.is-active .hh-size-price { color: var(--hh-primary); font-weight: 600; }
    .hh-size-chip.is-loading { opacity: .55; pointer-events: none; }

    .hh-stepper {
        display: inline-flex; align-items: center;
        background: #fff; border: 1px solid rgba(15,23,42,0.12);
        border-radius: 999px; overflow: hidden;
    }
    .hh-stepper-btn {
        width: 34px; height: 34px; display: grid; place-items: center;
        background: transparent; border: 0; color: #475569; cursor: pointer;
        transition: background .2s ease, color .2s ease;
    }
    .hh-stepper-btn:hover { background: rgba(25,135,84,0.1); color: var(--hh-primary); }
    .hh-stepper-input {
        width: 44px; text-align: center; border: 0; font-weight: 600;
        background: transparent; outline: none; appearance: textfield; -moz-appearance: textfield;
    }
    .hh-stepper-input::-webkit-outer-spin-button,
    .hh-stepper-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

    .hh-cart-remove {
        width: 36px; height: 36px; border-radius: 50%;
        background: rgba(220,53,69,0.08); color: #dc3545;
        border: 0; display: grid; place-items: center; cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }
    .hh-cart-remove:hover { background: rgba(220,53,69,0.18); transform: scale(1.05); }

    .hh-summary-sticky { position: sticky; top: 96px; }
    .hh-summary-card .hh-promo-hint {
        margin-top: 14px; padding: 12px 14px;
        background: linear-gradient(135deg, rgba(25,135,84,0.08), rgba(233,30,140,0.05));
        border-radius: 12px; display: flex; gap: 10px; align-items: flex-start;
        font-size: .85rem; color: #475569;
    }
    .hh-summary-divider {
        margin: 18px 0; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(15,23,42,0.12), transparent);
    }
    .hh-total {
        font-size: 1.5rem; font-weight: 800;
        background: linear-gradient(135deg, var(--hh-primary), var(--hh-accent));
        -webkit-background-clip: text; background-clip: text; color: transparent;
    }
    .hh-checkout-btn { box-shadow: 0 12px 24px rgba(25,135,84,0.25); }
    .hh-checkout-btn:hover { box-shadow: 0 16px 28px rgba(25,135,84,0.32); transform: translateY(-1px); }

    .hh-perks { display: grid; gap: 10px; }
    .hh-perk-item {
        display: flex; gap: 12px; align-items: center;
        padding: 12px 14px; background: #fff; border-radius: 14px;
        border: 1px solid rgba(15,23,42,0.05);
    }
    .hh-perk-item i { font-size: 1.25rem; }

    .hh-empty-cart {
        background: #fff; border-radius: var(--hh-radius);
        box-shadow: var(--hh-shadow); border: 1px solid rgba(15,23,42,0.04);
    }
    .hh-empty-icon {
        width: 110px; height: 110px; border-radius: 50%;
        background: linear-gradient(135deg, rgba(25,135,84,0.12), rgba(233,30,140,0.10));
        color: var(--hh-primary); display: grid; place-items: center;
        font-size: 2.5rem;
    }

    .hh-alert {
        padding: 16px 18px; border-radius: 14px;
        background: linear-gradient(135deg, #fff8e6, #fff3d6);
        border: 1px solid rgba(245, 158, 11, 0.35);
        color: #92400e;
    }

    @media (max-width: 767.98px) {
        .hh-cart-item {
            grid-template-columns: 1fr;
            gap: 14px;
        }
        .hh-cart-item-controls {
            justify-content: space-between;
        }
        .hh-cart-thumb { width: 76px; height: 76px; }
        .hh-size-chip { min-width: 100px; }
    }
</style>

<script>
(function () {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
    const fmt = (n) => new Intl.NumberFormat('vi-VN').format(Math.max(0, Math.round(n)));

    function recalcTotals() {
        let subtotal = 0;
        document.querySelectorAll('.cart-item').forEach((item) => {
            const qty = parseInt(item.querySelector('.qty-input').value, 10) || 0;
            const price = parseFloat(item.querySelector('.qty-input').dataset.price || '0');
            subtotal += qty * price;
        });
        const subEl = document.getElementById('subtotalDisplay');
        const totalEl = document.getElementById('totalDisplay');
        if (subEl) subEl.textContent = fmt(subtotal) + ' ₫';
        if (totalEl) totalEl.textContent = fmt(subtotal) + ' ₫';
    }

    function updateItemDisplay(item, qty) {
        const price = parseFloat(item.querySelector('.qty-input').dataset.price || '0');
        const totalEl = item.querySelector('.item-subtotal');
        if (totalEl) totalEl.textContent = fmt(qty * price);
    }

    function updateCart(item, qty, variant) {
        const cartId = item.dataset.cartId;
        const body = { quantity: qty };
        if (variant !== undefined) body.variant = variant;
        return fetch(`/cart/${cartId}/update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: JSON.stringify(body)
        }).then(r => r.json());
    }

    function applyServerCartUpdate(item, data) {
        if (data.reload) { location.reload(); return; }
        if (data.price !== undefined) {
            const input = item.querySelector('.qty-input');
            input.dataset.price = data.price;
        }
        if (data.subtotal !== undefined) {
            const totalEl = item.querySelector('.item-subtotal');
            if (totalEl) totalEl.textContent = fmt(data.subtotal);
        }
        recalcTotals();
    }

    document.querySelectorAll('.variant-chip').forEach(chip => {
        chip.addEventListener('click', function () {
            if (this.classList.contains('is-active') || this.classList.contains('is-loading')) return;
            const item = this.closest('.cart-item');
            const variant = this.dataset.variant;
            const qty = parseInt(item.querySelector('.qty-input').value, 10) || 1;

            // Optimistic UI
            const group = this.closest('.hh-size-chips');
            group.querySelectorAll('.hh-size-chip').forEach(c => c.classList.remove('is-active'));
            this.classList.add('is-active', 'is-loading');

            updateCart(item, qty, variant).then(d => {
                this.classList.remove('is-loading');
                if (!d.success) {
                    alert(d.message || 'Không cập nhật được kích thước');
                    location.reload();
                    return;
                }
                applyServerCartUpdate(item, d);
            });
        });
    });

    document.querySelectorAll('.increase-qty').forEach(btn => {
        btn.addEventListener('click', function () {
            const item = this.closest('.cart-item');
            const input = item.querySelector('.qty-input');
            const max = parseInt(input.max, 10) || 99;
            const next = Math.min(max, (parseInt(input.value, 10) || 1) + 1);
            if (next === parseInt(input.value, 10)) return;
            input.value = next;
            updateItemDisplay(item, next);
            recalcTotals();
            updateCart(item, next).then(d => { if (!d.success) { alert(d.message); location.reload(); } });
        });
    });

    document.querySelectorAll('.decrease-qty').forEach(btn => {
        btn.addEventListener('click', function () {
            const item = this.closest('.cart-item');
            const input = item.querySelector('.qty-input');
            const next = Math.max(1, (parseInt(input.value, 10) || 1) - 1);
            if (next === parseInt(input.value, 10)) return;
            input.value = next;
            updateItemDisplay(item, next);
            recalcTotals();
            updateCart(item, next).then(d => { if (!d.success) { alert(d.message); location.reload(); } });
        });
    });

    document.querySelectorAll('.qty-input').forEach(input => {
        input.addEventListener('change', function () {
            const item = this.closest('.cart-item');
            let v = parseInt(this.value, 10);
            const max = parseInt(this.max, 10) || 99;
            if (!v || v < 1) v = 1;
            if (v > max) v = max;
            this.value = v;
            updateItemDisplay(item, v);
            recalcTotals();
            updateCart(item, v).then(d => { if (!d.success) { alert(d.message); location.reload(); } });
        });
    });

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Xoá sản phẩm này khỏi giỏ hàng?')) return;
            const cartId = this.dataset.cartId;
            fetch(`/cart/${cartId}/remove`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            }).then(r => r.json()).then(d => {
                if (!d.success) { alert(d.message); return; }
                const item = this.closest('.cart-item');
                item.style.opacity = '0';
                item.style.transition = 'opacity .25s';
                setTimeout(() => {
                    item.remove();
                    if (!document.querySelectorAll('.cart-item').length) location.reload();
                    else recalcTotals();
                }, 220);
            });
        });
    });

    document.getElementById('clearCartBtn')?.addEventListener('click', function () {
        if (!confirm('Xoá toàn bộ giỏ hàng?')) return;
        fetch('/cart/clear', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
        }).then(r => r.json()).then(d => { if (d.success) location.reload(); });
    });
})();
</script>
@endsection
