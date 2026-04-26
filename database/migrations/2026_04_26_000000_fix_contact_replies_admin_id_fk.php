<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->dropForeign('contact_replies_admin_id_foreign');
            $table->foreign('admin_id')
                ->references('id')->on('admins')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contact_replies', function (Blueprint $table) {
            $table->dropForeign('contact_replies_admin_id_foreign');
            $table->foreign('admin_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();
        });
    }
};
