<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BiomedicalListDoctor extends Model
{
    protected $table = 'biomedical_list_doctors';

    protected $fillable = [
        'doctor_id',
        'biomedical_id',
        'is_deleted',
    ];

    function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
