<?php

namespace App\Observers;

use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        $comment->user_id = Auth::id();
    }
}
