<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->onDelete('cascade');

            $table->foreignId('location_id')
                  ->constrained('locations')   // ✅ plural — locations
                  ->onDelete('cascade');

            $table->decimal('opening_stock',   15, 4)->default(0);
            $table->decimal('stock_on_hand',   15, 4)->default(0);
            $table->decimal('committed_stock', 15, 4)->default(0);
            $table->decimal('available_stock', 15, 4)->default(0);
            $table->decimal('value_per_unit',  15, 4)->default(0);
            $table->decimal('total_value',     15, 4)->default(0);
            $table->string('source_type')->nullable(); // 'opening', 'assembly', 'sale'
            $table->unsignedBigInteger('source_id')->nullable(); // assembly id etc.
            $table->string('type')->default('opening');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_id', 'location_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};