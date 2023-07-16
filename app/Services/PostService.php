<?php

namespace App\Services;

use App\Jobs\UploadVideoToVimeo;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PostService
{

    public function listingAllPostsWithPagination() :LengthAwarePaginator
    {
        return Post::query()
            ->published()
            ->with(['user', 'comments' => fn($query) => $query->published()])
            ->withCount('likes')
            ->withCount('disLikes')
            ->withCount('comments')
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

    public function postDetails($post) :Model
    {
        return Post::query()->where('id', $post->id)
            ->published()
            ->with('comments', fn($query) => $query->published()->latest())
            ->withCount('likes')
            ->withCount('disLikes')
            ->withCount('comments')
            ->first();
    }

    public function store($request) :Model
    {
        if ($request->image instanceof UploadedFile)
            $path = $request->image->storeAs('uploads' . DIRECTORY_SEPARATOR . 'posts', Str::uuid()->toString() . '.' . $request->image->getClientOriginalExtension());

        return Post::query()->create([
            ...$request->validated(),
            'video' => sprintf('v-%s', md5(sprintf('%s-%s', time(), Str::random(10)))),
            'image' => $path ?? ''
        ]);

    }

    public function update($request, $post) :Model
    {
        $post->update($request->validated());
        // upload image here

        return $post->fresh();
    }

    public function delete($post) :array
    {
        $path = storage_path('app' . DIRECTORY_SEPARATOR . $post->image);

        if (File::exists($path))
            File::delete($path);

        return $post->delete()
            ? ['type'    => 'success', 'message' => 'Deleted Successfully']
            : ['type'    => 'danger', 'message' => 'Failed To Update'];
    }

    public function upload($request) :void
    {
        $post = Post::query()->where('is_published', 0)->where('id', $request->post)->first();

        if ($post && File::exists($request->tmp)){
            UploadVideoToVimeo::dispatch($request->tmp, $request->user(), $post);
        }
    }
}
