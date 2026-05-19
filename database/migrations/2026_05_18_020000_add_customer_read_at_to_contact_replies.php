<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Đánh dấu khách đã xem reply của admin
            $table->timestamp('customer_read_at')->nullable()->after('sent_at');
            $table->index('customer_read_at');
        });
    }

    public function down(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->dropIndex(['customer_read_at']);
            $table->dropColumn('customer_read_at');
        });
    }
};
