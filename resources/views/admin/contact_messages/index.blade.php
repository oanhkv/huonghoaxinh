@extends('admin.layouts.admin')

@section('title', 'Tin nhắn liên hệ')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 content-card">
        <div class="card-header d-flex flex-wrap gap-2 align-items-center justify-content-between">
            <div>
                <div class="fw-bold">Hộp thư liên hệ</div>
                <div class="text-muted small">Quản lý tin nhắn khách gửi từ trang Liên hệ và phản hồi qua email.</div>
            </div>

            <form method="GET" class="d-flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" style="min-width: 260px;" placeholder="Tìm theo tên/email/tiêu đề...">
                <select name="status" class="form-select" style="min-width: 170px;">
                    <option value="">-- Trạng thái --</option>
                    <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>Mới</option>
                    <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Đã xem</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Đã phản hồi</option>
                </select>
                <button class="btn btn-primary">Lọc</button>
            </form>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover admin-table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="140">Trạng thái</th>
                            <th>Khách hàng</th>
                            <th>Tiêu đề</th>
                            <th width="160">Thời gian</th>
                            <th width="120">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($messages as $m)
                            @php
                                $badge = $m->status === 'replied' ? 'success' : ($m->status === 'read' ? 'info' : 'warning');
                                $statusLabel = $m->status === 'replied' ? 'Đã phản hồi' : ($m->status === 'read' ? 'Đã xem' : 'Mới');
                            @endphp
                            <tr>
                                <td>
                                    <span class="badge bg-{{ $badge }} px-3 py-2">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $m->name }}</div>
                                    <div class="small text-muted">{{ $m->email }} @if($m->phone) · {{ $m->phone }} @endif</div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $m->subject ?: 'Liên hệ' }}</div>
                                    <div class="small text-muted">{{ Str::limit($m->message, 88) }}</div>
                                </td>
                                <td class="text-muted small">
                                    {{ $m->created_at?->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.contact-messages.show', $m) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i> Xem
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">Chưa có tin nhắn nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer">
            {{ $messages->links() }}
        </div>
    </div>
</div>
@endsection

