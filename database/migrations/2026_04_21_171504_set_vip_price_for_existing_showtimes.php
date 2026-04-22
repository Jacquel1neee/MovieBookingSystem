<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::update('UPDATE showtimes SET vip_price = price + 10 WHERE vip_price IS NULL OR vip_price = 0');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, set back to null or something, but since it's data, perhaps leave it.
    }
};
