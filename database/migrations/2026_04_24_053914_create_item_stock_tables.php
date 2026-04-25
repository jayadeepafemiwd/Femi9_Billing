<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── item_stock ──
        Schema::create('item_stock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->foreign('variant_id')->references('id')->on('item_variants')->nullOnDelete();

            $table->decimal('opening_stock',     15, 4)->default(0);
            $table->decimal('stock_on_hand',     15, 4)->default(0);
            $table->decimal('committed_stock',   15, 4)->default(0);
            $table->decimal('available_for_sale',15, 4)->default(0);
            $table->decimal('value_per_unit',    15, 4)->default(0);
            $table->decimal('total_value',       15, 4)->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['item_id', 'location_id', 'variant_id']);
        });

        // ── item_stock_ledger ──
        Schema::create('item_stock_ledger', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->foreign('variant_id')->references('id')->on('item_variants')->nullOnDelete();

            $table->enum('transaction_type', [
                'opening','purchase','sale','sale_return','purchase_return',
                'transfer_in','transfer_out','adjustment','commit','uncommit'
            ]);
            $table->date('transaction_date');
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->decimal('qty_change',          15, 4)->default(0);
            $table->decimal('committed_change',    15, 4)->default(0);
            $table->decimal('unit_value',          15, 4)->nullable();
            $table->decimal('stock_on_hand_after', 15, 4)->default(0);
            $table->decimal('committed_after',     15, 4)->default(0);
            $table->decimal('available_after',     15, 4)->default(0);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['item_id', 'location_id', 'variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('item_stock_ledger');
        Schema::dropIfExists('item_stock');
    }
};