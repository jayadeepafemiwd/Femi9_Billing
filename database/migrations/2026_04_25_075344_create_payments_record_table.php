<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments_record', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers');
             $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('payment_no', 50)->nullable();
            $table->date('payment_date');
            $table->decimal('amount_received', 15, 2);
            $table->decimal('bank_charges', 15, 2)->default(0);
            $table->string('payment_mode', 50)->nullable();
            $table->string('deposit_to', 100)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'paid','refunded'])->default('paid');
            
            $table->boolean('is_advance_payment')->default(false);
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('payments_record');
    }
};