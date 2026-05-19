<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    /**
     * Mỗi khách hàng có duy nhất 1 hộp thoại chat với cửa hàng.
     * Vào đây sẽ get-or-create rồi điều hướng tới chat.
     */
    public function index()
    {
        $conv = $this->getOrCreateConversation();

        return redirect()->route('account.contact.show', $conv);
    }

    /**
     * Hiển thị màn hình chat (full thread).
     */
    public function show(ContactMessage $contactMessage)
    {
        $this->authorizeOwnership($contactMessage);

        // Đánh dấu mọi reply của admin trong thread này là khách đã đọc
        $contactMessage->replies()
            ->whereNull('user_id')          // do admin gửi
            ->whereNull('customer_read_at')
            ->update(['customer_read_at' => now()]);

        $contactMessage->load(['replies' => fn ($q) => $q->orderBy('created_at')]);

        return view('frontend.account.contact.show', compact('contactMessage'));
    }

    /**
     * JSON: số reply của admin mà khách chưa đọc — dùng cho badge floating chat.
     */
    public function unreadCount(): \Illuminate\Http\JsonResponse
    {
        $userId = Auth::id();
        $count = ContactReply::query()
            ->whereNull('user_id')          // do admin gửi
            ->whereNull('customer_read_at')
            ->whereHas('message', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Khách gửi 1 tin nhắn mới vào hộp thoại. Chỉ lưu DB, KHÔNG gửi email.
     */
    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $this->authorizeOwnership($contactMessage);

        $request->validate([
            'body' => 'required|string|max:3000',
        ]);

        ContactReply::create([
            'contact_message_id' => $contactMessage->id,
            'user_id' => Auth::id(),
            'admin_id' => null,
            'subject' => null,
            'body' => $request->body,
            'to_email' => '',
            'sent_at' => now(),
        ]);

        $contactMessage->status = 'awaiting_reply';
        $contactMessage->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('account.contact.show', $contactMessage);
    }

    /**
     * JSON endpoint cho client poll tin nhắn mới.
     * GET /account/contact/{contactMessage}/poll?since=<reply_id>
     */
    public function poll(Request $request, ContactMessage $contactMessage): JsonResponse
    {
        $this->authorizeOwnership($contactMessage);

        $since = (int) $request->get('since', 0);
        $messages = $contactMessage->replies()
            ->where('id', '>', $since)
            ->orderBy('id')
            ->get();

        $items = $messages->map(fn ($r) => [
            'id' => $r->id,
            'body' => $r->body,
            'is_customer' => $r->isFromCustomer(),
            'sender_name' => $r->senderName(),
            'created_at' => optional($r->created_at)?->format('H:i d/m/Y'),
        ]);

        return response()->json([
            'items' => $items,
            'last_id' => $messages->max('id') ?: $since,
        ]);
    }

    private function getOrCreateConversation(): ContactMessage
    {
        $user = Auth::user();

        $conv = ContactMessage::where('user_id', $user->id)->latest()->first();

        if (! $conv) {
            // Cố gắng dùng lại thread cũ (chưa có user_id, match theo email) để
            // không làm mất lịch sử cũ
            $legacy = ContactMessage::whereNull('user_id')
                ->where('email', $user->email)
                ->latest()
                ->first();
            if ($legacy) {
                $legacy->user_id = $user->id;
                $legacy->save();
                return $legacy;
            }

            $conv = ContactMessage::create([
                'user_id' => $user->id,
                'name'    => $user->name,
                'email'   => $user->email,
                'phone'   => $user->phone ?? null,
                'subject' => 'Trao đổi với cửa hàng',
                'message' => '',
                'status'  => 'new',
            ]);
        }

        return $conv;
    }

    private function authorizeOwnership(ContactMessage $msg): void
    {
        if ($msg->user_id && $msg->user_id === Auth::id()) {
            return;
        }
        if (strtolower((string) $msg->email) === strtolower((string) Auth::user()->email)) {
            return;
        }
        abort(403);
    }
}
