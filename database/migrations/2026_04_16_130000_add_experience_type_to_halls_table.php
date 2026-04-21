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
        if (!Schema::hasColumn('halls', 'experience_type')) {
            Schema::table('halls', function (Blueprint $table) {
                $table->string('experience_type')->default('Standard')->after('total_seats');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('halls', 'experience_type')) {
            Schema::table('halls', function (Blueprint $table) {
                $table->dropColumn('experience_type');
            });
        }
    }
};
