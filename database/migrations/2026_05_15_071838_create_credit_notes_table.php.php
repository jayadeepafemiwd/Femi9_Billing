<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Credit Notes table
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();

            // CN-00005 auto generated
            $table->string('credit_note_number', 50)->unique();

            // FK → your existing customers table (display_name based)
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('restrict');

            $table->unsignedBigInteger('salesperson_id')->nullable();
            $table->foreign('salesperson_id')->references('id')->on('salespersons')->onDelete('set null');

            $table->string('location', 100)->default('Head Office');
            $table->string('warehouse_location', 100)->default('Head Office');
            $table->string('reference_number', 100)->nullable();
            $table->date('credit_note_date');
            $table->string('subject', 255)->nullable();
            $table->string('price_list', 100)->nullable();

            // Financials
            $table->decimal('sub_total', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->string('tax_type', 10)->default('TDS');   // TDS | TCS
            $table->string('tax_id', 100)->nullable();
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('adjustment', 15, 2)->default(0);
            $table->decimal('total', 15, 2)->default(0);
            $table->decimal('applied_amount', 15, 2)->default(0);
    $table->decimal('unused_amount',  15, 2)->default(0);
            // Notes
            $table->text('customer_notes')->nullable();
            $table->text('terms_and_conditions')->nullable();

            // Status
            $table->enum('status', ['draft', 'open', 'void', 'closed'])->default('draft');

            $table->string('pdf_template', 100)->default('Spreadsheet Template');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('credit_note_date');
            $table->index('customer_id');
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreign('invoice_id')->references('id')->on('invoices')->nullOnDelete();
        });

        // Credit Note Line Items
        Schema::create('credit_note_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('credit_note_id');
            $table->foreign('credit_note_id')->references('id')->on('credit_notes')->onDelete('cascade');

            // FK → your existing products table
            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');

            // Snapshot at time of CN creation (product name/sku can change later)
            $table->string('item_name');
            $table->string('item_sku', 100)->nullable();
            $table->string('item_type', 50)->nullable();   // goods | service | etc.
            $table->string('unit', 50)->nullable();

            $table->string('account', 100)->nullable();
            $table->decimal('quantity', 15, 4)->default(1);
            $table->decimal('rate', 15, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credit_note_items');
        Schema::dropIfExists('credit_notes');
    }
};