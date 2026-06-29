<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'abbreviation' => ['required', 'max:191', 'unique:categories'],
                'name' =>['required', 'max:191', 'unique:categories'],
            ];
        }

        return [
            'abbreviation' => ['required', 'max:191', Rule::unique('categories')->ignore($this->category->id)],
            'name' => ['required', 'max:191', Rule::unique('categories')->ignore($this->category->id)],
            'is_active' => ['required', 'integer', 'in:0,1'],
        ];
    }

    public function attributes()
    {
        return [
            'abbreviation' => 'abreviação',
            'name' => 'nome',
        ];
    }

    protected function prepareForValidation() 
    {
        $actionName = $this->isMethod('POST') ? 'criado' : 'alterado';

        $this->merge([
            'abbreviation' => trim(mb_strtoupper($this->abbreviation)),
            'name' => trim(mb_strtoupper($this->name)),
            'message' => "Setor {$actionName} com sucesso!",
        ]);
    }
}
