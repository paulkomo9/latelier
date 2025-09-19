<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bookings extends Model
{
    use SoftDeletes;

    protected $tagName = 'Bookings';
    protected $table = 'bookings';

    protected $fillable = [
        'reference',
        'user_id',
        'appointment_id',
        'booking_status',
        'deleted_by',
        'attendance_marked_by',
        'attended_at',
    ];

    protected $dates = [
        'attended_at',
        'deleted_at',
    ];
}
