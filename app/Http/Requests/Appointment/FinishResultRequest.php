<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class FinishResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    protected function prepareForValidation() 
    {
        $this->merge([
            'message' => 'Atendimento finalizado com sucesso, documento pronto para ser visualizado e impresso.',
        ]);
    }
}
