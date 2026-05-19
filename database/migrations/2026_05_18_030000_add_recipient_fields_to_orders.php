<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Người gửi (tự nhập, có thể khác user profile)
            $table->string('sender_name', 100)->nullable()->after('user_id');
            $table->string('sender_phone', 30)->nullable()->after('sender_name');

            // Người nhận hoa
            $table->string('recipient_name', 100)->nullable()->after('shipping_address');
            // recipient_phone tận dụng cột 'phone' hiện có
            // recipient_address tận dụng cột 'shipping_address'

            // Thời gian giao
            $table->date('delivery_date')->nullable()->after('recipient_name');
            $table->string('delivery_time_slot', 30)->nullable()->after('delivery_date');

            // Lời nhắn gửi cho người nhận (in trên thiệp hoa, …)
            $table->text('recipient_message')->nullable()->after('delivery_time_slot');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'sender_name',
                'sender_phone',
                'recipient_name',
                'delivery_date',
                'delivery_time_slot',
                'recipient_message',
            ]);
        });
    }
};
