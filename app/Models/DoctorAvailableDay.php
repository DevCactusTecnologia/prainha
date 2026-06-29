<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorAvailableDay extends Model
{
    protected $table = 'doctor_available_days';

    protected $fillable = [
        'doctor_id',
        'mon',
        'sun',
        'tue',
        'wen',
        'thu',
        'fri',
        'sat'
    ];
}
