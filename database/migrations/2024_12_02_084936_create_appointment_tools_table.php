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
        Schema::create('appointment_tools', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->foreignId('tool_id')->references('id')->on('tools')->onDelete('cascade');
            $table->decimal('price_male',8,2);
            $table->dateTime('date_time')->nullable();
            $table->string('quantity');
            $table->decimal('total',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_tools');
    }
};
