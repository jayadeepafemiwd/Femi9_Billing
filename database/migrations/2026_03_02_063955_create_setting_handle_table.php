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
        Schema::create('setting_handle', function (Blueprint $table) {
            $table->id();
            $table->string('process',200);
             $table->string('category_name', 100)
                  ->comment('Module name: products, sales, purchases, etc.');
            $table->json('config')->nullable()->comment('JSON field for configuration data');  
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_handle');
    }
};
