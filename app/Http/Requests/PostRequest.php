<?php

namespace App\Http\Requests;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Auth\AuthRepositoryInterface;

class PostRequest extends FormRequest
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        parent::__construct();
        $this->authRepository = $authRepository;
    }

    public function authorize()
    {
        if(auth()->check()) {
            switch ($this->getMethod()) {
                case 'post':
                case 'POST':
                    return true;
                case 'PUT':
                case 'put':
                case 'DELETE':
                case 'delete':
                    $post = Post::find($this->route('post'));
                    return $post && $post->user_id == $this->authRepository->getAuthId();
            }
        }
        return false;
    }

    public function rules()
    {
        switch ($this->getMethod()) {
            case 'post':
            case 'POST':
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

    public function messages()
    {
        return [
            'title.required' => 'You need to a :attribute to your blog',
            'body.required' => 'Your blog needs a :attribute',
        ];
    }

    public function attributes()
    {
        return [
            'title' => 'heading',
            'body' => 'description',
        ];
    }
}
