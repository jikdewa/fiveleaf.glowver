<?php

// MarketplaceProduct.php (Model baru)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketplaceProduct extends Model
{
    protected $fillable = [
        'product_id', // ID dari sistem Anda
        'marketplace_id',
        'remote_product_id', // ID unik produk di Shopee/Tokopedia/TikTok
        'sku_marketplace',
        'sync_status'
    ];
}