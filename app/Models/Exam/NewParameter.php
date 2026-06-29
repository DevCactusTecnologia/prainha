<?php

namespace App\Models\Exam;

use App\Enums\Shared\ActiveEnum as Status;
use Illuminate\Database\Eloquent\Model;

class NewParameter extends Model
{
    protected $table = 'new_parameter';
    
    protected $fillable = [
        'parameter',
        'type',
        'unit',
        'abbreviations',
        'standard_value',
        'formula',
        'size',
		'exam_id',
        'decimal_places',
        'decimal_mask',
        'block_recording_when_out_of_bounds',
        'mandatory_parameter',
        'minimum',
        'maximum',
        'imp_ruler',
        'previous_imp',
        'description',
        'reference_value',
        'support_parameter',
        'evolutionary_report_parameter',
        'required',
        'with_previous_result',
        'with_printed_map',
        'is_active',
    ];

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('is_active', Status::ACTIVE->value);
    }
}
