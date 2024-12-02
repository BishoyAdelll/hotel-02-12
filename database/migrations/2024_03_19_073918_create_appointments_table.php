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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignId('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->string('the_numbers_of_days')->nullable();
            $table->string('capacity_id')->nullable();
            $table->string('structure_id')->nullable();
            $table->decimal('hall_price',8,2)->nullable();
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
            $table->string('receipt_number')->nullable();
            $table->string('receipt_images')->nullable();
            $table->tinyInteger('is_edited')->default(0);
            $table->decimal('insurance',10,3);
            $table->string('discount');
            $table->string('tax');
            $table->string('grand_total');
            $table->string('paid');
            $table->string('person_price')->nullable();
            $table->string('capacity')->nullable();
            $table->decimal('total_person_price',8,2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
