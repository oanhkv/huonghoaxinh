<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactReply extends Model
{
    protected $fillable = [
        'contact_message_id',
        'admin_id',
        'user_id',
        'subject',
        'body',
        'to_email',
        'sent_at',
        'send_error',
        'customer_read_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'customer_read_at' => 'datetime',
    ];

    public function message()
    {
        return $this->belongsTo(ContactMessage::class, 'contact_message_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function isFromCustomer(): bool
    {
        return ! is_null($this->user_id);
    }

    public function senderName(): string
    {
        if ($this->isFromCustomer()) {
            return $this->user?->name ?? 'Khách hàng';
        }
        return $this->admin?->name ?? 'Hương Hoa Xinh';
    }
}

