<?php

namespace App\Http\Requests;

use App\Helpers\Sanitize;
use App\Models\Doctor;
use App\Models\User;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DoctorRequest extends FormRequest
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
                'is_deleted' => ['nullable', 'integer'],
            ];
        }

        $doctor = Doctor::firstWhere('user_id', $this->doctor->id);
        $user = User::find($this->doctor->id);
        
        return [
            'first_name' => ['required', 'max:191'],
            'last_name' => ['nullable', 'max:191'],
            'created_by' => ['nullable', 'integer'],
            'updated_by' => ['nullable', 'integer'],
            'doctor_cpf' =>['nullable', 'digits:11', Rule::unique('doctors')->ignore($doctor->id)],
            'doctor_cns' =>['nullable', 'digits:15', Rule::unique('doctors')->ignore($doctor->id)],
            'class_council_id' => ['required', 'integer'],
            'issuing_state_id' => ['required', 'integer'],
            'counsil_number' => ['required', 'max:100'],
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
            'first_name' => 'nome Completo',
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
            'doctor_cpf' => Sanitize::number($this->doctor_cpf),
            'doctor_cns' => Sanitize::number($this->doctor_cns),
            'email' => $this->email ? trim(mb_strtolower($this->email)) : null,
            'mobile' => Sanitize::number($this->mobile),
            'message' => "Médico <strong class='text-uppercase'>{$this->first_name}</strong> {$actionName} com sucesso!",
        ]);
    }

}
