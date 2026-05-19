@extends('admin.layouts.admin')

@section('title', 'Chat với khách hàng')

@section('content')
@php
    $m = $contactMessage;
    $hasInitial = trim((string) $m->message) !== '';
    $totalMessages = $m->replies->count() + ($hasInitial ? 1 : 0);
@endphp
<div class="container-fluid">
    <div class="card shadow-sm border-0 content-card">
        <div class="card-header d-flex align-items-start justify-content-between flex-wrap gap-2">
            <div>
                <div class="fw-bold">
                    <i class="far fa-comments text-success me-1"></i>
                    Chat với {{ $m->user?->name ?? $m->name }}
                </div>
                <div class="text-muted small">
                    {{ $m->email }}@if($m->phone) · {{ $m->phone }} @endif ·
                    bắt đầu {{ $m->created_at?->format('d/m/Y H:i') }} ·
                    {{ $totalMessages }} tin nhắn
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.contact-messages.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
                <form action="{{ route('admin.contact-messages.destroy', $m) }}" method="POST"
                      onsubmit="return confirm('Xóa toàn bộ hộp thoại này?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm">
                        <i class="fas fa-trash me-1"></i> Xóa
                    </button>
                </form>
            </div>
        </div>

        <div id="chat-box" class="p-4"
             style="background:#fafafa; height:560px; overflow-y:auto;">

            @if ($hasInitial)
                <div class="chat-row chat-row-customer mb-3">
                    <div class="chat-bubble chat-bubble-customer">
                        <div class="chat-text">{{ $m->message }}</div>
                    </div>
                    <div class="chat-meta">
                        <i class="far fa-user-circle me-1"></i>{{ $m->name }} ·
                        {{ $m->created_at?->format('H:i d/m/Y') }}
                    </div>
                </div>
            @endif

            @if (! $hasInitial && $m->replies->isEmpty())
                <div id="chat-empty" class="text-center text-muted py-5">
                    <i class="far fa-comment-dots fa-3x mb-3 d-block text-success-emphasis"></i>
                    <p class="mb-0">Khách chưa nhắn gì. Hãy gửi lời chào để bắt đầu cuộc trò chuyện.</p>
                </div>
            @endif

            @foreach ($m->replies->sortBy('created_at') as $r)
                @if ($r->isFromCustomer())
                    <div class="chat-row chat-row-customer mb-3" data-id="{{ $r->id }}">
                        <div class="chat-bubble chat-bubble-customer">
                            <div class="chat-text">{{ $r->body }}</div>
                        </div>
                        <div class="chat-meta">
                            <i class="far fa-user-circle me-1"></i>{{ $r->user?->name ?? $m->name }} ·
                            {{ $r->created_at?->format('H:i d/m/Y') }}
                        </div>
                    </div>
                @else
                    <div class="chat-row chat-row-shop mb-3" data-id="{{ $r->id }}">
                        <div class="chat-bubble chat-bubble-shop">
                            <div class="chat-text">{{ $r->body }}</div>
                        </div>
                        <div class="chat-meta text-end">
                            <i class="fas fa-store me-1"></i>{{ $r->admin?->name ?? 'Shop' }} ·
                            {{ $r->created_at?->format('H:i d/m/Y') }}
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <div class="border-top bg-white p-3">
            <form id="chat-form" method="POST" action="{{ route('admin.contact-messages.reply', $m) }}">
                @csrf
                <div class="input-group">
                    <textarea name="body" id="chat-input" rows="1"
                              class="form-control @error('body') is-invalid @enderror"
                              placeholder="Trả lời khách… (Enter để gửi, Shift + Enter để xuống dòng)"
                              required maxlength="3000"
                              style="resize:none;">{{ old('body') }}</textarea>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="far fa-paper-plane"></i>
                    </button>
                </div>
                @error('body') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </form>
        </div>
    </div>
</div>

