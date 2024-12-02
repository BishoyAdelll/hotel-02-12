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
        Schema::create('application_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->references('id')->on('applications')->onDelete('cascade');
            $table->foreignId('component_id')->references('id')->on('components')->onDelete('cascade');
            $table->enum('type',[
                \App\Enums\Type::Lunch->value,
                \App\Enums\Type::Breakfast_and_lunch->value
            ]);
            $table->string('quantity');
            $table->decimal('price',8,2);
            $table->decimal('total',8,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_components');
    }
};
