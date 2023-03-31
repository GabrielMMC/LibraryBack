<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'O campo Nome é obrigatório',
            'email.required' => 'O campo E-mail é obrigatório',
            'email.email' => 'O campo E-mail deve ser um E-mail válido',
            'password.required' => 'O campo Senha é obrigatório',
            'password.min' => 'O campo Senha deve ter no mínimo 6 caractéres',
            'password.max' => 'O campo Senha deve ter no máximo 50 caractéres',
        ];
    }

    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'login':
                return [
                    'email' => 'required|email',
                    'password' => 'required|min:6|max:100',
                ];
                break;
            case 'register':
                return [
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'password' => 'required|string|min:6|max:100',
                    'confirm_password' => 'required|string|min:6|max:100',
                    // 'document' => 'required|string',
                    // 'phone' => 'required|string',
                    // 'birthdate' => 'required|string',
                ];
                break;
            case 'store_user':
                return [
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'password' => 'required|string|min:6|max:100',
                    'document' => 'required|string',
                    'phone' => 'required|string',
                    'zip_code' => 'required',
                    'city' => 'required',
                    'state' => 'required',
                    'number' => 'required',
                    'nbhd' => 'required',
                    'street' => 'required',
                ];
                break;
            case 'update_user':
                return [
                    'id' => 'required',
                    'name' => 'required|string',
                    'email' => 'required|email',
                    'password' => 'required|string|min:6|max:100',
                    'document' => 'required|string',
                    'phone' => 'required|string',
                    'zip_code' => 'required|string',
                ];
                break;

            default:
                return [];
                break;
        endswitch;
    }
}
