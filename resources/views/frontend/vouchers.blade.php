@extends('frontend.layouts.app')

@section('title', 'Mã giảm giá - Hương Hoa Xinh')

@section('content')
<div class="container py-5">

    <div class="row mb-4 align-items-center">
        <div class="col-lg-8">
            <p class="text-success fw-bold mb-2">🎁 Khuyến mãi đặc biệt</p>
            <h1 class="display-6 fw-bold mb-2">Mã giảm giá độc quyền</h1>
            <p class="text-muted mb-0">Sử dụng các mã giảm giá này để nhận ưu đãi tuyệt vời khi mua hàng tại cửa hàng Hương Hoa Xinh.</p>
        </div>
        <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="{{ route('shop') }}" class="btn btn-success btn-lg shadow-sm">
                <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Danh mục -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4 border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Bộ lọc</h5>
                </div>
                <div class="card-body p-3 bg-light">
                    <div class="filter-group">
                        <h6 class="mb-3">Loại mã giảm giá</h6>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="all" checked>
                            <label class="form-check-label" for="all">
                                Tất cả
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="percent">
                            <label class="form-check-label" for="percent">
                                Giảm theo phần trăm (%)
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="fixed">
                            <label class="form-check-label" for="fixed">
                                Giảm số tiền cố định
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">💡 Mẹo sử dụng</h5>
                </div>
                <div class="card-body p-3">
                    <ul class="small">
                        <li class="mb-2">Sao chép mã giảm giá bằng cách nhấn vào nó</li>
                        <li class="mb-2">Dán mã vào ô "Mã giảm giá" khi thanh toán</li>
                        <li class="mb-2">Mỗi mã chỉ được sử dụng một lần cho mỗi đơn hàng</li>
                        <li>Kiểm tra hạn sử dụng trước khi đặt hàng</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Nội dung chính -->
        <div class="col-lg-9">
            <div class="row g-4">
                @forelse($vouchers as $voucher)
                    <div class="col-lg-6">
                        <div class="card h-100 shadow-sm border-0 voucher-card position-relative" style="overflow: hidden;">
                            <div class="card-body p-4">
                                <!-- Ribbon góc -->
                                @if($voucher->type === 'percent')
                                    <div class="position-absolute" style="top: -30px; right: -30px; width: 80px; height: 80px; background-color: #28a745; transform: rotate(45deg); display: flex; align-items: center; justify-content: center;">
                                        <span class="text-white fw-bold" style="transform: rotate(-45deg); font-size: 18px;">{{ $voucher->value }}%</span>
                                    </div>
                                @else
                                    <div class="position-absolute" style="top: -30px; right: -30px; width: 80px; height: 80px; background-color: #28a745; transform: rotate(45deg); display: flex; align-items: center; justify-content: center;">
                                        <span class="text-white fw-bold" style="transform: rotate(-45deg); font-size: 14px;">{{ number_format($voucher->value) }}đ</span>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <h6 class="mb-1 text-muted small">MÃ GIẢM GIÁ</h6>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="code-box bg-light border border-success rounded px-3 py-2 flex-grow-1" style="font-family: 'Courier New', monospace; cursor: pointer;" onclick="copyCode('{{ $voucher->code }}', this)">
                                            <strong class="text-success">{{ $voucher->code }}</strong>
                                        </div>
                                        <button class="btn btn-success btn-sm" onclick="copyCode('{{ $voucher->code }}')" title="Sao chép mã">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </div>
                                </div>

                                <h5 class="card-title mb-2">{{ $voucher->name }}</h5>

                                <div class="row g-2 mb-3">
                                    @if($voucher->min_order_amount > 0)
                                        <div class="col-6">
                                            <small class="text-muted d-block">Đơn hàng tối thiểu</small>
                                            <strong>{{ number_format($voucher->min_order_amount) }}đ</strong>
                                        </div>
                                    @endif

                                    @if($voucher->max_discount_amount > 0)
                                        <div class="col-6">
                                            <small class="text-muted d-block">Giảm tối đa</small>
                                            <strong>{{ number_format($voucher->max_discount_amount) }}đ</strong>
                                        </div>
                                    @endif

                                    @if($voucher->usage_limit > 0)
                                        <div class="col-6">
                                            <small class="text-muted d-block">Lượt sử dụng</small>
                                            <strong>{{ $voucher->used_count }}/{{ $voucher->usage_limit }}</strong>
                                        </div>
                                    @endif

                                    <div class="col-6">
                                        <small class="text-muted d-block">Hạn sử dụng</small>
                                        <strong>{{ $voucher->ends_at->format('d/m/Y') }}</strong>
                                    </div>
                                </div>

                                <div class="progress mb-3" style="height: 6px;">
                                    @php
                                        $percentage = $voucher->usage_limit > 0 ? ($voucher->used_count / $voucher->usage_limit) * 100 : 0;
                                    @endphp
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percentage }}%"></div>
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('shop') }}" class="btn btn-success flex-grow-1">
                                        <i class="fas fa-shopping-cart me-2"></i>Sử dụng mã
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info text-center py-5" role="alert">
                            <i class="fas fa-info-circle fa-2x mb-3 text-info"></i>
                            <p class="mb-0">Hiện không có mã giảm giá nào disponible. Vui lòng quay lại sau!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Phân trang -->
            @if($vouchers->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $vouchers->render() }}
                </div>
            @endif
        </div>
    </div>

