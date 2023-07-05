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
        Schema::create('csv_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id')->unique();
            $table->string('name')->nullable();
            $table->string('sku')->nullable();
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->longText('variations')->nullable();
            $table->string('quantity')->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
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
