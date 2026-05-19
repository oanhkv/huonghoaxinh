@extends('frontend.layouts.app')

@section('title', 'Hộp thư liên hệ - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Hộp thư liên hệ</h2>
                    <p class="text-muted mb-0">Theo dõi các tin nhắn bạn đã gửi và trao đổi qua lại với cửa hàng.</p>
                </div>
                <a href="{{ route('contact') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Gửi tin nhắn mới
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @forelse ($messages as $msg)
                @php
                    $statusLabel = match ($msg->status) {
                        'replied' => ['Đã có phản hồi', 'bg-success'],
                        'awaiting_reply' => ['Đang chờ shop trả lời', 'bg-warning text-dark'],
                        'read' => ['Đã đọc', 'bg-info'],
                        default => ['Mới gửi', 'bg-secondary'],
                    };
                @endphp
                <a href="{{ route('account.contact.show', $msg) }}"
                   class="card border-0 shadow-sm mb-3 text-decoration-none text-dark d-block">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                            <h5 class="mb-0">{{ $msg->subject ?: 'Liên hệ chung' }}</h5>
                            <span class="badge {{ $statusLabel[1] }}">{{ $statusLabel[0] }}</span>
                        </div>
                        <p class="text-muted mb-2" style="white-space: pre-line">{{ \Illuminate\Support\Str::limit($msg->message, 160) }}</p>
                        <div class="d-flex gap-3 small text-muted">
                            <span><i class="far fa-clock me-1"></i>{{ $msg->created_at?->format('d/m/Y H:i') }}</span>
                            <span><i class="far fa-comments me-1"></i>{{ $msg->replies_count }} phản hồi</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="alert alert-info text-center">
                    Bạn chưa có tin nhắn nào. <a href="{{ route('contact') }}">Gửi tin nhắn cho cửa hàng</a> để bắt đầu trao đổi.
                </div>
            @endforelse

            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection
