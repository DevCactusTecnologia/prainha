<?php

namespace App\Http\Requests\Exam;

use Illuminate\Foundation\Http\FormRequest;

class ModelRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'max:191'],
            'exam_editor' => ['required', 'max:65000'],
            'observation' => ['nullable', 'max:65000'],
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nome',
            'exam_editor' => 'editor de texto',
            'observation' => 'observações',
        ];
    }

    protected function prepareForValidation() 
    {   
        $actionName = $this->isMethod('POST') ? 'registrado' : 'alterado';

        $this->merge([
            'message' => "Modelo <strong>{$actionName}</strong> com sucesso!",
        ]);
    }
}
