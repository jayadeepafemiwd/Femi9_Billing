<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_category_permissions', function (Blueprint $table) {
            $table->id();

            // Which user category (Super Stockist / Stockist / Distributor ...)
            $table->foreignId('user_category_id')
                  ->constrained('user_categories')
                  ->onDelete('cascade');

            // Module name — e.g. 'invoices', 'products', 'customers'
            $table->string('module', 80);

            // CRUD permissions
            $table->boolean('can_create')->default(false);
            $table->boolean('can_read')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);

            // Scope: 'all' = every record, 'own' = only their own records
            $table->enum('scope', ['all', 'own', 'none'])->default('none');

            $table->timestamps();

            // One row per category+module — no duplicates
            $table->unique(['user_category_id', 'module']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_category_permissions');
    }
};