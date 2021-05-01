<?php

namespace App\Http\Requests;

use App\Models\Comment;
use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
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
                    return $comment && $comment->user_id == auth()->id();
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
