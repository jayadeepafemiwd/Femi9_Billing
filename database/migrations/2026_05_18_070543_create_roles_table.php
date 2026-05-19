<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create roles table
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->foreignId('user_category_id')
                  ->constrained('user_categories')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Modify user_category_permissions
        Schema::table('user_category_permissions', function (Blueprint $table) {

            // ✅ Drop the FK first — MySQL needs this before dropping the index it relies on
            $table->dropForeign(['user_category_id']);

            // ✅ Now safe to drop the unique index
            $table->dropUnique(['user_category_id', 'module']);

            // ✅ Re-add the FK (we still want this relationship)
            $table->foreign('user_category_id')
                  ->references('id')
                  ->on('user_categories')
                  ->onDelete('cascade');

            // Add role_id column
            $table->foreignId('role_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('roles')
                  ->onDelete('cascade');

            // New unique: role + module combination
            $table->unique(['role_id', 'module']);
        });
    }

    public function down(): void
    {
        Schema::table('user_category_permissions', function (Blueprint $table) {
            $table->dropUnique(['role_id', 'module']);
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');

            // Restore original unique index
            $table->dropForeign(['user_category_id']);
            $table->unique(['user_category_id', 'module']);
            $table->foreign('user_category_id')
                  ->references('id')
                  ->on('user_categories')
                  ->onDelete('cascade');
        });

        Schema::dropIfExists('roles');
    }
};