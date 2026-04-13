<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_series', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('location_id')->nullable();
            $table->json('series_data');
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_name')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // ⭐ No foreign key constraint
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_series');
    }
};