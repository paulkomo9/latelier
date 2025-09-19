<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedules extends Model
{
    use SoftDeletes;

    protected $tagName = 'Schedules';
    protected $table = 'schedules';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'start_date_time',
        'end_date_time',
        'description',
        'estimated_time',
        'schedule_image',
        'slots',
        'slots_taken',
        'location',
        'location_latitude',
        'location_longitude',
        'recurring_status',
        'trainer_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'schedule_status',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'location_latitude' => 'decimal:8',
        'location_longitude' => 'decimal:8',
    ];

    
}
