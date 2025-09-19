<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventsLogger extends Model
{
    use HasFactory, SoftDeletes;

    protected $tagName = 'Event Logger';
    protected $table = 'events_logger';

    /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */
    protected $fillable = [
        'user_id',
        'action',
        'module_section',
        'old_values',
        'new_values',
        'ip_address',
        'client_information',
        'created_by',
        'updated_by',
        'event_status',
        'deleted_by'
    ];

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array'
    ];
    

    protected $attributes = [
        'old_values' => '[]',
        'new_values' => '[]'
     ];

}
