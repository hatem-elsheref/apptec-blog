@extends('layouts.master')
@section('content')
    <div class="row">
        @foreach($posts as $post)
            <div class="col-lg-4 mb-2">
                <!-- Blog post-->
                <div class="card mb-12">
                    <a href="{{route('posts.show', $post->id)}}"><img class="card-img-top" src="{{$post->image_url}}"/></a>
                    <div class="card-body">
                        <div class="small text-muted">{{$post->created_at->diffForHumans()}}</div>
                        <div class="small text-muted">
                            {{$post->likes_count}} <i class="fa-solid fa-thumbs-up text-success"></i>
                            {{$post->dis_likes_count}} <i class="fa-solid fa-thumbs-down text-danger"></i>
                            {{$post->comments_count}} <i class="fa-solid fa-comment text-warning"></i>
                        </div>
                        <h2 class="card-title h4">{{\Illuminate\Support\Str::of($post->title)->limit(30)}}</h2>
                        <p class="card-text">{{\Illuminate\Support\Str::of($post->body)->limit(80, '...etc')}}</p>
                        <a class="btn btn-primary" href="{{route('posts.show', $post->id)}}">Read more →</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Pagination-->
    <nav aria-label="Pagination">
        <hr class="my-5" />
        {!! $posts->appends(request()->query())->render('vendor.pagination.bootstrap-5') !!}
    </nav>
@endsection
