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
       Schema::table('customers', function (Blueprint $table) {
    $table->unsignedBigInteger('customer_sub_category_id')->nullable()->after('customer_category');
    $table->foreign('customer_sub_category_id')
          ->references('id')->on('user_sub_categories')
          ->onDelete('set null');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
