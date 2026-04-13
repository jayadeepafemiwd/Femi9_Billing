<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * user_category_layers
 * ---------------------
 * Tracks which lc_layers.id is assigned to which user_category.
 * One layer can only be assigned to ONE category (unique on layer_id).
 * When a category is deleted, its assignments are removed (cascade).
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1. Main user_categories table (with assign_fix_location column)
        Schema::create('user_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('user_categories')->nullOnDelete();
            $table->unsignedTinyInteger('level')->default(1);
            $table->boolean('portal_access')->default(true);
            $table->boolean('visible_in_hierarchy')->default(true);

            // country_id = lc_layer_values.id of the selected country value
            $table->unsignedBigInteger('country_id')->nullable();

            // assign_fix_location = the lc_layers.id assigned to this category
            // UNIQUE: one layer can belong to only one category across the system
            $table->unsignedBigInteger('assign_fix_location')->nullable();

            $table->string('location_label')->nullable(); // e.g. "India → state"
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['parent_id', 'portal_access']);
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_categories');
    }
};