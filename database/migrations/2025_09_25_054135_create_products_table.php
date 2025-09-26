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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->max(255);
            $table->string('slug')->unique()->max(255);
            $table->text('description')->nullable();
            $table->decimal('current_price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->boolean('in_stock')->default(true);
            $table->float('rating')->default(0);
            $table->integer('reviews_count')->default(0);
            $table->integer('sale_percentage')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
