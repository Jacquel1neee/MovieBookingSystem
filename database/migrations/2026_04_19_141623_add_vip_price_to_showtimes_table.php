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
        Schema::table('showtimes', function (Blueprint $table) {
            $table->decimal('vip_price', 8, 2)->after('price')->nullable();
        });

        // Set default VIP price for existing showtimes (regular price + 10)
        DB::statement('UPDATE showtimes SET vip_price = price + 10 WHERE vip_price IS NULL');

        // Now that data is seeded, we can make it non-nullable if we want
        Schema::table('showtimes', function (Blueprint $table) {
            $table->decimal('vip_price', 8, 2)->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('showtimes', function (Blueprint $table) {
            $table->dropColumn('vip_price');
        });
    }
};
