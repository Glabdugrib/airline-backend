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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('departure_airport_id');
            $table->unsignedBigInteger('arrival_airport_id');
            $table->timestamp('departure_at');
            $table->timestamp('arrival_at');
            $table->float('price', 8, 2);
            $table->smallInteger('stopovers');
            $table->timestamps();
            $table->softDeletes();

            // Create foreign keys
            $table->foreign('departure_airport_id')->references('id')->on('airports')->cascadeOnDelete();
            $table->foreign('arrival_airport_id')->references('id')->on('airports')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
