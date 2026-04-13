@extends('admin.layouts.admin')

@section('title', 'Sửa mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')
                <div class="col-md-6">
                    <label class="form-label">Mã giảm giá</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $voucher->code) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tên voucher</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $voucher->name) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Loại giảm</label>
                    <select name="type" class="form-select">
                        <option value="percent" {{ old('type', $voucher->type) === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giá trị</label>
                    <input type="number" step="0.01" name="value" class="form-control" value="{{ old('value', $voucher->value) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Đơn tối thiểu</label>
                    <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount', $voucher->min_order_amount) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giảm tối đa (chỉ cho %)</label>
                    <input type="number" step="0.01" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount', $voucher->max_discount_amount) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit', $voucher->usage_limit) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bắt đầu từ</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', optional($voucher->starts_at)->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kết thúc lúc</label>
                    <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', optional($voucher->ends_at)->format('Y-m-d\TH:i')) }}">
                </div>
                <div class="col-12 form-check ms-2">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label">Kích hoạt ngay</label>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection