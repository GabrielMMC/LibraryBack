<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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

    public function rules()
    {
        switch (strtolower($this->route()->getActionMethod())):
            case 'store':
                return [
                    'title' => 'required',
                    'synopsis' => 'required',
                    'price' => 'required',
                    'gender_id' => 'required',
                    'writer' => 'required',
                    'pages' => 'required',
                    'thumb' => 'required',
                ];
                break;
            case 'update':
                return [
                    'id' => 'required',
                    'title' => 'required',
                    'synopsis' => 'required',
                    'price' => 'required',
                    'gender_id' => 'required',
                    'writer' => 'required',
                    'pages' => 'required',
                    'thumb' => 'required',
                ];
                break;

            default:
                return [];
                break;
        endswitch;
    }
}
