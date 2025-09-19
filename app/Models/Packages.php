<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Packages extends Model
{
    use SoftDeletes;

    protected $tagName = 'Packages';
    protected $table = 'packages';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'package',
        'description',
        'package_image',
        'sessions_total',
        'validity_quantity',
        'validity_unit',
        'currency',
        'amount',
        'tax_type',
        'tax',
        'total_amount',
        'package_status',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be mutated to dates.
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
