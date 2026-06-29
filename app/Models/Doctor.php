<?php

namespace App\Models;

use App\Enums\Shared\InactiveEnum;
use App\Helpers\Pagination;
use App\Http\Requests\DoctorRequest;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Doctor extends Model
{
    protected $table = 'doctors';

    protected $fillable = [
        'user_id',
        'doctor_cpf',
        'doctor_cns',
        'class_council_id',
        'issuing_state_id',
        'counsil_number',
        'slot_time',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => InactiveEnum::class
    ];

    // METHODS

    public static function getUsersPaginate()
    {
        $limit = Pagination::getLimit();
        $doctorRole = Sentinel::findRoleBySlug('doctor');

        return $doctorRole->users()
            ->with(['roles', 'doctor' => function ($query) {
                return $query->with(['state', 'council']);
            }])
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public static function saveImage(DoctorRequest $request)
    {
        if (! $request->new_profile_photo) {
            return '';
        }

        $file = $request->file('new_profile_photo');
        $extention = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users/'), $imageName);

        return $imageName;
    }

    private static function updateImage(User $doctor, DoctorRequest $request)
    {
        $pathAvatar = 'storage/images/users/.' . $doctor->profile_photo;

        if (File::exists($pathAvatar)) {
            File::delete($pathAvatar);
        }

        $file = $request->file('profile_photo');
        $extention = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users'), $imageName);

        return $imageName;
    }

    public static function updateDataToUser(User &$doctor, DoctorRequest $request, int $userId)
    {
        if ($request->hasFile('profile_photo')) {
            $doctor->profile_photo = self::updateImage($doctor, $request);
        }

        $doctor->first_name = $request->first_name;
        $doctor->mobile = $request->mobile;
        $doctor->email = $request->email;
        $doctor->is_deleted = $request->is_deleted;
            
        if ($doctor->password != $request->password) {
            $password = $request->password ? $request->password : config('app.DEFAULT_PASSWORD');
            $doctor->password = password_hash($password, PASSWORD_DEFAULT);
        }
            
        $doctor->updated_by = $userId;
        $doctor->save();
    }

    public static function updateData(User $doctor, DoctorRequest $request)
    {
        self::where('user_id', $doctor->id)
            ->update([
                'doctor_cpf' => $request->doctor_cpf,
                'doctor_cns' => $request->doctor_cns,
                'class_council_id' => $request->class_council_id,
                'issuing_state_id' => $request->issuing_state_id,
                'counsil_number' => $request->counsil_number,
                'is_deleted' => $request->is_deleted,
            ]);
    }

    public static function getAll()
    {
        return DB::select(
            "SELECT 
                users.id,
                users.first_name,
                users.last_name
            FROM doctors
            INNER JOIN users
            ON doctors.user_id = users.id
            WHERE doctors.is_deleted = 0"
        );
    }

    public static function search(string|null $value)
    {
        if (! $value) {
            return [];
        }

        return DB::select(
            "SELECT 
                users.id, 
                users.first_name, 
                class_councils.name AS class_council_name, 
                states.name AS state_name, 
                IFNULL(doctors.counsil_number, '') AS counsil_number,
                users.is_deleted
            FROM doctors
            INNER JOIN users
            ON doctors.user_id = users.id
            INNER JOIN class_councils
            ON doctors.class_council_id = class_councils.id
            INNER JOIN states
            ON doctors.issuing_state_id = states.id
            WHERE users.first_name LIKE '%{$value}%'
            OR doctors.doctor_cpf LIKE '%{$value}%'
            OR doctors.doctor_cns LIKE '%{$value}%'
            LIMIT 50"
        );
    }

    // RELATIONSHIPS

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function council()
    {
        return $this->belongsTo(ClassCouncil::class, 'class_council_id');
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'issuing_state_id');
    }
}
