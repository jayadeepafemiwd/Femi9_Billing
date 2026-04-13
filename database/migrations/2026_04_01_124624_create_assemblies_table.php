<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   // database/migrations/xxxx_create_assemblies_table.php
public function up(): void
{
    Schema::create('assemblies', function (Blueprint $table) {
        $table->id();
        $table->string('assembly_number')->nullable();
        $table->foreignId('composite_item_id')->constrained('products')->onDelete('cascade');
        $table->string('composite_item_name')->nullable();;
        $table->string('composite_item_sku')->nullable();
        $table->text('description')->nullable();
        $table->date('assembled_date');
        $table->decimal('quantity_to_assemble', 15, 4)->default(1);
        $table->unsignedBigInteger('location_id')->nullable();
        $table->json('associated_items')->nullable();   // goods items
        $table->json('associated_services')->nullable(); // service items
        $table->enum('status', ['draft', 'assembled'])->default('draft');
        $table->unsignedBigInteger('created_by')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assemblies');
    }
};
