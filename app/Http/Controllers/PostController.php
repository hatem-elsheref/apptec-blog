<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\VideoRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PostController extends Controller
{

    public function __construct(private readonly PostService $postService){}


    public function page() :View
    {
        $posts = $this->postService->listingAllPostsWithPagination();

        return view('site.home', compact('posts'));
    }

    public function index() :View
    {
        $posts = $this->postService->listingAllPosts();

        return view('admin.posts.index', compact('posts'));
    }

    public function create() :View
    {
        return view('admin.posts.2create');
    }


    public function store(PostRequest $request) :JsonResponse
    {
        $response = $this->postService->store($request);

        return $response instanceof Post
            ? response()->json(['post' => new PostResource($response), 'status' => true])
            : response()->json(['post' =>  null, 'status' => false]);
    }


    public function show(Post $post) :View
    {
        $post = $this->postService->postDetails($post);

        return view('site.details', compact('post'));
    }


    public function edit(Post $post) :View
    {
        return view('admin.posts.create', compact('post'));
    }

    public function update(PostRequest $request, Post $post) :RedirectResponse
    {
        $response = $this->postService->update($request, $post);

        return redirect()->route('posts.index')
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }

    public function destroy(Post $post) :RedirectResponse
    {
        $response = $this->postService->delete($post);

         return redirect()->route('posts.index')
             ->with('type', $response['type'])
             ->with('message', $response['message']);
    }

    public function upload(VideoRequest $request) :JsonResponse
    {
        if ($request->size === $request->end){
            $this->postService->upload($request);
            $finished = true;
        }
        return response()
            ->json([
                'message'  => 'success',
                'part'     => $request->end,
                'finished' => $finished ?? false]);
    }
}
