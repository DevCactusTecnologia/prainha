<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassCouncil extends Model
{
    protected $table = 'class_councils';

    protected $fillable = [
        'sigla',
        'name',
        'short_name',
        'filter',
    ];

}
