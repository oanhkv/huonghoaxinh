<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessageReplied;
use App\Models\ContactMessage;
use App\Models\ContactReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

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

        $messages = $query->paginate(12)->withQueryString();

        return view('admin.contact_messages.index', compact('messages'));
    }

    public function show(ContactMessage $contactMessage)
    {
        if (! $contactMessage->read_at) {
            $contactMessage->read_at = now();
            $contactMessage->status = $contactMessage->status === 'new' ? 'read' : $contactMessage->status;
            $contactMessage->save();
        }

        $contactMessage->load(['replies.admin']);

        return view('admin.contact_messages.show', compact('contactMessage'));
    }

    public function reply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string|max:3000',
        ]);

        $reply = ContactReply::create([
            'contact_message_id' => $contactMessage->id,
            'admin_id' => Auth::guard('admin')->id(),
            'subject' => $request->subject,
            'body' => $request->body,
            'to_email' => $contactMessage->email,
        ]);

        try {
            Mail::to($contactMessage->email)
                ->replyTo(config('shop.contact_inbox_email'), config('app.name'))
                ->send(new ContactMessageReplied($contactMessage, $reply));

            $reply->sent_at = now();
            $reply->save();

            $contactMessage->status = 'replied';
            $contactMessage->replied_at = now();
            if (! $contactMessage->read_at) {
                $contactMessage->read_at = now();
            }
            $contactMessage->save();

            return redirect()
                ->route('admin.contact-messages.show', $contactMessage)
                ->with('success', 'Đã gửi phản hồi tới email khách hàng.');
        } catch (\Throwable $e) {
            $reply->send_error = $e->getMessage();
            $reply->save();

            return redirect()
                ->route('admin.contact-messages.show', $contactMessage)
                ->with('error', 'Gửi email thất bại. Vui lòng kiểm tra cấu hình email (SMTP) trong .env.');
        }
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')->with('success', 'Đã xóa tin nhắn.');
    }
}

