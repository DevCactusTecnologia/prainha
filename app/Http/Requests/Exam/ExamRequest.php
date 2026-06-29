<?php

namespace App\Http\Requests\Exam;

use App\Helpers\Sanitize;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExamRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'name' => ['required', 'max:191', 'unique:exams'],
                'abbreviation' =>['required', 'max:191', 'unique:exams'],
                'category' =>['required', 'max:191'],
                'deadline' =>['required', 'integer'],
                'destiny' =>['nullable', 'max:191'],
                'label_group' =>['nullable', 'max:191'],
                'quantity_label' =>['nullable', 'integer'],
                'exam_kit' =>['nullable', 'integer'],
                'exam_editor' =>['nullable', 'max:65000'],
                'code' =>['nullable', 'digits:10'],
            ];
        }

        return [
            'name' => ['required', 'max:191', Rule::unique('exams')->ignore($this->exam->id)],
            'abbreviation' => ['required', 'max:191', Rule::unique('exams')->ignore($this->exam->id)],
            'category' =>['required', 'max:191'],
            'deadline' =>['required', 'integer'],
            'destiny' =>['nullable', 'max:191'],
            'label_group' =>['nullable', 'max:191'],
            'quantity_label' =>['nullable', 'integer'],
            'exam_kit' =>['nullable', 'integer'],
            'exam_editor' =>['nullable', 'max:65000'],
            'model_id' =>['required', 'integer'],
            'code' =>['nullable', 'digits:10'],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nome',
            'abbreviation' => 'abreviação',
            'category' => 'abreviação',
            'deadline' => 'prazo',
            'destiny' => 'destino',
            'label_group' => 'grupo de rótulos',
            'quantity_label' => 'quantidade de etiquetas',
            'exam_kit' => 'kit',
            'exam_editor' => 'editor de texto',
            'code' => 'código do exame',
        ];
    }

    protected function prepareForValidation() 
    {
        $actionName = $this->isMethod('POST') ? 'criado' : 'alterado';

        $this->merge([
            'name' => trim(mb_strtoupper($this->name)),
            'abbreviation' => trim(mb_strtoupper($this->abbreviation)),
            'code' => $this->code ? Sanitize::number($this->code) : '',
            'message' => "Exame {$actionName} com sucesso!",
        ]);
    }
}
