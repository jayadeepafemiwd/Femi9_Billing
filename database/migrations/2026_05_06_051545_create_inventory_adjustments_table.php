<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('inventory_adjustments', function (Blueprint $table) {
    $table->id();
    $table->string('reference_number')->nullable();
    $table->enum('mode', ['quantity', 'value'])->default('quantity');
    $table->date('date');
    $table->string('account');
    $table->string('reason');
    $table->unsignedBigInteger('location_id');
    $table->text('description')->nullable();
    $table->string('product_rules')->nullable();
    $table->enum('status', ['draft', 'adjusted'])->default('draft');
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_adjustments');
    }
};