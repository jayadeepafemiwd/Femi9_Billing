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
    Schema::create('transfer_orders', function (Blueprint $table) {
        $table->id();
        $table->string('transfer_order_number')->unique();
        $table->date('date')->nullable();
        $table->text('reason')->nullable();
        $table->foreignId('source_location_id')->constrained('locations');
        $table->foreignId('destination_location_id')->constrained('locations');
        $table->enum('status', ['draft', 'initiated', 'transferred', 'completed', 'cancelled'])->default('draft');
        $table->softDeletes();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('transfer_orders');
}
};
