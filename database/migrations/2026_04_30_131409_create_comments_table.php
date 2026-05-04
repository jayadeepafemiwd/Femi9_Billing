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
      Schema::create('comments', function (Blueprint $table) {
    $table->id();
    $table->string('module');
    $table->unsignedBigInteger('record_id');
    $table->text('content');
    $table->unsignedBigInteger('user_id')->nullable();
    $table->string('user_name')->nullable();
    $table->softDeletes();
    $table->timestamps();

    $table->index(['module', 'record_id']);
    
});

Schema::create('comment_settings', function (Blueprint $table) {
    $table->id();
    $table->string('module')->unique();
    $table->json('configuration')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
