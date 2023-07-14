@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="row justify-content-between">
            <div class="col-md-12">
                @if(session()->has('type'))
                    <div class="alert alert-{{session('type')}}">
                        {{session('message')}}
                    </div>
                @endif
                <form method="POST" action="{{ route('posts.comments.update', ['post' => $post->id, 'comment' => $comment->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="comment" class="form-label">Comment</label>
                        <input class="form-control  @error('comment') is-invalid @enderror" name="comment" type="text" id="comment" value="{{$comment->comment}}">
                        @error('comment')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <input class="form-check-input" name="is_published" type="checkbox" id="is_published" @checked($comment->is_published)>
                        <label for="is_published" class="form-label">Is Published</label>
                    </div>

                    <div class="mb-3 text-lg-end">
                        <button type="submit" class="btn btn-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
