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
        Schema::table('hotel_reservations', function (Blueprint $table) {
            //
            $table->dropIndex(['hotel_id', 'reserved_at', 'status']);
            $table->dropColumn('reserved_at');
            $table->index(['hotel_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotel_reservations', function (Blueprint $table) {
            //
        });
    }
};
