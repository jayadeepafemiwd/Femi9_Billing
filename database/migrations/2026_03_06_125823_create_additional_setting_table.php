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
        Schema::create('additional_setting', function (Blueprint $table) {
            $table->id();
            $table->string('name');

            // ── FIX: category_name column added ──────────────────────
            // 'products', 'sales', 'purchases', etc.
            $table->string('category_name', 100)
                  ->comment('Module name: products, sales, purchases, etc.');

            $table->enum('data_type', [
                // Text Types
                'string',
                'text',
                'longtext',
                'char',

                // Number Types
                'integer',
                'biginteger',
                'smallinteger',
                'tinyinteger',
                'decimal',
                'float',
                'double',

                // Boolean
                'boolean',

                // Date & Time
                'date',
                'datetime',
                'timestamp',
                'time',
                'year',

                // Internet / Contact
                'email',
                'phone',
                'url',
                'ip_address',
                'mac_address',

                // File & Media
                'file',
                'image',
                'video',
                'audio',

                // Special
                'json',
                'array',
                'uuid',
                'password',
                'color',
                'currency',
                'percentage',
                'coordinates',
            ]);

            $table->enum('mandatory', ['yes', 'no'])->default('no');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->json('additional_config')->nullable()->comment('JSON field for configuration data');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_setting');
    }
};