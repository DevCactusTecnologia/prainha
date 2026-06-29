<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAvailableTime extends Model
{
    protected $table = 'doctor_available_times';

    protected $fillable = [
        'doctor_id',
        'from',
        'to',
        'is_deleted'
    ];
}
