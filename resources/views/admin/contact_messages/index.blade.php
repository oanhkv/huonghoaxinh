@extends('admin.layouts.admin')

@section('title', 'Chat với khách hàng')

@section('content')
<div class="container-fluid">
    <div class="card shadow-sm border-0 content-card hhx-inbox-card">
        {{-- ===== Header ===== --}}
        <div class="card-header bg-transparent border-0 p-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="fw-bold mb-1">
                        <i class="far fa-comments text-success me-2"></i> Hộp thoại với khách hàng
                    </h5>
                    <div class="text-muted small">Toàn bộ hội thoại trực tiếp giữa shop và khách. Click để mở chat.</div>
                </div>

                <form method="GET" class="d-flex gap-2 flex-wrap">
                    <div class="input-group hhx-inbox-search">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-magnifying-glass text-muted"></i>
                        </span>
                        <input type="text" name="q" value="{{ request('q') }}"
                               class="form-control border-start-0"
                               placeholder="Tìm theo tên / email / nội dung...">
                        @if(request()->has('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </div>
                    <button class="btn btn-success px-3">Tìm</button>
                </form>
            </div>

            {{-- Tabs trạng thái --}}
            <div class="hhx-status-tabs mt-3">
                @php
                    $tabs = [
                        ['key' => '',               'label' => 'Tất cả',             'icon' => 'inbox',           'count' => $statusCounts['all']],
                        ['key' => 'awaiting_reply', 'label' => 'Khách phản hồi mới', 'icon' => 'reply',           'count' => $statusCounts['awaiting_reply']],
                        ['key' => 'new',            'label' => 'Tin mới',            'icon' => 'envelope-open',   'count' => $statusCounts['new']],
                        ['key' => 'replied',        'label' => 'Đã trả lời',         'icon' => 'circle-check',    'count' => $statusCounts['replied']],
                    ];
                    $currentTab = request('status') ?: '';
                @endphp
                @foreach($tabs as $tab)
                    @php
                        $url = $tab['key']
                            ? route('admin.contact-messages.index', array_merge(request()->except(['status','page']), ['status' => $tab['key']]))
                            : route('admin.contact-messages.index', request()->except(['status','page']));
                        $isActive = $currentTab === $tab['key'];
                    @endphp
                    <a href="{{ $url }}" class="hhx-status-tab {{ $isActive ? 'is-active' : '' }}">
                        <i class="fas fa-{{ $tab['icon'] }} me-2"></i>
                        {{ $tab['label'] }}
                        <span class="hhx-status-tab-count">{{ $tab['count'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ===== Danh sách hội thoại (chat-inbox style) ===== --}}
        <div class="card-body p-0">
            <div class="hhx-inbox-list">
                @forelse($messages as $m)
                    @php
                        $lastReply = $m->replies->first();
                        $lastMessage = $lastReply
                            ? $lastReply->body
                            : ($m->message ?: '(Chưa có nội dung)');
                        $lastTime = $lastReply?->created_at ?? $m->created_at;
                        $lastFromCustomer = $lastReply
                            ? $lastReply->isFromCustomer()
                            : true;
                        $displayName = $m->user?->name ?? $m->name;
                        $initials = collect(explode(' ', trim($displayName)))
                            ->map(fn($s) => mb_substr($s, 0, 1))
                            ->take(2)
                            ->implode('');
                        $isAwaiting = $m->status === 'awaiting_reply';
                        $isNew = $m->status === 'new';
                        $isUnread = $isAwaiting || $isNew;
                        $avatarColors = ['#f06595','#20a464','#5e60ce','#f59f00','#e8590c','#0ca678','#d6336c'];
                        $color = $avatarColors[$m->id % count($avatarColors)];
                    @endphp
                    <a href="{{ route('admin.contact-messages.show', $m) }}"
                       class="hhx-inbox-item {{ $isUnread ? 'is-unread' : '' }}">
                        <div class="hhx-inbox-avatar" style="background: {{ $color }};">
                            {{ $initials ?: '?' }}
                            @if($isAwaiting)
                                <span class="hhx-inbox-avatar-dot" title="Có tin chưa đọc"></span>
                            @endif
                        </div>
                        <div class="hhx-inbox-content">
                            <div class="hhx-inbox-line-1">
                                <span class="hhx-inbox-name">{{ $displayName }}</span>
                                @if($isAwaiting)
                                    <span class="hhx-inbox-tag awaiting"><i class="fas fa-bell me-1"></i>Khách vừa nhắn</span>
                                @elseif($isNew)
                                    <span class="hhx-inbox-tag new">Mới</span>
                                @elseif($m->status === 'replied')
                                    <span class="hhx-inbox-tag replied"><i class="fas fa-check me-1"></i>Đã trả lời</span>
                                @endif
                                <span class="hhx-inbox-time">{{ $lastTime?->diffForHumans() }}</span>
                            </div>
                            <div class="hhx-inbox-line-2">
                                <span class="text-muted small me-1">
                                    @if($lastFromCustomer)
                                        <i class="far fa-user me-1"></i>{{ $displayName }}:
                                    @else
                                        <i class="fas fa-store me-1 text-success"></i>Shop:
                                    @endif
                                </span>
                                <span class="hhx-inbox-preview">{{ \Illuminate\Support\Str::limit($lastMessage, 90) }}</span>
                            </div>
                            <div class="hhx-inbox-line-3">
                                <span><i class="fas fa-envelope me-1"></i>{{ $m->email }}</span>
                                @if($m->phone)<span class="ms-3"><i class="fas fa-phone me-1"></i>{{ $m->phone }}</span>@endif
                                <span class="ms-3"><i class="far fa-comment-dots me-1"></i>{{ $m->replies_count }} tin nhắn</span>
                            </div>
                        </div>
                        <div class="hhx-inbox-arrow">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5">
                        <i class="far fa-comments fa-3x text-muted mb-3 d-block"></i>
                        <h6 class="text-muted">Chưa có hội thoại nào</h6>
                        <p class="text-muted small mb-0">Khi khách bắt đầu chat, hội thoại sẽ xuất hiện tại đây.</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($messages->hasPages())
            <div class="card-footer bg-transparent border-0 px-4 py-3">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .hhx-inbox-card { border-radius: 16px; overflow: hidden; }

    .hhx-inbox-search { min-width: 320px; max-width: 420px; }
    .hhx-inbox-search .form-control,
    .hhx-inbox-search .input-group-text {
        border-radius: 999px;
        background: #f7f8fa;
    }
    .hhx-inbox-search .input-group-text { border-right: 0; }
    .hhx-inbox-search .form-control {
        border-top-left-radius: 0; border-bottom-left-radius: 0;
        border-left: 0;
    }
    .hhx-inbox-search .input-group-text {
        border-top-right-radius: 0; border-bottom-right-radius: 0;
    }

    .hhx-status-tabs {
        display: flex; gap: 8px; flex-wrap: wrap;
        padding-top: 4px;
    }
    .hhx-status-tab {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #4b5563;
        font-weight: 600; font-size: 13px;
        text-decoration: none;
        transition: all .2s ease;
        border: 1.5px solid transparent;
    }
    .hhx-status-tab:hover {
        background: #e5e7eb; color: #1f2937;
    }
    .hhx-status-tab.is-active {
        background: linear-gradient(135deg, #198754, #20a464);
        color: #fff;
        box-shadow: 0 4px 12px rgba(25, 135, 84, .25);
    }
    .hhx-status-tab.is-active .hhx-status-tab-count {
        background: rgba(255, 255, 255, .25);
        color: #fff;
    }
    .hhx-status-tab-count {
        margin-left: 4px;
        background: #fff;
        color: #1f2937;
        font-size: 11px;
        padding: 1px 8px;
        border-radius: 999px;
        font-weight: 700;
    }

    .hhx-inbox-list { display: flex; flex-direction: column; }
    .hhx-inbox-item {
        display: flex; align-items: center; gap: 16px;
        padding: 16px 24px;
        border-top: 1px solid #f1f2f6;
        text-decoration: none; color: inherit;
        background: #fff;
        transition: background .2s ease, transform .15s ease;
    }
    .hhx-inbox-item:hover {
        background: #f9fafb;
        text-decoration: none; color: inherit;
    }
    .hhx-inbox-item.is-unread {
        background: linear-gradient(90deg, #fff5f9 0%, #ffffff 60%);
        border-left: 3px solid #d63384;
        padding-left: 21px;
    }
    .hhx-inbox-item.is-unread:hover { background: #fff0f6; }

    .hhx-inbox-avatar {
        position: relative;
        width: 50px; height: 50px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: 17px;
        text-transform: uppercase;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .hhx-inbox-avatar-dot {
        position: absolute;
        bottom: -2px; right: -2px;
        width: 14px; height: 14px;
        border-radius: 50%;
        background: #ef4444;
        border: 2px solid #fff;
        animation: hhxAvatarDot 1.6s ease infinite;
    }
    @keyframes hhxAvatarDot {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .hhx-inbox-content { flex: 1; min-width: 0; }
    .hhx-inbox-line-1 {
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 4px;
        flex-wrap: wrap;
    }
    .hhx-inbox-name {
        font-weight: 700; font-size: 15px; color: #1f2937;
    }
    .hhx-inbox-item.is-unread .hhx-inbox-name { color: #111827; }

    .hhx-inbox-tag {
        font-size: 11px; font-weight: 700;
        padding: 3px 10px; border-radius: 999px;
        display: inline-flex; align-items: center;
    }
    .hhx-inbox-tag.awaiting { background: #fee2e2; color: #b91c1c; }
    .hhx-inbox-tag.new      { background: #fef3c7; color: #92400e; }
    .hhx-inbox-tag.replied  { background: #d1fae5; color: #065f46; }

    .hhx-inbox-time {
        font-size: 12px; color: #9ca3af;
        margin-left: auto;
    }

    .hhx-inbox-line-2 {
        display: flex; align-items: center;
        font-size: 14px;
        margin-bottom: 4px;
        overflow: hidden;
    }
    .hhx-inbox-preview {
        color: #6b7280;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        flex: 1;
    }
    .hhx-inbox-item.is-unread .hhx-inbox-preview {
        color: #1f2937; font-weight: 500;
    }

    .hhx-inbox-line-3 {
        font-size: 12px; color: #9ca3af;
    }

    .hhx-inbox-arrow {
        color: #d1d5db;
        font-size: 14px;
        transition: transform .2s ease, color .2s ease;
    }
    .hhx-inbox-item:hover .hhx-inbox-arrow {
        color: #198754;
        transform: translateX(4px);
    }

    @media (max-width: 575.98px) {
        .hhx-inbox-search { min-width: 100%; }
        .hhx-inbox-item { padding: 14px 16px; gap: 12px; }
        .hhx-inbox-item.is-unread { padding-left: 13px; }
        .hhx-inbox-avatar { width: 44px; height: 44px; font-size: 15px; }
        .hhx-inbox-time { display: none; }
    }
</style>
@endsection
