<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'total_amount',
        'status',
        'stock_deducted',
        'shipping_address',
        'phone',
        'note',
        'viewed_at',
        // Người gửi & người nhận
        'sender_name',
        'sender_phone',
        'recipient_name',
        'delivery_date',
        'delivery_time_slot',
        'recipient_message',
    ];

    protected $casts = [
        'stock_deducted' => 'boolean',
        'viewed_at' => 'datetime',
        'delivery_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
