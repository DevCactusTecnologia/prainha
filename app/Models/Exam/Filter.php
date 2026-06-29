<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Model;

class Filter extends Model
{
    protected $table = 'exam_filters';

    protected $fillable = [
        'exam_id',
        'gender',
        'intial_age_year',
        'intial_age_month',
        'intial_age_day',
        'final_age_year',
        'final_age_month',
        'final_age_day',
        'exam_editor',
    ];

    // RELATIONSHIPS

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
