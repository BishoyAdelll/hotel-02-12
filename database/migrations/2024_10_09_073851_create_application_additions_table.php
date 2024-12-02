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
        Schema::create('application_additions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreignId('addition_id')->references('id')->on('additions')->onDelete('cascade');
            $table->decimal('price',8, 2);
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
        Schema::dropIfExists('application_additions');
    }
};
