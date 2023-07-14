<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;
use App\Models\Post;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function __construct(private readonly CommentService $commentService){}

    public function index(Post $post) :View
    {
        $comments = $this->commentService->listingAllPostComments($post);

        return view('admin.posts.comments.index', compact('post', 'comments'));
    }

    public function edit(Post $post, Comment $comment) :View
    {
        return view('admin.posts.comments.edit', compact('post', 'comment'));
    }
    public function update(CommentRequest $request, Post $post, Comment $comment) :RedirectResponse
    {
        $response = $this->commentService->update($request, $comment);

        return redirect()->route('posts.comments.index', $post->id)
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }

    public function destroy(Post $post, Comment $comment) :RedirectResponse
    {
        $this->authorize('delete', $comment);

        $response = $this->commentService->delete($comment);

        return redirect()->back()
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }
}
