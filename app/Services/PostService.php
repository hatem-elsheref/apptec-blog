<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class PostService
{

    public function listingAllPostsWithPagination() :LengthAwarePaginator
    {
        return Post::query()
            ->with(['user', 'comments' => fn($query) => $query->where('is_published', 1)])
            ->paginate(setting('site_frontend_pagination', 6));
    }

    public function listingAllPosts() :Collection
    {
        return Post::query()
            ->withCount('likes')
            ->withCount('disLikes')
            ->withCount('comments')
            ->get();
    }

    public function postReacts() :Collection
    {
        return Post::query()->with('reacts')->get();
    }

    public function postComments() :Collection
    {
        return Post::query()->with('comments')->get();
    }

    public function postDetails($post) :Model
    {
        return Post::query()->where('id', $post->id)
            ->with('comments')
            ->withCount('likes')
            ->withCount('disLikes')
            ->first();
    }

    public function createPost($request) :Model
    {
        $post = Post::query()->create($request->validated());
        // upload image here

        return  $post->fresh();
    }

    public function editPost($request, $post) :Model
    {
        $post->update($request->validated());
        // upload image here

        return $post->fresh();
    }

    public function deletePost($post) :bool
    {
        // delete image here

        return $post->delete();
    }
}
