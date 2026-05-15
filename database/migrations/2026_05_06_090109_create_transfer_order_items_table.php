<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('transfer_order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('transfer_order_id')->constrained()->cascadeOnDelete();
        $table->foreignId('product_id')->constrained();
        $table->unsignedBigInteger('variant_id')->nullable();
        $table->decimal('quantity', 15, 4)->default(0);
        $table->decimal('source_stock', 15, 4)->default(0);
        $table->decimal('destination_stock', 15, 4)->default(0);
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('transfer_order_items');
}
};
