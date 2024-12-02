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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->enum('status',[
                \App\Enums\Status::Booked->value,
                \App\Enums\Status::Confirmed->value,
                \App\Enums\Status::Cancelled->value,
            ]);
            $table->enum('payment',[
                \App\Enums\Payment::cash->value,
                \App\Enums\Payment::visa->value,
                \App\Enums\Payment::instapay->value,
            ]);
            $table->tinyInteger('is_edited')->default(0);
            $table->decimal('insurance',10,3);
            $table->string('discount');
            $table->string('tax');
            $table->string('grand_total');
            $table->string('deposit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
