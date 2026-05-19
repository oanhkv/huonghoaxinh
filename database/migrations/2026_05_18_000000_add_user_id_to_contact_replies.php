<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            // Cho phép khách hàng cũng có thể reply (admin_id=null khi đó)
            $table->foreignId('user_id')
                ->nullable()
                ->after('admin_id')
                ->constrained('users')
                ->nullOnDelete();

            // admin_id phải nullable để chứa được reply của khách
            $table->foreignId('admin_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->foreignId('admin_id')->nullable(false)->change();
        });
    }
};
