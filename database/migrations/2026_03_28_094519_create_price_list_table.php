<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Price Lists table
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
           $table->enum('transaction_type', ['sales', 'purchase', 'both'])->default('sales');
            $table->string('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->boolean('access_permission')->default(false);
            $table->enum('price_list_type', ['all_items', 'individual_items'])->default('all_items');
            $table->text('description')->nullable();

            // All Items fields
            $table->enum('markup_type', ['markup', 'markdown'])->nullable();
            $table->decimal('percentage', 8, 2)->nullable();
            $table->string('round_off')->nullable();

            // Individual Items fields
            $table->enum('pricing_scheme', ['unit', 'volume'])->nullable();
            $table->string('currency')->default('INR');
            $table->boolean('include_discount')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('price_lists');
    }
};