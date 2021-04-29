<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    protected $stopOnFirstFailure = false;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => ['required', 'max:255'],
            'body' => ['required'],
        ];
    }

    public function messages() {
        return [
            'title.required' => 'You need to a :attribute to your blog',
            'body.required' => 'Your blog needs a :attribute',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'heading',
            'body' => 'description'
        ];
    }
}
