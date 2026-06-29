<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'exam_id' => ['nullable'],
            'gender' =>['nullable'],
            'intial_age_year' =>['nullable'],
            'intial_age_month' => ['nullable'],
            'intial_age_day' => ['nullable'],
            'final_age_year' => ['nullable'],
            'final_age_month' => ['nullable'],
            'final_age_day' => ['nullable'],
            'exam_editor' => ['nullable'],
        ];
    }

    public function attributes()
    {
        return [
            'exam_id' => 'código do exame',
            'gender' => 'sexo',
            'intial_age_year' => 'ano inicial',
            'intial_age_month' => 'mês inicial',
            'intial_age_day' => 'dia inicial',
            'final_age_year' => 'ano final',
            'final_age_month' => 'mês final',
            'final_age_day' => 'dia final',
            'exam_editor' => 'editor de texto',
        ];
    }

    protected function prepareForValidation() 
    {   
        $actionName = $this->id ? 'atualizado' : 'criado';

        $this->merge([
            'message' => "Filtro {$actionName} com sucesso!",
        ]);
    }
}
