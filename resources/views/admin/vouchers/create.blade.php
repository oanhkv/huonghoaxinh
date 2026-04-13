@extends('admin.layouts.admin')

@section('title', 'Tạo mã giảm giá')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.vouchers.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Mã giảm giá</label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" placeholder="VD: SUMMER10">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Tên voucher</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="VD: Giảm giá mùa hè">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Loại giảm</label>
                    <select name="type" class="form-select">
                        <option value="percent" {{ old('type') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Giá trị</label>
                    <input type="number" step="0.01" name="value" class="form-control" value="{{ old('value') }}" placeholder="VD: 10 hoặc 50000">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Đơn tối thiểu</label>
                    <input type="number" step="0.01" name="min_order_amount" class="form-control" value="{{ old('min_order_amount') }}" placeholder="VD: 300000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Giảm tối đa (chỉ cho %)</label>
                    <input type="number" step="0.01" name="max_discount_amount" class="form-control" value="{{ old('max_discount_amount') }}" placeholder="VD: 100000">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Số lượt dùng</label>
                    <input type="number" name="usage_limit" class="form-control" value="{{ old('usage_limit') }}" placeholder="VD: 100">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Bắt đầu từ</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kết thúc lúc</label>
                    <input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at') }}">
                </div>
                <div class="col-12 form-check ms-2">
                    <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
                    <label class="form-check-label">Kích hoạt ngay</label>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-success">Lưu voucher</button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection