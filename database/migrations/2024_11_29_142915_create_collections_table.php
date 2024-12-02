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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attachment_id')->nullable()->references('id')->on('attachments')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->references('id')->on('appointments')->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->references('id')->on('applications')->onDelete('cascade');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->date('booked_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
