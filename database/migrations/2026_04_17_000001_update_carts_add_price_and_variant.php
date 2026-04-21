<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Drop foreign keys first to prevent error 1553 in MySQL when dropping the unique index
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);

            // Remove the old unique constraint across product_id and user_id
            $table->dropUnique(['user_id', 'product_id']);

            // Re-add foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Add price and variant fields
            $table->unsignedBigInteger('price')->default(0)->after('quantity');
            $table->string('variant')->default('')->after('price');

            // Ensure the same product with the same price and variant merges into one line
            $table->unique(['user_id', 'product_id', 'price', 'variant']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'product_id', 'price', 'variant']);
            $table->dropColumn(['price', 'variant']);
            $table->unique(['user_id', 'product_id']);
        });
    }
};
