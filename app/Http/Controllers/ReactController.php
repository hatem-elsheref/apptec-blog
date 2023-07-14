<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\React;
use App\Services\ReactService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReactController extends Controller
{
    public function __construct(private readonly ReactService $reactService){}

    public function index(Post $post) :View
    {
        $data = $this->reactService->listingAllPostReacts($post);

        return view('admin.posts.reacts.index', ['post' => $post, ...$data]);
    }

    public function destroy(Post $post, React $react) :RedirectResponse
    {
        $this->authorize('delete', $react);

        $response = $this->reactService->deleteReact($react);

        return redirect()->back()
            ->with('type', $response['type'])
            ->with('message', $response['message']);
    }
}
