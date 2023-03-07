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
        Schema::create('hotel_reservations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hotel_id');
            $table->bigInteger('user_id');
            $table->date('reserved_at');
            $table->enum('status', ['NORMAL', 'PROPOSE', 'REFUSE', 'RESERVED'])->default('NORMAL');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['hotel_id', 'reserved_at', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_reservations');
    }
};
