<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->json('selected_seat_ids')->nullable()->after('reason');
        });
    }

    public function down()
    {
        Schema::table('exchange_requests', function (Blueprint $table) {
            $table->dropColumn('selected_seat_ids');
        });
    }
};
