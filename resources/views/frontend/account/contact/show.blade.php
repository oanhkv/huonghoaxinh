@extends('frontend.layouts.app')

@section('title', 'Chat với cửa hàng - Hương Hoa Xinh')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h3 class="fw-bold mb-1">
                        <i class="far fa-comments text-success me-2"></i> Chat với Hương Hoa Xinh
                    </h3>
                    <p class="text-muted mb-0 small">
                        Hộp thoại trực tiếp – tất cả tin nhắn được lưu lại để bạn xem lại bất kỳ lúc nào.
                    </p>
                </div>
                <a href="{{ route('orders.history') }}" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-box me-1"></i> Đơn hàng của tôi
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- ===== Khung chat ===== --}}
            <div class="card border-0 shadow-sm" style="border-radius:16px; overflow:hidden;">
                <div id="chat-box" class="p-4"
                     style="background:#fafafa; height:520px; overflow-y:auto;">

                    @php
                        $hasInitial = trim((string) $contactMessage->message) !== '';
                    @endphp

                    @if ($hasInitial)
                        {{-- Tin nhắn đầu tiên (từ form contact cũ) --}}
                        <div class="chat-row chat-row-customer mb-3">
                            <div class="chat-bubble chat-bubble-customer">
                                <div class="chat-text">{{ $contactMessage->message }}</div>
                            </div>
                            <div class="chat-meta text-end">
                                <i class="far fa-user-circle me-1"></i>Bạn ·
                                {{ $contactMessage->created_at?->format('H:i d/m/Y') }}
                            </div>
                        </div>
                    @endif

                    @if ($contactMessage->replies->isEmpty() && ! $hasInitial)
                        <div id="chat-empty" class="text-center text-muted py-5">
                            <i class="far fa-comment-dots fa-3x mb-3 d-block text-success-emphasis"></i>
                            <p class="mb-0">Bắt đầu trò chuyện với cửa hàng bằng tin nhắn đầu tiên của bạn.</p>
                        </div>
                    @endif

                    @foreach ($contactMessage->replies as $reply)
                        @if ($reply->isFromCustomer())
                            <div class="chat-row chat-row-customer mb-3" data-id="{{ $reply->id }}">
                                <div class="chat-bubble chat-bubble-customer">
                                    <div class="chat-text">{{ $reply->body }}</div>
                                </div>
                                <div class="chat-meta text-end">
                                    <i class="far fa-user-circle me-1"></i>Bạn ·
                                    {{ $reply->created_at?->format('H:i d/m/Y') }}
                                </div>
                            </div>
                        @else
                            <div class="chat-row chat-row-shop mb-3" data-id="{{ $reply->id }}">
                                <div class="chat-bubble chat-bubble-shop">
                                    <div class="chat-text">{{ $reply->body }}</div>
                                </div>
                                <div class="chat-meta">
                                    <i class="fas fa-store me-1"></i>{{ $reply->admin?->name ?? 'Hương Hoa Xinh' }} ·
                                    {{ $reply->created_at?->format('H:i d/m/Y') }}
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                {{-- ===== Form gửi tin nhắn ===== --}}
                <div class="border-top bg-white p-3">
                    <form id="chat-form" method="POST" action="{{ route('account.contact.reply', $contactMessage) }}">
                        @csrf
                        <div class="input-group">
                            <textarea name="body" id="chat-input" rows="1"
                                      class="form-control"
                                      placeholder="Nhập tin nhắn… (Enter để gửi, Shift + Enter để xuống dòng)"
                                      required maxlength="3000"
                                      style="resize:none;"></textarea>
                            <button class="btn btn-success px-4" type="submit">
                                <i class="far fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .chat-row { display: flex; flex-direction: column; max-width: 80%; }
    .chat-row-customer { margin-left: auto; align-items: flex-end; }
    .chat-row-shop     { margin-right: auto; align-items: flex-start; }
    .chat-bubble {
        padding: 10px 14px; border-radius: 14px;
        box-shadow: 0 2px 6px rgba(0,0,0,.05);
        white-space: pre-wrap; word-wrap: break-word;
    }
    .chat-bubble-customer { background: #d1e7dd; border-bottom-right-radius: 4px; }
    .chat-bubble-shop     { background: #ffffff; border: 1px solid #e5e7eb; border-bottom-left-radius: 4px; }
    .chat-meta { font-size: 12px; color: #6b7280; margin-top: 4px; }
    #chat-input { border: none; box-shadow: none; }
    #chat-input:focus { box-shadow: none; outline: none; }
</style>

<script>
(function () {
    const chatBox  = document.getElementById('chat-box');
    const form     = document.getElementById('chat-form');
    const input    = document.getElementById('chat-input');
    const emptyEl  = document.getElementById('chat-empty');
    const pollUrl  = "{{ route('account.contact.poll', $contactMessage) }}";
    const csrf     = "{{ csrf_token() }}";
    const postUrl  = form.action;

    let lastId    = {{ $contactMessage->replies->max('id') ?: 0 }};
    let isPolling = false;        // mutex: tránh 2 poll() chạy chồng nhau gây duplicate
    let isSending = false;        // tránh double-submit (Enter spam)

    function scrollBottom() { chatBox.scrollTop = chatBox.scrollHeight; }

    function renderMessage(m) {
        // De-dupe: nếu đã có row với id này trong DOM thì bỏ qua
        if (chatBox.querySelector('[data-id="' + m.id + '"]')) return;

        const row    = document.createElement('div');
        row.className = 'chat-row mb-3 ' + (m.is_customer ? 'chat-row-customer' : 'chat-row-shop');
        row.dataset.id = m.id;

        const bubble = document.createElement('div');
        bubble.className = 'chat-bubble ' + (m.is_customer ? 'chat-bubble-customer' : 'chat-bubble-shop');
        const text = document.createElement('div');
        text.className = 'chat-text';
        text.textContent = m.body;
        bubble.appendChild(text);

        const meta = document.createElement('div');
        meta.className = 'chat-meta' + (m.is_customer ? ' text-end' : '');
        const icon = m.is_customer
            ? '<i class="far fa-user-circle me-1"></i>'
            : '<i class="fas fa-store me-1"></i>';
        meta.innerHTML = icon + m.sender_name + ' · ' + m.created_at;

        row.appendChild(bubble);
        row.appendChild(meta);
        chatBox.appendChild(row);
        if (emptyEl) emptyEl.remove();
    }

    async function poll() {
        if (isPolling) return;         // đang fetch dở → bỏ qua
        isPolling = true;
        try {
            const r = await fetch(pollUrl + '?since=' + lastId, { headers: { Accept: 'application/json' } });
            if (! r.ok) return;
            const data = await r.json();
            if (data.items && data.items.length) {
                data.items.forEach(renderMessage);
                // Lấy id lớn nhất trong batch để cập nhật cursor — kể cả khi
                // server không trả last_id (đề phòng).
                const maxId = data.items.reduce((m, it) => Math.max(m, it.id || 0), lastId);
                lastId = Math.max(data.last_id || 0, maxId);
                scrollBottom();
            }
        } catch (e) { /* silent */ }
        finally { isPolling = false; }
    }

    // submit qua AJAX để không reload trang
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
                await poll();        // pull tin nhắn vừa gửi của chính mình
            }
        } catch (e) { /* silent */ }
        finally {
            isSending = false;
            if (submitBtn) submitBtn.disabled = false;
            input.focus();
        }
    });

    // Enter để gửi, Shift+Enter xuống dòng
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && ! e.shiftKey) {
            e.preventDefault();
            if (! isSending) form.requestSubmit();
        }
    });
    // Auto-resize textarea
    input.addEventListener('input', () => {
        input.style.height = 'auto';
        input.style.height = Math.min(input.scrollHeight, 120) + 'px';
    });

    scrollBottom();
    setInterval(poll, 4000);  // poll mỗi 4s
})();
</script>
@endsection
