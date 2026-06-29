<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class ReceptionListDoctor extends Model
{
    protected $table = 'reception_list_doctors';

    protected $fillable = [
        'doctor_id',
        'reception_id',
        'is_deleted',
    ];

    function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
