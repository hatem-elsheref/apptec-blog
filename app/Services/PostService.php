<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;

class PostService
{

    public function listingAllPostsWithPagination() :LengthAwarePaginator
    {
        return Post::query()
            ->with(['user', 'comments' => fn($query) => $query->published()])
            ->latest()
            ->paginate(setting('site_frontend_pagination_general', 12));
    }

    public function listingAllPosts() :LengthAwarePaginator
    {
        return Post::query()
            ->withCount('likes')
            ->withCount('disLikes')
            ->withCount('comments')
            ->latest()
            ->paginate();
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

    public function store($request) :Model
    {
        $post = Post::query()->create($request->validated());
        // upload image here

        return  $post->fresh();
    }

    public function update($request, $post) :Model
    {
        $post->update($request->validated());
        // upload image here

        return $post->fresh();
    }

    public function deletePost($post) :array
    {
        $path = storage_path('app' . DIRECTORY_SEPARATOR . $post->image);

        if (File::exists($path))
            File::delete($path);

        return $post->delete()
            ? ['type'    => 'success', 'message' => 'Deleted Successfully']
            : ['type'    => 'danger', 'message' => 'Failed To Update'];
    }
}
