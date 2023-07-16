<?php

namespace App\Observers;

use App\Notifications\CommentPublishedSuccessfully;

class CommentObserver
{
    public function updated($comment)
    {
        if($comment->isDirty('is_published') && $comment->is_published){
            $comment->user->notify(new CommentPublishedSuccessfully($comment));
        }
    }
}
