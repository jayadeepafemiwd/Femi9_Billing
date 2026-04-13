<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lc_layer_values', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('layer_id');         // belongs to which lc_layer
            $table->unsignedBigInteger('parent_value_id')->nullable(); // parent value (e.g. India's id for states)
            $table->string('value');                         // e.g. "India", "TamilNadu", "Erode"
            $table->timestamps();

            $table->index('layer_id');
            $table->index('parent_value_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lc_layer_values');
    }
};