</div>

<style>
    .voucher-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .voucher-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(40, 167, 69, 0.15) !important;
    }

    .code-box {
        transition: all 0.3s ease;
        user-select: all;
    }

    .code-box:hover {
        background-color: #f0f8ff !important;
        border-color: #20c997 !important;
    }

    .filter-group {
        padding: 10px 0;
    }

    .form-check {
        margin-bottom: 10px;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-label {
        cursor: pointer;
        margin-left: 5px;
    }
</style>

<script>
    function copyCode(code, element) {
        const ev = window.event;
        const btn = element || (ev && ev.target ? ev.target.closest('button, .code-box') : null);

        const onSuccess = () => {
            try {
                if (btn) {
                    const originalHTML = btn.innerHTML;
                    btn.innerHTML = '<i class="fas fa-check"></i>';
                    btn.classList.add('copied');
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.classList.remove('copied');
                    }, 2000);
                }
            } catch (e) { /* no-op: chỉ là hiệu ứng UI */ }
            showToast('Đã sao chép mã ' + code + '!', 'success');
        };

        // Cách 1: Clipboard API (yêu cầu secure context — https hoặc localhost)
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(code).then(onSuccess).catch(() => fallbackCopy(code, onSuccess));
            return;
        }
        // Cách 2: Fallback dùng textarea + execCommand
        fallbackCopy(code, onSuccess);
    }

    function fallbackCopy(text, onSuccess) {
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.style.cssText = 'position:fixed;top:-9999px;left:-9999px;opacity:0;';
        document.body.appendChild(ta);
        ta.focus(); ta.select();
        try {
            const ok = document.execCommand('copy');
            document.body.removeChild(ta);
            if (ok) onSuccess(); else showToast('Không thể sao chép. Hãy copy thủ công: ' + text, 'danger');
        } catch (e) {
            document.body.removeChild(ta);
            showToast('Không thể sao chép. Hãy copy thủ công: ' + text, 'danger');
        }
    }

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} position-fixed shadow-sm border-0 rounded-3 fade show`;
        toast.style.cssText = 'bottom: 20px; right: 20px; z-index: 9999; min-width: 280px; animation: hhxToastIn .25s ease;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                <span>${message}</span>
            </div>
        `;
        document.body.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; }, 2400);
        setTimeout(() => toast.remove(), 2800);
    }
    // CSS keyframe inline cho toast slide-in
    if (!document.getElementById('hhxToastStyle')) {
        const s = document.createElement('style'); s.id = 'hhxToastStyle';
        s.textContent = '@keyframes hhxToastIn{from{transform:translateY(20px);opacity:0}to{transform:translateY(0);opacity:1}}';
        document.head.appendChild(s);
    }
</script>
@endsection
