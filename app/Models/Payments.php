<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'payment_reference',
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


    /**
     * hook into the creating event:
     */
    protected static function booted()
    {
        static::creating(function ($payment) {
            // Generate a reference only if not already set
            if (empty($payment->payment_reference)) {
                $payment->payment_reference = self::generateReference();
            }
        });
    }

    /**
     * Generate Reference
     */
    public static function generateReference(): string
    {
        $date = now()->format('Ymd'); // e.g. 20250924
        $random = strtoupper(Str::random(6)); // e.g. AB12CD

        return "REF-{$date}-{$random}";
    }

}
