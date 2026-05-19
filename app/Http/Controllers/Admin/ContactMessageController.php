<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\ContactReply;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()
            ->withCount('replies')
            ->with(['user', 'replies' => function ($q) {
                $q->latest()->limit(1);
            }])
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('subject', 'like', "%{$q}%")
                    ->orWhere('message', 'like', "%{$q}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(15)->withQueryString();

        $statusCounts = [
            'all' => ContactMessage::count(),
            'new' => ContactMessage::where('status', 'new')->count(),
            'awaiting_reply' => ContactMessage::where('status', 'awaiting_reply')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
        ];

        return view('admin.contact_messages.index', compact('messages', 'statusCounts'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if (! $contactMessage->read_at) {
            $contactMessage->read_at = now();
            $contactMessage->status = $contactMessage->status === 'new' ? 'read' : $contactMessage->status;
            $contactMessage->save();
        }

        // Nếu thread đang awaiting_reply → admin xem coi như đã thấy
        $contactMessage->load([
            'user',
            'replies' => fn ($q) => $q->orderBy('created_at'),
            'replies.admin',
            'replies.user',
        ]);

        return view('admin.contact_messages.show', compact('contactMessage'));
    }

    /**
     * Admin gửi tin nhắn trong hộp thoại. Lưu DB, KHÔNG gửi email nữa.
     */
    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'body' => 'required|string|max:3000',
        ]);

        ContactReply::create([
            'contact_message_id' => $contactMessage->id,
            'admin_id' => Auth::guard('admin')->id(),
            'user_id' => null,
            'subject' => null,
            'body' => $request->body,
            'to_email' => $contactMessage->email,
            'sent_at' => now(),
        ]);

        $contactMessage->status = 'replied';
        $contactMessage->replied_at = now();
        if (! $contactMessage->read_at) {
            $contactMessage->read_at = now();
        }
        $contactMessage->save();

        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.contact-messages.show', $contactMessage);
    }

    /**
     * JSON poll cho admin chat — trả về reply có id > since.
     */
    public function poll(Request $request, ContactMessage $contactMessage): JsonResponse
    {
        $since = (int) $request->get('since', 0);
        $messages = $contactMessage->replies()
            ->with(['admin', 'user'])
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

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Đã xóa hộp thoại.');
    }
}
