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
        Schema::create('marketplaces', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained();
            $table->string('platform_name'); // 'shopee', 'tokopedia', 'tiktok'
            $table->string('shop_id');
            $table->text('access_token');
            $table->text('refresh_token')->nullable();
            $table->timestamp('expired_at');
            $table->timestamps();
        });

        Schema::create('marketplace_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('marketplace_id')->constrained();
            $table->string('remote_product_id'); // ID produk di marketplace
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
