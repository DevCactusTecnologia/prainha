<?php

namespace App\Models;

use App\Models\Appointment\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $table = 'prescriptions';

    protected $fillable = [
        'patient_id',
        'symptoms',
        'diagnosis',
        'prescription_date',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    // METHODS

    public static function byDoctorId($doctorId, $limit)
    {
        return Prescription::with('patient')
            ->where('created_by', $doctorId)
            ->orderByDesc('id')
            ->paginate($limit, '*', 'prescriptions');
    }

    function doctor() 
    {
        return $this->hasOne(User::class,'id','created_by');
    }

    function patient() 
    {
        return $this->hasOne(User::class,'id','patient_id');
    }

    function appointment() 
    {
        return $this->hasOne(Appointment::class,'id','appointment_id');
    }
}
