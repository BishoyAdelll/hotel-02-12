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
        Schema::create('appointment_additions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->nullable()->references('id')->on('appointments')->onDelete('cascade');
            $table->foreignId('addition_id')->nullable()->references('id')->on('additions')->onDelete('cascade');
            $table->dateTime('booked_at')->nullable();
            $table->decimal('price',8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_additions');
    }
};
