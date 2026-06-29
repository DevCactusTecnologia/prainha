<?php

namespace App\Models\Exam;

use App\Enums\Shared\ActiveEnum as Status;
use Illuminate\Database\Eloquent\Model as ModelEloquent;
use Illuminate\Support\Facades\DB;

class Exam extends ModelEloquent
{
    protected $table = 'exams'; 

    protected $fillable = [
        'abbreviation',
        'name',
        'category',
        'deadline',
        'team',
        'destiny',
        'label_group',
        'quantity_label',
        'exam_kit',
        'exam_support',
        'exam_editor',
        'model_id',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => Status::class
    ];

    public $timestamps = false;

    // METHODS

    public static function listContentLong()
    {
        // 186 - COLESTEROL TOTAL E FRAÇÕES
        // 347 - HEMOGRAMA
        // 709 - URINA DE JATO MÉDIO

        return [
            186, 347, 709
        ];  
    }

    public static function withMapPageSeparated()
    {
        // 347 - HEMOGRAMA COMPLETO,
        // 367 - HIV 1 e 2 (Anticorpos),
        // 546 - PARASITOLOGICO DE FEZES,
        // 709 - URINA DE JATO MEDIO

        return [
            347, 367, 546, 709
        ];  
    }

    public static function searchByName(string|null $name)
    {
        if (! $name) {
            return [];
        }

        return DB::select(
            "SELECT 
                id, name, abbreviation, category, deadline, destiny,
                label_group, quantity_label, exam_kit, is_active
            FROM exams
            WHERE name LIKE '%{$name}%'
            OR abbreviation LIKE '%{$name}%'
            LIMIT 50"
        );
    }

    public static function searchAbbreviation(string $filter)
    {
        $abbreviations = DB::select(
            "SELECT id, name, abbreviation
            FROM exams 
            WHERE abbreviation LIKE '{$filter}%' AND is_active = 1
            LIMIT 50"
        );

        return json_decode(json_encode($abbreviations), true);
    }

    public static function searchName(string $filter)
    {
        $names = DB::select(
            "SELECT id, name, abbreviation
            FROM exams 
            WHERE name LIKE '%{$filter}%' AND is_active = 1
            LIMIT 50"
        );

        return json_decode(json_encode($names), true);
    }

    // SCOPES

    public function scopeActive($query)
    {
        return $query->where('is_active', Status::ACTIVE->value);
    }

    // RELATIONSHIPS

    public function parameters()
    {
        return $this->hasMany(NewParameter::class, 'exam_id');
    }

    public function filters()
    {
        return $this->hasMany(Filter::class, 'exam_id');
    }

    public function models()
    {
        return $this->hasMany(Model::class, 'exam_id');
    }

    public function model()
    {
        return $this->belongsTo(Model::class, 'model_id');
    }
}
