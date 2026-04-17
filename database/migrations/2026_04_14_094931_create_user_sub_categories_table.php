<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_category_id')
                  ->constrained('user_categories')
                  ->onDelete('cascade');
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->decimal('target_amount', 12, 2)->nullable();
            $table->string('reference', 100)->nullable();
            $table->string('coupon', 50)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();

            $table->index('user_category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_sub_categories');
    }
};