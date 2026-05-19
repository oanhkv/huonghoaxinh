<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            // Một user có 1 hộp thoại chat duy nhất với shop.
            // Bản ghi cũ (chưa migrate sang chat) thì user_id = null.
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
