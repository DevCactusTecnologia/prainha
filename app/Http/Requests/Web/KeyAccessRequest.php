<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class KeyAccessRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'access_key' => ['required', 'digits:8'],
        ];
    }

    public function attributes()
    {
        return [
            'access_key' => '<strong>chave de acesso</strong>',
        ];
    }

}
