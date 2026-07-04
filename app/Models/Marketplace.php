<?php

// Marketplace.php (Model baru)
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    protected $fillable = [
        'store_id', // Relasi ke tabel stores
        'platform_name', // 'shopee', 'tokopedia', 'tiktok'
        'shop_id', // ID toko di marketplace
        'access_token',
        'refresh_token',
        'expired_at',
        'is_active'
    ];
}