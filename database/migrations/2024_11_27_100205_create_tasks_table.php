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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class)->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->foreignId('appointment_id')->nullable()->references('id')->on('appointments')->onDelete('cascade');
            $table->foreignId('application_id')->nullable()->references('id')->on('applications')->onDelete('cascade');
            $table->enum('status',[
                \App\Enums\Status::Confirmed->value,
                \App\Enums\Status::Booked->value,
                \App\Enums\Status::Cancelled->value,
            ])->default(\App\Enums\Status::Booked->value);
            $table->longText('hall')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
