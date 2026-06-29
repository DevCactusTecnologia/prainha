<?php

namespace App\Models;

use App\Models\Appointment\Appointment;
use Illuminate\Database\Eloquent\Model;

class DoctorAvailableSlot extends Model
{
    protected $table = 'doctor_available_slots';

    protected $fillable = [
        'doctor_id',
        'doctor_available_id',
        'from',
        'to',
        'is_deleted'
    ];

    function appointment()
    {
        return $this->hasMany(Appointment::class, 'available_slot', 'id');
    }

}
