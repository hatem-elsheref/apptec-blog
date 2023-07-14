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
            <h3>All Reacts Of Post (<small>{{$reacts->count()}}</small>)</h3>
                @foreach($groups as $type => $group)
                    <h5>
                        @if($type == 1)
                        <i class="fa-solid fa-thumbs-up text-success"></i> Likes
                        @else
                        <i class="fa-solid fa-thumbs-down text-danger"></i> Dis Likes
                        @endif
                        (<small>{{$group->count()}}</small>)
                    </h5>
                @endforeach
            <h6 class="badge text-bg-warning">{{$post->id}} - {{$post->title}}</h6>
            <div class="card mb-12">
                <div class="card-body">
                    <div class="card-title h4 text-center">
                    </div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Type</th>
                            <th scope="col">Author</th>
                            <th scope="col">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($reacts as $react)
                            <tr>
                                <th>{{$react->id}}</th>
                                @if($react->is_like)
                                    <td class="text-success">
                                        <i class="fa-solid fa-thumbs-up"></i>
                                    </td>
                                @else
                                    <td class="text-danger">
                                        <i class="fa-solid fa-thumbs-down"></i>
                                    </td>
                                @endif
                                <td>{{$react->user->name}}</td>
                                <td class="text-nowrap">
                                    <button onclick="document.getElementById('delete-{{$react->id}}').submit()" class="btn btn-sm btn-danger">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                            <form id="delete-{{$react->id}}" action="{{route('posts.reacts.destroy', ['post' => $react->post_id, 'react' => $react->id])}}" method="post"> @csrf @method('delete') </form>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
