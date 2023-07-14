<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\Request;
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

    public function create()
    {
        return view('admin.posts.create');
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Post $post)
    {
        $post = $this->postService->postDetails($post);
        return view('site.details', compact('post'));
    }


    public function edit(Post $post)
    {
        //
    }

    public function update(Request $request, Post $post)
    {
        //
    }

    public function destroy(Post $post)
    {
        //
    }
}
