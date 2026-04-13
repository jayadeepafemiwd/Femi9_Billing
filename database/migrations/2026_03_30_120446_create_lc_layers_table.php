<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lc_layers', function (Blueprint $table) {
            $table->id();
            $table->string('name');           // country, state, district
            $table->boolean('is_global')->default(false);
            $table->integer('depth'); 
            $table->unsignedBigInteger('parent_value_id')->nullable(); // nullable வேணும்!
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lc_layers');
    }
};