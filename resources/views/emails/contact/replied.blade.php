@php
    /** @var \App\Models\ContactMessage $m */
    /** @var \App\Models\ContactReply $r */
@endphp
<div style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2 style="margin: 0 0 12px;">Phản hồi từ cửa hàng</h2>

    <p style="margin: 0 0 10px;">
        Xin chào <strong>{{ $m->name }}</strong>,<br>
        Cảm ơn bạn đã liên hệ. Dưới đây là phản hồi của chúng tôi:
    </p>

    <div style="padding: 12px 14px; border: 1px solid #d1fae5; border-radius: 10px; background: #ecfdf5;">
        <div style="font-weight: 700; margin-bottom: 6px;">Nội dung phản hồi</div>
        <div style="white-space: pre-wrap;">{{ $r->body }}</div>
    </div>

    <div style="margin-top: 14px; padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fafafa;">
        <div style="font-weight: 700; margin-bottom: 6px;">Tin nhắn gốc của bạn</div>
        <div style="color:#6b7280; font-size: 13px; margin-bottom: 6px;">
            {{ $m->created_at?->format('d/m/Y H:i') ?? '—' }} — {{ $m->subject ?: 'Liên hệ' }}
        </div>
        <div style="white-space: pre-wrap;">{{ $m->message }}</div>
    </div>

    <p style="margin: 12px 0 0; color: #6b7280; font-size: 13px;">
        Nếu cần hỗ trợ thêm, bạn có thể trả lời trực tiếp email này.
    </p>
</div>

