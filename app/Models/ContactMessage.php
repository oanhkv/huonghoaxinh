<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'phone',
        'read_at',
        'replied_at',
        'status',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    public function replies()
    {
        return $this->hasMany(ContactReply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
