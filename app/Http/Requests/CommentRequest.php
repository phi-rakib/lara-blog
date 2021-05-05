<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Auth\AuthRepositoryInterface;

class CommentRequest extends FormRequest
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        parent::__construct();
        $this->authRepository = $authRepository;
    }
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (auth()->check()) {
            switch ($this->getMethod()) {
                case 'post':
                case 'POST':
                    return true;
                case 'PUT':
                case 'put':
                case 'DELETE':
                case 'delete':
                    $comment = Comment::find($this->route('comment'));
                    return $comment && $comment->user_id == $this->authRepository->getAuthId();
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->getMethod()) {
            case 'post':
            case 'POST':
            case 'PUT':
            case 'put':
                return [
                    'body' => ['required'],
                ];
            case 'DELETE':
            case 'delete':
                return [];
        }
    }
}