<style>
    .chat-row { display: flex; flex-direction: column; max-width: 80%; }
    .chat-row-customer { margin-right: auto; align-items: flex-start; }
    .chat-row-shop     { margin-left: auto; align-items: flex-end; }
    .chat-bubble {
        padding: 10px 14px; border-radius: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,.05);
        white-space: pre-wrap; word-wrap: break-word;
    }
    .chat-bubble-customer { background: #ffffff; border: 1px solid #e5e7eb; border-bottom-left-radius: 4px; }
    .chat-bubble-shop     { background: #d1e7dd; border-bottom-right-radius: 4px; }
    .chat-meta { font-size: 12px; color: #6b7280; margin-top: 4px; }
    #chat-input { border: none; box-shadow: none; }
    #chat-input:focus { box-shadow: none; outline: none; }
</style>

<script>
(function () {
    const chatBox = document.getElementById('chat-box');
    const form    = document.getElementById('chat-form');
    const input   = document.getElementById('chat-input');
    const emptyEl = document.getElementById('chat-empty');
    const pollUrl = "{{ route('admin.contact-messages.poll', $m) }}";
    const csrf    = "{{ csrf_token() }}";
    const postUrl = form.action;

    let lastId    = {{ $m->replies->max('id') ?: 0 }};
    let isPolling = false;        // mutex: tránh 2 poll() chạy chồng nhau gây duplicate
    let isSending = false;        // tránh double-submit (Enter spam)

    function scrollBottom() { chatBox.scrollTop = chatBox.scrollHeight; }

    function renderMessage(msg) {
        // De-dupe: nếu đã có row id này trong DOM → bỏ qua
        if (chatBox.querySelector('[data-id="' + msg.id + '"]')) return;

        // is_customer = true → khách bên trái; false → admin bên phải
        const row = document.createElement('div');
        row.className = 'chat-row mb-3 ' + (msg.is_customer ? 'chat-row-customer' : 'chat-row-shop');
        row.dataset.id = msg.id;

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + (msg.is_customer ? 'chat-bubble-customer' : 'chat-bubble-shop');
        const text = document.createElement('div');
        text.className = 'chat-text';
        text.textContent = msg.body;
        bubble.appendChild(text);

        const meta = document.createElement('div');
        meta.className = 'chat-meta' + (msg.is_customer ? '' : ' text-end');
        const icon = msg.is_customer
            ? '<i class="far fa-user-circle me-1"></i>'
            : '<i class="fas fa-store me-1"></i>';
        meta.innerHTML = icon + msg.sender_name + ' · ' + msg.created_at;

        row.appendChild(bubble);
        row.appendChild(meta);
        chatBox.appendChild(row);
        if (emptyEl) emptyEl.remove();
    }

    async function poll() {
        if (isPolling) return;        // đang fetch dở → bỏ qua
        isPolling = true;
        try {
            const r = await fetch(pollUrl + '?since=' + lastId, { headers: { Accept: 'application/json' } });
            if (! r.ok) return;
            const data = await r.json();
            if (data.items && data.items.length) {
                data.items.forEach(renderMessage);
                const maxId = data.items.reduce((m, it) => Math.max(m, it.id || 0), lastId);
                lastId = Math.max(data.last_id || 0, maxId);
                scrollBottom();
            }
        } catch (e) {}
        finally { isPolling = false; }
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (isSending) return;
        const body = input.value.trim();
        if (! body) return;
        isSending = true;
        const submitBtn = form.querySelector('button[type=submit]');
        if (submitBtn) submitBtn.disabled = true;

        const fd = new FormData();
        fd.append('_token', csrf);
        fd.append('body', body);
        try {
            const r = await fetch(postUrl, {
                method: 'POST',
                body: fd,
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            if (r.ok) {
                input.value = '';
                input.style.height = 'auto';
                await poll();
            }
        } catch (e) {}
        finally {
            isSending = false;
            if (submitBtn) submitBtn.disabled = false;
            input.focus();
        }
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && ! e.shiftKey) {
            e.preventDefault();
            if (! isSending) form.requestSubmit();
        }
    });
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    });

    scrollBottom();
    setInterval(poll, 4000);
})();
</script>
@endsection
