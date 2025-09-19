<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use SoftDeletes;

    protected $tagName = 'Payments';
    protected $table = 'payments';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'transaction_id',
        'payment_gateway_currency',
        'payment_amount',
        'payment_processing_fee',
        'payment_tax',
        'payment_message',
        'balance_transaction',
        'payment_charge_outcome',
        'payment_method',
        'last4',
        'card_brand',
        'payment_start_created',
        'payment_end_created',
        'package_id',
        'payment_status',
        'deleted_by',
        'paid_by'
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'payment_amount' => 'decimal:2',
        'payment_processing_fee' => 'decimal:2',
        'payment_tax' => 'decimal:2',
        'payment_start_created' => 'datetime',
        'payment_end_created' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    // relation with packages
    public function package()
    {
        return $this->belongsTo(Packages::class);
        
    }

    //relation with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
