@php
    /** @var \App\Models\ContactMessage $m */
@endphp
<div style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2 style="margin: 0 0 12px;">Bạn có liên hệ mới từ website</h2>

    <p style="margin: 0 0 10px;">
        <strong>Khách hàng:</strong> {{ $m->name }}<br>
        <strong>Email:</strong> {{ $m->email }}<br>
        <strong>Điện thoại:</strong> {{ $m->phone ?: '—' }}<br>
        <strong>Tiêu đề:</strong> {{ $m->subject ?: '—' }}<br>
        <strong>Thời gian:</strong> {{ $m->created_at?->format('d/m/Y H:i') ?? '—' }}
    </p>

    <div style="padding: 12px 14px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fafafa;">
        <div style="font-weight: 700; margin-bottom: 6px;">Nội dung</div>
        <div style="white-space: pre-wrap;">{{ $m->message }}</div>
    </div>

    <p style="margin: 12px 0 0; color: #6b7280; font-size: 13px;">
        Bạn có thể vào trang Admin để xem và phản hồi trực tiếp.
    </p>
</div>

