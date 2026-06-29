<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser;
use Illuminate\Notifications\Notifiable;
use App\Http\Requests\BiomedicalRequest;
use App\Http\Requests\DoctorRequest;
use App\Http\Requests\PatientRequest;
use App\Http\Requests\ReceptionistRequest;
use App\Models\Doctor;
use App\Models\Patient;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class User extends EloquentUser
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'mobile',
        'profile_photo',
        'created_by',
        'updated_by',
        'permissions',
        'is_deleted',
    ];

    // METHODS

    public static function createUserDoctor(DoctorRequest $request)
    {
        $doctor = Sentinel::registerAndActivate($request->all());
        $role = Sentinel::findRoleBySlug('doctor');
        $role->users()->attach($doctor);

        return $doctor->id;
    }

    public static function findByDoctorId(int $doctorId)
    {
        $user = Sentinel::getUser();

        return $user::whereHas('roles', function ($query) { 
                $query->where('slug', 'doctor'); 
            })
            ->where('id', $doctorId)
            ->first();
    }

    public static function createUserBiomedical(BiomedicalRequest $request)
    {
        $biomedical = Sentinel::registerAndActivate($request->all());
        $role = Sentinel::findRoleBySlug('biomedical');
        $role->users()->attach($biomedical);

        return $biomedical->id;
    }

    public static function findByBiomedicalId(int $biomedicalId)
    {
        $user = Sentinel::getUser();

        return $user::whereHas('roles', function ($query) { 
                $query->where('slug', 'biomedical'); 
            })
            ->where('id', $biomedicalId)
            ->first();
    }

    public static function createUserReceptionist(ReceptionistRequest $request)
    {
        $receptionist = Sentinel::registerAndActivate($request->all());
        $role = Sentinel::findRoleBySlug('receptionist');
        $role->users()->attach($receptionist);

        return $receptionist->id;
    }

    public static function findByReceptionistId(int $receptionistId)
    {
        $user = Sentinel::getUser();

        return $user::whereHas('roles', function ($query) { 
                $query->where('slug', 'receptionist'); 
            })
            ->where('id', $receptionistId)
            ->first();
    }

    public static function createUserPatient(PatientRequest $request)
    {
        $patient = Sentinel::registerAndActivate($request->all());
        $role = Sentinel::findRoleBySlug('patient');
        $role->users()->attach($patient);

        return $patient->id;
    }

    public static function findByPatientId(int $patientId)
    {
        $user = Sentinel::getUser();

        return $user::whereHas('roles', function ($query) { 
                $query->where('slug', 'patient'); 
            })
            ->where('id', $patientId)
            ->first();
    }

    // RELATIONSHIPS

    function doctor() 
    {
        return $this->hasOne(Doctor::class);
    }

    function patient() 
    {
        return $this->hasOne(Patient::class);
    }

    function biomedical() 
    {
        return $this->hasOne(Biomedical::class);
    }

    function receptionist() 
    {
        return $this->hasOne(Receptionist::class);
    }

}
