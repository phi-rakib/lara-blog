<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getName()) {
            case 'api.user.login':
                return [
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                ];

            case 'api.user.registration':
                return [
                    'name' => ['required'],
                    'email' => ['required', 'email'],
                    'password' => ['required'],
                    'confirm_password' => ['required', 'same:password'],
                ];
        }
    }
}
