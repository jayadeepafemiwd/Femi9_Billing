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
       Schema::create('inventory_adjustment_items', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('inventory_adjustment_id');
    $table->unsignedBigInteger('item_id');
    $table->decimal('quantity_available', 15, 4)->default(0);
    $table->decimal('new_quantity_on_hand', 15, 4)->nullable();
    $table->decimal('quantity_adjusted', 15, 4)->nullable();
    $table->decimal('current_value', 15, 2)->nullable();
    $table->decimal('changed_value', 15, 2)->nullable();
    $table->decimal('adjusted_value', 15, 2)->nullable();
    $table->json('reporting_tags')->nullable();
    $table->timestamps();
    $table->foreign('inventory_adjustment_id')
      ->references('id')
      ->on('inventory_adjustments')
      ->onDelete('cascade');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustment_items');
    }
};
