<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{   
    protected $fillable = [
        'invoice_number',
        'user_id',
        'store_id',
        'transaction_date',
        'subtotal',
        'discount',
        'tax',
        'grand_total',
        'payment_method',
        'paid_amount',
        'change_amount',
        'status',
        'notes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
