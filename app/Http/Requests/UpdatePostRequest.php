<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize()
    {
        $post = Post::find($this->route('post'));

        return $post && $post->user_id == auth()->id();
    }

    public function rules()
    {
        switch ($this->getMethod()) {
            case 'PUT':
            case 'put':
                return [
                    'title' => ['required', 'max:255'],
                    'body' => ['required'],
                ];
            case 'DELETE':
            case 'delete':
                return [];
        }
    }
}
