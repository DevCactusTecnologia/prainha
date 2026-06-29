<?php

namespace App\Models;

use App\Enums\Shared\ActiveEnum as Status;
use Illuminate\Database\Eloquent\Model;

class ProfessionalType extends Model
{
    protected $table = 'biomedical_professional_types';

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
