<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Patient;
use App\Helpers\Sanitize;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('POST')) {
            return [
                'first_name' => ['required', 'max:191'],
                'last_name' => ['nullable', 'max:191'],
                'patient_social_name' => ['nullable', 'max:191'],
                'created_by' => ['nullable', 'integer'],
                'updated_by' => ['nullable', 'integer'],
                'patient_cpf' =>['nullable', 'digits:11', 'unique:patients'],
                'cns' =>['nullable', 'digits:15', 'unique:patients'],
                'mother_name' =>['nullable', 'max:191'],
                'dob' =>['required', 'date_format:Y-m-d'],
                'gender' =>['required', 'in:Male,Female,Trans,LGBT,Other'],
                'address' => ['nullable', 'max:191'],
                'email' => ['nullable', 'max:191', 'unique:users'],
                'password' => ['nullable', 'max:191'],
                'mobile' => ['nullable', 'digits:11'],
                'new_profile_photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
                'is_deleted' => ['nullable', 'integer'],
            ];
        }

        $patient = Patient::firstWhere('user_id', $this->patient->id);
        $user = User::find($this->patient->id);
        
        return [
            'first_name' => ['required', 'max:191'],
            'last_name' => ['nullable', 'max:191'],
            'patient_social_name' => ['nullable', 'max:191'],
            'created_by' => ['nullable', 'integer'],
            'updated_by' => ['nullable', 'integer'],
            'patient_cpf' =>['nullable', 'digits:11', Rule::unique('patients')->ignore($patient->id)],
            'cns' =>['nullable', 'digits:15', Rule::unique('patients')->ignore($patient->id)],
            'mother_name' =>['nullable', 'max:191'],
            'dob' =>['required', 'date_format:Y-m-d'],
            'gender' =>['required', 'in:Male,Female,Trans,LGBT,Other'],
            'address' => ['nullable', 'max:191'],
            'email' => ['nullable', 'max:191', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'max:191'],
            'mobile' => ['nullable', 'digits:11'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
            'is_deleted' => ['nullable', 'integer'],
        ];
    }

    public function attributes()
    {
        return [
            'first_name' => 'nome completo',
            'patient_social_name' => 'nome completo',
            'patient_cpf' => 'CPF',
            'cns' => 'CNS',
            'mother_name' => 'Nome da mãe',
            'dob' => 'data de nascimento',
            'gender' => 'sexo biológico',
            'address' => 'endereço',
            'email' => 'e-mail',
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
            'password' => $this->password ?: config('app.DEFAULT_PASSWORD'),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'patient_cpf' => Sanitize::number($this->patient_cpf),
            'cns' => Sanitize::number($this->cns),
            'mother_name' => trim(mb_strtoupper($this->mother_name ?: '')),
            'email' => $this->email ? trim(mb_strtolower($this->email)) : null,
            'mobile' => Sanitize::number($this->mobile),
            'message' => "Paciente <strong class='text-uppercase'>{$this->first_name}</strong> {$actionName} com sucesso!",
        ]);
    }
}
