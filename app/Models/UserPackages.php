<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class UserPackages extends Model
{
    use SoftDeletes;

    protected $tagName = 'User Packages';
    protected $table = 'user_packages';

    protected $fillable = [
        'user_id',
        'package_id',
        'payment_id',
        'purchased_by',
        'sessions_total',
        'sessions_used',
        'sessions_remaining',
        'validity_quantity',
        'validity_unit',
        'purchased_at',
        'expires_at',
        'usr_pkg_status',
        'notes',
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * relationship with users 
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * relationship with packages 
     */
    public function package()
    {
        return $this->belongsTo(Packages::class);
    }

    /**
     * relationship with payments 
     */
    public function payment()
    {
        return $this->belongsTo(Payments::class);
    }
    

    // Automatically set 'expires_at' when creating
    protected static function booted()
    {
        static::creating(function ($model) {
            $model->purchased_at = $model->purchased_at ?? now();

            // Set expires_at using internal method
            $model->expires_at = $model->calculateExpiry();
        });
    }

     // Logic: Calculate expiry based on quantity + unit
    public function calculateExpiry(): Carbon
    {
        return match($this->validity_unit) {
            'days' => $this->purchased_at->copy()->addDays($this->validity_quantity),
            'weeks' => $this->purchased_at->copy()->addWeeks($this->validity_quantity),
            'months' => $this->purchased_at->copy()->addMonths($this->validity_quantity),
            default => $this->purchased_at,
        };
    }

    // Check if package is currently usable
    public function isActive(): bool
    {
        return $this->expires_at->isFuture() && $this->sessions_remaining > 0;
    }
}
