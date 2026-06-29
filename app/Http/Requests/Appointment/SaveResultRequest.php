<?php

namespace App\Http\Requests\Appointment;

use Illuminate\Foundation\Http\FormRequest;

class SaveResultRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'appointment_id' => ['required', 'integer'],
            'exam_id' => ['required', 'integer'],
            'parameter_id' => ['required', 'array'],
            'parameter_value' => ['required', 'array'],
        ];
    }

    public function attributes()
    {
        return [
            'appointment_id' => 'Número do protocolo',
            'exam_id' => 'Código do exame',
            'parameter_id' => 'lista deparâmetros',
            'result' => 'resultados',
            'exams' => 'exames',
        ];
    }

    protected function prepareForValidation() 
    {
        $this->merge([
            'id' => $this->appointment_id,
            'message' => 'Resultado salvo com sucesso!',
            'messageFinished' => 'Atendimento finalizado com sucesso, documento pronto para ser visualizado e impresso.'
        ]);
    }
}
