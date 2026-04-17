<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            // Basic Info
            $table->enum('customer_type', ['business', 'individual'])->default('business');
             $table->string('customer_category', 100)->nullable();
             $table->string('user_code', 20)->nullable();
             $table->json('assign_location')->nullable();
            $table->string('name', 100)->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('display_name', 255);
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 20)->nullable();

            // Other Details
            $table->string('pan', 10)->nullable();
            $table->json('additional_datas')->nullable()->comment('Additional customer informations');
            $table->json('common_address')->nullable()->comment('billing and shipping address');
            $table->json('comments')->nullable()->comment('note of the customer');


             // Remarks
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('customer_type');
            $table->index('display_name');
            $table->index('email');
            $table->index('company_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};