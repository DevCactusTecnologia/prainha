<?php

namespace App\Models\Exam;

use Illuminate\Database\Eloquent\Model as ModelEloquent;

class Model extends ModelEloquent
{
    protected $table = 'exam_models';

    protected $fillable = [
        'name',
        'exam_id',
        'exam_editor',
        'observation',
    ];

    // RELATIONSHIPS

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
