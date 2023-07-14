<?php

namespace App\Services;

use App\Models\Comment;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function listingAllPostComments($post) :Collection
    {
        return Comment::query()->with('user')->where('post_id', $post->id)->get();
    }

    public function update($request, $comment) :array
    {
        try {
            $comment->update($request->validated());
            return [
                'type'    => 'success',
                'message' => 'Updated Successfully'
            ];
        }catch (Exception $exception){
            Log::error($exception->getMessage());
            return [
                'type'    => 'danger',
                'message' => 'Failed To Update'
            ];
        }
    }

    public function deleteComment($comment) :array
    {
        return $comment->delete()
            ? ['type'    => 'success', 'message' => 'Deleted Successfully']
            : ['type'    => 'danger', 'message' => 'Failed To Update'];
    }
}

