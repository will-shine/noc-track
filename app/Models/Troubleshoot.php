<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Troubleshoot extends Model
{
    protected $fillable = [
        'ticket',
        'name',
        'client',
        'complaint',
        'incident_time',
        'response_time',
        'completion_time',
        'action',
        'root_cause',
        'handled_by',
        'priority',
        'status',
        'type',
        'notes',
        'images',
    ];
}
