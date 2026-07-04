<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();

            $table->string('sku')
                ->unique();

            $table->string('product_code')
                ->unique();

            $table->string('barcode')
                ->unique()
                ->nullable();

            $table->string('product_name');

            $table->text('description')
                ->nullable();

            $table->decimal('cost_price', 15, 2)
                ->default(0);

            $table->decimal('selling_price', 15, 2)
                ->default(0);

            $table->integer('stock')
                ->default(0);

            $table->integer('minimum_stock')
                ->default(5);

            $table->string('product_photo')
                ->nullable();

            $table->string('barcode_photo')
                ->nullable();

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();

            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};