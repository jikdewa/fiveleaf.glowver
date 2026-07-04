<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stores extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_code',
        'store_name',
        'phone',
        'address',
        'is_active',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}