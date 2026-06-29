<?php

namespace App\Models;

use App\Enums\Shared\InactiveEnum;
use App\Http\Requests\ReceptionistRequest;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Receptionist extends Model
{
    protected $table = 'receptionist';

    protected $fillable = [
        'user_id',
        'cpf',
        'cns',
        'is_deleted',
    ];

    protected $casts = [
        'is_deleted' => InactiveEnum::class
    ];

    // METHODS

    public static function getUsersPaginate(int $limit)
    {
        $receptionistRole = Sentinel::findRoleBySlug('receptionist');

        return $receptionistRole->users()
            ->with('roles')
            ->orderByDesc('id')
            ->paginate($limit);
    }

    public static function saveImage(ReceptionistRequest $request)
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

    private static function updateImage(User $receptionist, ReceptionistRequest $request)
    {
        $pathAvatar = 'storage/images/users/.' . $receptionist->profile_photo;

        if (File::exists($pathAvatar)) {
            File::delete($pathAvatar);
        }

        $file = $request->file('profile_photo');
        $extention = $file->getClientOriginalExtension();
        $imageName = time() . '.' . $extention;
        $file->move(public_path('storage/images/users'), $imageName);

        return $imageName;
    }

    public static function updateDataToUser(User &$receptionist, ReceptionistRequest $request, int $userId)
    {
        if ($request->hasFile('profile_photo')) {
            $receptionist->profile_photo = self::updateImage($receptionist, $request);
        }

        $receptionist->first_name = $request->first_name;
        $receptionist->mobile = $request->mobile;
        $receptionist->email = $request->email;
        $receptionist->is_deleted = $request->is_deleted;
            
        if ($receptionist->password != $request->password) {
            $password = $request->password ? $request->password : config('app.DEFAULT_RECEPTIONIST_PASSWORD');
            $receptionist->password = password_hash($password, PASSWORD_DEFAULT);
        }
            
        $receptionist->updated_by = $userId;
        $receptionist->save();
    }

    public static function updateData(User $receptionist, ReceptionistRequest $request)
    {
        self::where('user_id', $receptionist->id)
            ->update([
                'cpf' => $request->cpf,
                'cns' => $request->cns,
                'is_deleted' => $request->is_deleted,
            ]);
    }

}
