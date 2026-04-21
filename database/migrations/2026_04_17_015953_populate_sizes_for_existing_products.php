<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add default sizes for all products that don't have sizes
        $defaultSizes = json_encode([
            ['size' => '40cm', 'price' => 0],
            ['size' => '50cm', 'price' => 50000],
            ['size' => '60cm', 'price' => 100000],
        ]);

        DB::table('products')
            ->whereNull('sizes')
            ->orWhere('sizes', '')
            ->update(['sizes' => $defaultSizes]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('products')->update(['sizes' => null]);
    }
};
