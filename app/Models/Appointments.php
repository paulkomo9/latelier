<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointments extends Model
{
    use SoftDeletes;

    protected $tagName = 'Appointments';
    protected $table = 'appointments';

    protected $fillable = [
        'title',
        'description',
        'appointment_image',
        'start_date_time',
        'end_date_time',
        'slots',
        'slots_taken',
        'is_all_day',
        'category',
        'color',
        'backgroundColor',
        'dragBackgroundColor',
        'borderColor',
        'is_editable',
        'schedule_id',
        'trainer_id',
        'deleted_by',
        'created_by',
        'updated_by',
        'deleted_by',
        'appointment_status',
    ];

    protected $casts = [
        'start_date_time' => 'datetime',
        'end_date_time' => 'datetime',
        'is_all_day' => 'boolean',
        'is_editable' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
}
