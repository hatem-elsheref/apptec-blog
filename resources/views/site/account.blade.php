@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="row justify-content-between">
            <div class="col-md-8">
                @if(session()->has('type'))
                    <div class="alert alert-{{session('type')}}">
                        {{session('message')}}
                    </div>
                @endif
                <form method="POST" action="{{ route('account.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input class="form-control  @error('name') is-invalid @enderror" name="name" type="text" id="name" value="{{$user->name}}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input class="form-control  @error('email') is-invalid @enderror" name="email" type="email" id="email" value="{{$user->email}}">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="OldPassword" class="form-label">Old Password</label>
                        <input class="form-control  @error('old_password') is-invalid @enderror" name="old_password" type="password" id="OldPassword">
                        @error('old_password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input class="form-control  @error('password') is-invalid @enderror" name="password" type="password" id="password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Password</label>
                        <input class="form-control" name="password_confirmation" type="password" id="password_confirmation">
                    </div>


                    <div class="mb-3">
                        <input id="avatar" type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" >
                        @error('avatar')
                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                        @enderror
                    </div>
                    <div class="mb-3 text-lg-end">
                        <button type="submit" class="btn btn-success">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>

            </div>
            <div class="col-md-4">
                    <div class="mb-3">
                        <img src="{{$user->avatar_url}}" width="50%" height="50%" >
                    </div>
            </div>
        </div>
    </div>
@endsection
