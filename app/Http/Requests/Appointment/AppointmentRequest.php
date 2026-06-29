<?php

namespace App\Http\Requests\Appointment;

use App\Models\Appointment\Appointment;
use Carbon\Carbon;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'appointment_date' => ['required', 'date_format:Y-m-d'],
            'appointment_for' =>['required', 'integer'],
            'appointment_with' => ['required', 'integer'],
            'unity_id' => ['required', 'integer'],
            'company_id' => ['required', 'integer'],
            'exam_biomedicals' => ['required', 'array', 
                function ($attribute, $value, $fail) {
                    if (count(array_filter($this->exam_ids)) !== count(array_filter($this->exam_biomedicals))) {
                        $fail('Selecione todos os analistas que irão analisar os exames!');
                    }
                }
            ],
            'exam_ids' => ['required', 'array'],
            'exam_collected_at' => ['required', 'array',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($this->exam_ids)) !== count(array_filter($this->exam_collected_at))) {
                        $fail('Selecione todas as datas de coletas dos exames que serão analisados!');
                    }
                }
            ],
            'delivery_date' => ['nullable', 'date_format:Y-m-d'],
            'booked_by' => ['required', 'integer',
                function ($attribute, $value, $fail) {
                    if ($this->isMethod('POST')) {
                        if (count(array_filter($this->documents_types ?: [])) !== count(array_filter($this->documents ?: []))) {
                            $fail('Selecione todos os tipos e documentos que serão carregados!');
                        }
                    }
                }
            ],
            'fast' => ['nullable', 'in:yes,no'],
            'dum' => ['nullable', 'date_format:Y-m-d'],
            'priority_id' => ['nullable', 'integer'],
            'access_key' => ['required', 'digits:8'],
            'guide_number' => ['nullable', 'integer'],
            'observation' => ['nullable', 'max:65000'],
        ];
    }

    public function attributes()
    {
        return [
            'appointment_date' => 'data de registro',
            'appointment_for' => 'paciente',
            'appointment_with' => 'solicitante',
            'exam_ids' => 'exames',
            'exam_biomedicals' => 'analistas',
            'exam_collected_at' => 'datas das coletas dos exames',
            'health_insurance' => 'convênio',
            'delivery_date' => 'Data de entrega',
            'fast' => 'jejum',
            'dum' => 'dum',
            'priority_id' => 'prioridade',
            'guide_number' => 'nº da guia',
            'access_key' => 'chave de acesso',
            'observation' => 'observações, doenças e medicamentos',
        ];
    }

    protected function prepareForValidation() 
    {
        $newDate = Carbon::createFromFormat('d/m/Y', $this->appointment_date)->format('Y-m-d');
        $user = Sentinel::getUser();

        if ($this->isMethod('POST')) {
            do {
                $accessKey = random_int(10000000, 99999999);
                $keyExists = Appointment::firstWhere('access_key', $accessKey);

                if (! $keyExists) { break; }
            } while (true);
        } else {
            $accessKey = $this->access_key; 
        }

        $this->merge([
            'appointment_date' => $newDate,
            'booked_by' => $user->id,
            'access_key' => $accessKey,
        ]);
    }
}
