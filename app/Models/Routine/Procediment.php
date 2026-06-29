<?php

namespace App\Models\Routine;

use App\Models\Routine\Occurrence as RoutineOccurrence;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Procediment extends Model
{
    protected $table = 'appointment_occurrence_procediments';

    protected $fillable = [
        'occurrence_id',
        'procediment',
        'registered_at',
        'user_id',
    ];

    protected $casts = [
        'registered_at' => 'date'
    ];

    public $timestamps = false;

    // RELATIONSHIPS

    public function occurrence(): BelongsTo
    {
        return $this->belongsTo(
            related: RoutineOccurrence::class, 
            foreignKey: 'occurrence_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class, 
            foreignKey: 'user_id',
        );
    }
    
}
