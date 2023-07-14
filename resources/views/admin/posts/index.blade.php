@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-lg-12 mb-5">
            @if(session()->has('type'))
                <div class="alert alert-{{session('type')}}">
                    {{session('message')}}
                </div>
            @endif
            <h3>All Posts ({{$posts->total()}})</h3>
            <div class="card mb-12">

                <div class="card-body">
                    <div class="card-title h4 text-center">
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Author</th>
                            <th scope="col">#Comments</th>
                            <th scope="col">#Likes</th>
                            <th scope="col">#DisLikes</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                            <tr>
                                <th>{{$post->id}}</th>
                                <td>{{\Illuminate\Support\Str::of($post->title)->limit(30)}}</td>
                                <td>{{$post->user->name}}</td>
                                <td class="text-warning">
                                    <i class="fa-solid fa-comment"></i>
                                    {{$post->comments_count}}
                                </td>
                                <td class="text-success">
                                    <i class="fa-solid fa-thumbs-up"></i>
                                    {{$post->likes_count}}
                                </td>
                                <td class="text-danger">
                                    <i class="fa-solid fa-thumbs-down"></i>
                                    {{$post->dis_likes_count}}
                                </td>
                                <td class="text-nowrap">
                                    <a href="{{route('posts.comments.index', $post->id)}}" class="btn btn-sm btn-info">
                                        Comments
                                    </a>
                                    <a href="{{route('posts.reacts.index', $post->id)}}" class="btn btn-sm btn-secondary">
                                        Reacts
                                    </a>
                                    <button onclick="document.getElementById('delete-{{$post->id}}').submit()" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                    <a href="{{route('posts.edit', $post->id)}}" class="btn btn-sm btn-success">
                                        Edit
                                    </a>
                                    <a target="_blank" href="{{route('posts.show', $post->id)}}" class="btn btn-sm btn-warning">
                                        Read
                                    </a>
                                </td>
                            </tr>
                            <form id="delete-{{$post->id}}" action="{{route('posts.destroy', $post->id)}}" method="post"> @csrf @method('delete') </form>

                        @endforeach
                        </tbody>
                    </table>
                    {{$posts->render('vendor.pagination.bootstrap-5')}}
                </div>
            </div>
        </div>
    </div>
@endsection
