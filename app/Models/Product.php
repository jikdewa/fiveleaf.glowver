<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $guarded = [];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function photos()
    {
        return $this->hasMany(ProductPhoto::class);
    }

    public function transactionDetails()
    {
        return $this->hasMany(TransactionDetail::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    protected static function booted()
    {
        static::deleting(function ($product) {

            // Hapus barcode
            if ($product->barcode_photo) {

                $barcodePath = 'barcodes/' . $product->barcode_photo;

                if (Storage::disk('public')->exists($barcodePath)) {
                    Storage::disk('public')->delete($barcodePath);
                }
            }

            // Hapus semua foto produk
            foreach ($product->photos as $photo) {

                $photoPath = 'products/' . $photo->photo;

                if (Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
            }

            // Hapus relasi kategori (opsional)
            $product->categories()->detach();
        });
    }
}