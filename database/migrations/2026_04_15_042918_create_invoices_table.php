<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('invoices', function (Blueprint $table) {
        $table->id();
        $table->string('invoice_number')->unique();
        $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
        $table->unsignedBigInteger('referral_id')->nullable();
        $table->string('location')->nullable();
        $table->string('order_number')->nullable();
        $table->date('invoice_date');
        $table->unsignedBigInteger('invoice_number_int')->default(0)->index();
        $table->string('terms')->default('Due on Receipt');
        $table->date('due_date');
        $table->string('salesperson')->nullable();
        $table->text('subject')->nullable();
        $table->string('warehouse_location')->nullable();
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('discount_percent', 5, 2)->default(0);
        $table->decimal('discount_amount', 10, 2)->default(0);
        $table->string('tax_type')->default('TDS');
        $table->decimal('tax_percent', 5, 2)->default(0);
        $table->decimal('tax_amount', 10, 2)->default(0);
        $table->decimal('adjustment', 10, 2)->default(0);
        $table->decimal('courier_charges', 10, 2)->default(0);
        $table->json('extra_charges')->nullable();
        $table->decimal('grand_total', 10, 2)->default(0);
        $table->text('customer_notes')->nullable();
        $table->text('terms_conditions')->nullable();
         $table->json('additional_data')->nullable();
        $table->string('status')->default('Draft');
        $table->enum('payment_status', ['unpaid', 'partial', 'paid'])->default('unpaid');
        $table->decimal('amount_received', 12, 2)->default(0);
        $table->decimal('balance_due', 12, 2)->default(0);
        $table->string('payment_mode')->nullable();
        $table->string('deposit_to')->nullable();
        $table->timestamps();
    });
}


    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
