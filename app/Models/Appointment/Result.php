<?php
namespace App\Models\Appointment;

use App\Models\Exam\Exam;
use App\Models\Exam\NewParameter;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = "results";

    protected $fillable = [
        'appointment_id',
        'exam_id',
        'parameter_id',
        'result',
        'result_status',
    ];

    public $timestamps = false;

    // RELATIONSHIPS

    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }

    public function parameter()
    {
        return $this->belongsTo(NewParameter::class, 'parameter_id');
    }
}
