@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-5">
            @if(session()->has('type'))
                <div class="alert alert-{{session('type')}}">
                    {{session('message')}}
                </div>
            @endif
            <a class="btn btn-sm btn-primary" href="{{route('posts.index')}}">
                <i class="fa-solid fa-arrow-left"></i>
                Back To Posts
            </a>
            <a target="_blank" href="{{route('posts.show', $post->id)}}" class="btn btn-sm btn-warning">
                Read Post
            </a>
            <h3>All Comments Of Post </h3>
                <h6 class="badge text-bg-warning">{{$post->id}} - {{$post->title}}</h6>
            <div class="card mb-12">
                <div class="card-body">
                    <div class="card-title h4 text-center">
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Comment</th>
                            <th scope="col">Author</th>
                            <th scope="col">Status</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($comments as $comment)
                            <tr>
                                <th>{{$comment->id}}</th>
                                <td>{{\Illuminate\Support\Str::of($comment->comment)->limit(30)}}</td>
                                <td>{{$comment->user->name}}</td>
                                @if($comment->is_published)
                                    <td class="text-success">
                                        <i class="fa-solid fa-check"></i>
                                    </td>
                                @else
                                    <td class="text-danger">
                                        <i class="fa-solid fa-times"></i>
                                    </td>
                                @endif

                                <td class="text-nowrap">
                                    <button onclick="document.getElementById('delete-{{$comment->id}}').submit()" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                    <a href="{{route('posts.comments.edit', ['post' => $comment->post_id, 'comment' => $comment->id])}}" class="btn btn-sm btn-success">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <form id="delete-{{$comment->id}}" action="{{route('posts.comments.destroy', ['post' => $comment->post_id, 'comment' => $comment->id])}}" method="post"> @csrf @method('delete') </form>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
