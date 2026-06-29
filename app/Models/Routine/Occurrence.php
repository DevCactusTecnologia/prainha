<?php

namespace App\Models\Routine;

use App\Enums\Appointment\OccurrenceStatusEnum;
use App\Enums\Appointment\ResolutionEnum;
use App\Models\Appointment\Appointment;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Occurrence extends Model
{
    protected $table = 'appointment_occurrences';

    protected $fillable = [
        'appointment_id',
        'motive',
        'solution_id',
        'status',
        'user_id',
        'registered_at',
    ];

    protected $casts = [
        'solution_id' => ResolutionEnum::class,
        'status' => OccurrenceStatusEnum::class,
        'registered_at' => 'datetime',
    ];

    public $timestamps = false;

    // METHODS

    public static function total(): object
    {
        return DB::select(
            "SELECT
                SUM(IF(appointment_occurrences.status = 0, 1, 0)) AS pendings,
                SUM(IF(appointment_occurrences.status = 1, 1, 0)) AS partials,
                SUM(IF(appointment_occurrences.status = 2, 1, 0)) AS resolveds
            FROM appointment_occurrences"
        )[0];
    }

    // RELATIONSHIPS

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(
            related: Appointment::class, 
            foreignKey: 'appointment_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            related: User::class, 
            foreignKey: 'user_id',
        );
    }

    public function procediments(): HasMany
    {
        return $this->hasMany(
            related: Procediment::class,
            foreignKey: 'occurrence_id',
        );
    }
    
}
