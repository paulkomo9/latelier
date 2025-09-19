<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkflowStatus extends Model
{
    use SoftDeletes;

    protected $tagName = 'Workflow Status';
    protected $table = 'workflow_status';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status_name',
        'css'
    ];
}
