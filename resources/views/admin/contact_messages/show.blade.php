@extends('admin.layouts.admin')

@section('title', 'Chi tiết tin nhắn')

@section('content')
@php
    $m = $contactMessage;
@endphp
<div class="container-fluid">
    <div class="row g-4">
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 content-card">
                <div class="card-header d-flex align-items-start justify-content-between flex-wrap gap-2">
                    <div>
                        <div class="fw-bold">{{ $m->subject ?: 'Liên hệ' }}</div>
                        <div class="text-muted small">
                            {{ $m->created_at?->format('d/m/Y H:i') }} ·
                            <span class="fw-semibold">{{ $m->name }}</span> ({{ $m->email }})
                            @if($m->phone) · {{ $m->phone }} @endif
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <form action="{{ route('admin.contact-messages.destroy', $m) }}" method="POST" onsubmit="return confirm('Xóa tin nhắn này?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="p-3 rounded-4 border bg-white">
                        <div class="small text-muted mb-2">Nội dung khách gửi</div>
                        <div style="white-space: pre-wrap;">{{ $m->message }}</div>
                    </div>

                    <div class="mt-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="fw-bold mb-0">Lịch sử phản hồi</h6>
                            <span class="text-muted small">{{ $m->replies->count() }} phản hồi</span>
                        </div>
                        <div class="mt-3">
                            @forelse($m->replies as $r)
                                <div class="p-3 rounded-4 border bg-light mb-3">
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <div>
                                            <div class="fw-semibold">{{ $r->admin->name ?? 'Admin' }}</div>
                                            <div class="small text-muted">{{ $r->created_at?->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="text-end">
                                            @if($r->sent_at)
                                                <span class="badge bg-success">Đã gửi email</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Chưa gửi</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($r->subject)
                                        <div class="small text-muted mt-2">Tiêu đề: <strong>{{ $r->subject }}</strong></div>
                                    @endif
                                    <div class="mt-2" style="white-space: pre-wrap;">{{ $r->body }}</div>
                                    @if($r->send_error)
                                        <div class="alert alert-danger mt-3 mb-0">
                                            <div class="fw-bold small">Lỗi gửi email</div>
                                            <div class="small">{{ $r->send_error }}</div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-muted small">Chưa có phản hồi nào.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card shadow-sm border-0 content-card">
                <div class="card-header">
                    <div class="fw-bold">Phản hồi khách hàng</div>
                    <div class="text-muted small">Gửi email trả lời tới {{ $m->email }} và lưu lại lịch sử.</div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.contact-messages.reply', $m) }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề email</label>
                            <input type="text" name="subject" class="form-control @error('subject') is-invalid @enderror"
                                   value="{{ old('subject', $m->subject ? ('Re: ' . $m->subject) : 'Re: Liên hệ từ Hương Hoa Xinh') }}">
                            @error('subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung phản hồi <span class="text-danger">*</span></label>
                            <textarea name="body" rows="8" class="form-control @error('body') is-invalid @enderror" required
                                      placeholder="Nhập nội dung phản hồi...">{{ old('body') }}</textarea>
                            @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Email sẽ được gửi từ địa chỉ cấu hình SMTP trong `.env`.</div>
                        </div>
                        <button class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Gửi phản hồi
                        </button>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0 content-card mt-4">
                <div class="card-body">
                    <div class="fw-bold mb-2">Gợi ý cấu hình email (SMTP)</div>
                    <div class="small text-muted">
                        Để gửi được email thật (Gmail), bạn cần cấu hình `MAIL_MAILER=smtp` và App Password cho Gmail trong `.env`.
                        Inbox cửa hàng đang đặt là <strong>{{ config('shop.contact_inbox_email') }}</strong>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

