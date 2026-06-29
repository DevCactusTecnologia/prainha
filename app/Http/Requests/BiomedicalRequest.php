<?php

namespace App\Http\Requests;

use App\Models\Biomedical;
use App\Helpers\Sanitize;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BiomedicalRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'first_name' => ['required', 'max:28'],
                'professional_type_id' => ['required', 'integer'],
                'last_name' => ['nullable', 'max:191'],
                'created_by' => ['nullable', 'integer'],
                'updated_by' => ['nullable', 'integer'],
                'doctor_cpf' =>['nullable', 'digits:11', 'unique:doctors'],
                'doctor_cns' =>['nullable', 'digits:15', 'unique:doctors'],
                'class_council_id' => ['required', 'integer'],
                'issuing_state_id' => ['required', 'integer'],
                'counsil_number' => ['required', 'max:100'],
                'email' => ['nullable', 'max:191', 'unique:users'],
                'password' => ['nullable', 'max:191'],
                'mobile' => ['nullable', 'digits:11'],
                'new_profile_photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
                'new_signature' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
                'is_deleted' => ['nullable', 'integer'],
            ];
        }

        $biomedical = Biomedical::firstWhere('user_id', $this->biomedical->id);
        $user = User::find($this->biomedical->id);
        
        return [
            'first_name' => ['required', 'max:28'],
            'professional_type_id' => ['required', 'integer'],
            'last_name' => ['nullable', 'max:191'],
            'created_by' => ['nullable', 'integer'],
            'updated_by' => ['nullable', 'integer'],
            'doctor_cpf' =>['nullable', 'digits:11', Rule::unique('biomedicalist')->ignore($biomedical->id)],
            'doctor_cns' =>['nullable', 'digits:15', Rule::unique('biomedicalist')->ignore($biomedical->id)],
            'class_council_id' => ['required', 'integer'],
            'issuing_state_id' => ['required', 'integer'],
            'counsil_number' => ['required', 'max:100'],
            'email' => ['nullable', 'max:191', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'max:191'],
            'mobile' => ['nullable', 'digits:11'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
            'signature' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
            'is_deleted' => ['nullable', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'nome completo',
            'professional_type_id' => 'tipo de profissional',
            'doctor_cpf' => 'CPF',
            'doctor_cns' => 'CNS',
            'class_council_id' => 'conselho de classe',
            'issuing_state_id' => 'estado Emissor',
            'counsil_number' => 'número de registro do conselho',
            'email' => 'E-mail',
            'password' => 'senha de acesso',
            'mobile' => 'nº de Contato',
            'is_deleted' => 'inativo',
        ];
    }

    protected function prepareForValidation() 
    {
        $user = Sentinel::getUser();
        $actionName = $this->isMethod('POST') ? 'criado' : 'alterado';

        $this->merge([
            'first_name' => trim(mb_strtoupper($this->first_name)),
            'last_name' => '',
            'password' => $this->password ? $this->password : config('app.DEFAULT_PASSWORD'),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'cpf' => Sanitize::number($this->cpf),
            'cns' => Sanitize::number($this->cns),
            'email' => $this->email ? trim(mb_strtolower($this->email)) : null,
            'mobile' => Sanitize::number($this->mobile),
            'message' => "Analista <strong class='text-uppercase'>{$this->first_name}</strong> {$actionName} com sucesso!",
        ]);
    }
}
