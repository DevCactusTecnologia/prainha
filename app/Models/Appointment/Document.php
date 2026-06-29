<?php

namespace App\Models\Appointment;

use App\Enums\Appointment\DocumentTypeEnum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    protected $table = 'appointment_documents';

    protected $fillable = [
        'type_id',
        'path',
        'appointment_id',
    ];

    protected $casts = [
        'type_id' => DocumentTypeEnum::class,
    ];

    // ACCESSORS

    protected function link(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                asset("storage/files/{$this->appointment_id}/{$this->path}")
        );
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            get: fn () =>
                str_contains($this->path, 'pdf')
                    ? '<i class="bx bxs-file-pdf text-danger font-size-24"></i>'
                    : '<i class="bx bxs-file-image text-info font-size-24"></i>'
        );
    }

    // RELATIONSHIPS
   
    public function appointment(): BelongsTo
    {
        return $this->belongsTo(
            related: Appointment::class, 
            foreignKey: 'appointment_id',
        );
    }
}
