@extends('layouts.master')
@section('content')
    <!-- Post content-->
    <article>
        <!-- Post header-->
        <header class="mb-4">
            <!-- Post title-->
            <h1 class="fw-bolder mb-1">{{$post->title}}</h1>
            <!-- Post meta content-->
            <div class="text-muted fst-italic mb-2">Posted on {{$post->created_at->format('l m, Y')}} by {{$post->user->name}}</div>
            <!-- Post details-->
            <span class="text-success">
                {{$post->likes_count}} <i class="fa-solid fa-thumbs-up"></i>
            </span>
            <span class="text-danger">
                {{$post->dis_likes_count}} <i class="fa-solid fa-thumbs-down"></i>
            </span>
            <span class="text-warning">
                {{$post->comments_count}} <i class="fa-solid fa-comment text-warning"></i>
            </span>
        </header>
        <!-- Preview image figure-->
        <figure class="mb-4"><img class="img-fluid rounded" src="{{$post->image_url}}" /></figure>
        <!-- Post content-->
        <section class="mb-5">
            {!! $post->body !!}
        </section>
    </article>
    <!-- Comments section-->
    <section class="mb-5">
        <div class="card bg-light">
            <div class="card-body">
                <!-- Comment form-->
                <form class="mb-4"><textarea class="form-control" rows="3" placeholder="Join the discussion and leave a comment!"></textarea></form>
                @foreach($post->comments as $comment)
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0"><img class="rounded-circle" style="width: 50px;height: 50px" src="{{$comment->user->avatar_url}}" /></div>
                    <div class="ms-3">
                        <div class="fw-bold">{{$comment->user->name}}</div>
                        {{$comment->comment}}
                    </div>
                    <button class="btn btn-sm btn-danger">delete</button>
                </div>
                    @endforeach
            </div>
        </div>
    </section>
@endsection
