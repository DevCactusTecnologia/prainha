<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\Shared\ActiveEnum as Status;

class Company extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('is_active', Status::ACTIVE->value);
    }

}
