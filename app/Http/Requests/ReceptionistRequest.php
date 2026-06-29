<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Helpers\Sanitize;
use App\Models\Receptionist;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReceptionistRequest extends FormRequest
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
                'cpf' =>['nullable', 'digits:11', 'unique:receptionist'],
                'cns' =>['nullable', 'digits:15', 'unique:receptionist'],
                'email' => ['nullable', 'max:191', 'unique:users'],
                'password' => ['nullable', 'max:191'],
                'mobile' => ['nullable', 'digits:11'],
                'new_profile_photo' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:500'],
                'is_deleted' => ['nullable', 'integer'],
            ];
        }

        $receptionist = Receptionist::firstWhere('user_id', $this->receptionist->id);
        $user = User::find($this->receptionist->id);
        
        return [
            'first_name' => ['required', 'max:191'],
            'last_name' => ['nullable', 'max:191'],
            'created_by' => ['nullable', 'integer'],
            'updated_by' => ['nullable', 'integer'],
            'cpf' =>['nullable', 'digits:11', Rule::unique('receptionist')->ignore($receptionist->id)],
            'cns' =>['nullable', 'digits:15', Rule::unique('receptionist')->ignore($receptionist->id)],
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
            'cpf' => 'CPF',
            'cns' => 'CNS',
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
            'password' => $this->password ? $this->password : config('app.DEFAULT_RECEPTIONIST_PASSWORD'),
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'cpf' => Sanitize::number($this->cpf),
            'cns' => Sanitize::number($this->cns),
            'email' => $this->email ? trim(mb_strtolower($this->email)) : null,
            'mobile' => Sanitize::number($this->mobile),
            'message' => "Recepcionista <strong class='text-uppercase'>{$this->first_name}</strong> {$actionName} com sucesso!",
        ]);
    }

}
