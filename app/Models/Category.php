<?php

namespace App\Models;

use App\Enums\Shared\ActiveEnum as Status;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'abbreviation',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => Status::class
    ];

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('is_active', Status::ACTIVE->value);
    }

}
